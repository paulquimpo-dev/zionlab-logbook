<?php
namespace App\Http\Controllers;
use App\Models\{Computer,LaboratorySession,GradeLevel,Subject,UsageReason};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Hash};
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function login(){return view('admin.login');}
    public function authenticate(Request $r){$data=$r->validate(['email'=>'required|email','password'=>'required']); if(!auth()->attempt($data))return back()->withErrors(['email'=>'Email or password is incorrect.']); $r->session()->regenerate(); return redirect()->route('admin.dashboard');}
    public function logout(Request $r){auth()->logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('admin.login');}
    public function dashboard(){
        $computers=Computer::with(['sessions'=>fn($q)=>$q->where('status','ACTIVE')->latest('time_in')])->orderBy('pc_number')->get();
        $active=LaboratorySession::where('status','ACTIVE')->count();
        $completed=LaboratorySession::where('status','COMPLETED')->count();
        $today=LaboratorySession::whereDate('time_in',today())->count();
        $start=now()->subDays(6)->startOfDay();
        $counts=DB::table('laboratory_sessions')->selectRaw('DATE(time_in) as day, COUNT(*) as total')->where('time_in','>=',$start)->groupBy('day')->pluck('total','day');
        $weeklyUsage=collect(range(0,6))->map(function($offset)use($start,$counts){$date=$start->copy()->addDays($offset);return ['label'=>$date->format('D'),'date'=>$date->format('M j'),'total'=>(int)($counts[$date->format('Y-m-d')]??0)];});
        $computerRows=$computers->map(function($computer){$session=$computer->sessions->first();return ['pcNumber'=>$computer->pc_number,'active'=>$computer->active,'state'=>!$computer->active?'Inactive':($session?'Occupied':'Available'),'currentStudent'=>$session?$session->first_name.' '.$session->last_name:null,'lastSeen'=>$computer->last_seen_at?->format('M j, g:i A')];});
        $recentSessions=LaboratorySession::with(['computer','subject'])->latest('time_in')->limit(8)->get()->map(fn($s)=>['student'=>$s->first_name.' '.$s->last_name,'pc'=>$s->computer->pc_number,'subject'=>$s->subject->name,'timeIn'=>$s->time_in->format('g:i A'),'status'=>$s->status]);
        return view('admin.dashboard',['dashboard'=>['stats'=>['computers'=>$computers->count(),'occupied'=>$active,'available'=>$computers->where('active',true)->filter(fn($c)=>$c->sessions->isEmpty())->count(),'completedToday'=>LaboratorySession::where('status','COMPLETED')->whereDate('time_in',today())->count(),'sessionsToday'=>$today],'weeklyUsage'=>$weeklyUsage,'computers'=>$computerRows,'recentSessions'=>$recentSessions]]);
    }
    public function sessions(Request $r){$q=LaboratorySession::with(['computer','gradeLevel','subject','usageReason'])->orderByDesc('time_in'); foreach(['status','computer_id','grade_level_id','subject_id'] as $f)if($r->filled($f))$q->where($f,$r->$f); if($r->filled('date'))$q->whereDate('time_in',$r->date); if($r->filled('q'))$q->where(fn($w)=>$w->where('first_name','like','%'.$r->q.'%')->orWhere('last_name','like','%'.$r->q.'%')->orWhere('student_id','like','%'.$r->q.'%')); if($r->boolean('csv'))return response()->streamDownload(function()use($q){$out=fopen('php://output','w');fputcsv($out,['Name','Student ID','Grade','Subject','Reason','Computer','Time In','Time Out','Duration seconds','Status']);$q->chunk(500,function($rows)use($out){foreach($rows as $s)fputcsv($out,[$s->first_name.' '.$s->last_name,$s->student_id,$s->gradeLevel->name,$s->subject->name,$s->usageReason->name,$s->computer->pc_number,$s->time_in,$s->time_out,$s->duration_seconds,$s->status]);});fclose($out);},'logbook.csv'); return view('admin.sessions',['sessions'=>$q->paginate(30)->withQueryString(),'computers'=>Computer::all(),'grades'=>GradeLevel::all(),'subjects'=>Subject::all()]);}
    public function computers(Request $request){
        $computers=Computer::withCount('sessions')->with(['sessions'=>fn($q)=>$q->where('status','ACTIVE')->latest('time_in')])->orderBy('pc_number')->get();
        $summary=['total'=>$computers->count(),'inactive'=>$computers->where('active',false)->count(),'occupied'=>$computers->filter(fn($pc)=>$pc->active&&$pc->sessions->isNotEmpty())->count()];
        $summary['available']=$summary['total']-$summary['inactive']-$summary['occupied'];
        $rows=$computers->map(function($pc){$session=$pc->sessions->first();return ['computer'=>$pc,'state'=>!$pc->active?'Inactive':($session?'Occupied':'Available'),'currentStudent'=>$session?$session->first_name.' '.$session->last_name:null,'sessionStarted'=>$session?->time_in];});
        $search=trim((string)$request->query('q',''));$status=$request->query('status','all');
        if($search!=='')$rows=$rows->filter(fn($row)=>str_contains(mb_strtolower($row['computer']->pc_number),mb_strtolower($search)));
        if(in_array($status,['available','occupied','inactive'],true))$rows=$rows->filter(fn($row)=>strtolower($row['state'])===$status);
        return view('admin.computers',['computerRows'=>$rows->values(),'summary'=>$summary,'search'=>$search,'status'=>$status]);
    }
    public function addComputer(Request $r){
        $r->merge(['pc_number'=>mb_strtoupper(trim((string)$r->input('pc_number')))]);
        $d=$r->validate(['pc_number'=>'required|string|max:20|regex:/^[A-Z0-9][A-Z0-9._-]*$/|unique:computers,pc_number']);
        $secret=Str::random(48);$pc=Computer::create($d+['station_token_hash'=>hash('sha256',$secret),'active'=>true]);
        $this->auditComputer($r,'computer.created',$pc,['pc_number'=>$pc->pc_number]);
        return back()->with('enrollment_url',url('/station/'.$secret))->with('created_pc',$pc->pc_number);
    }
    public function renameComputer(Request $r,Computer $computer){
        $r->merge(['pc_number'=>mb_strtoupper(trim((string)$r->input('pc_number')))]);
        $data=$r->validate(['pc_number'=>['required','string','max:20','regex:/^[A-Z0-9][A-Z0-9._-]*$/',Rule::unique('computers','pc_number')->ignore($computer->id)]]);
        $previous=$computer->pc_number;$computer->update($data);
        $this->auditComputer($r,'computer.renamed',$computer,['from'=>$previous,'to'=>$computer->pc_number]);
        return back()->with('success',"Computer unit {$previous} was renamed to {$computer->pc_number}.");
    }
    public function rotateComputer(Request $r,Computer $computer){
        if(!$computer->active)return back()->withErrors(['computer'=>'Activate this computer unit before creating a new station setup link.']);
        $secret=Str::random(48);
        DB::transaction(function()use($r,$computer,$secret){
            $computer->update(['station_token_hash'=>hash('sha256',$secret)]);
            $this->interruptActiveSessions($computer,'Station setup credentials were regenerated by an administrator.');
            $this->auditComputer($r,'computer.credential_rotated',$computer);
        });
        return back()->with('enrollment_url',url('/station/'.$secret))->with('created_pc',$computer->pc_number);
    }
    public function toggleComputer(Request $r,Computer $computer){
        DB::transaction(function()use($r,$computer){
            $active=!$computer->active;$computer->update(['active'=>$active]);
            if(!$active)$this->interruptActiveSessions($computer,'Computer unit was deactivated by an administrator.');
            $this->auditComputer($r,$active?'computer.activated':'computer.deactivated',$computer,['pc_number'=>$computer->pc_number]);
        });
        return back()->with('success',$computer->active?"{$computer->pc_number} is active and ready for use.":"{$computer->pc_number} was deactivated.");
    }
    public function deleteComputer(Request $request,Computer $computer){if($computer->sessions()->exists())return back()->withErrors(['delete'=>'This computer has session history and cannot be deleted. Deactivate it to keep its records.']);DB::transaction(function()use($request,$computer){DB::table('audit_logs')->insert(['user_id'=>auth()->id(),'action'=>'computer.deleted','subject_type'=>'computer','subject_id'=>$computer->id,'details'=>json_encode(['pc_number'=>$computer->pc_number]),'ip_address'=>$request->ip(),'created_at'=>now(),'updated_at'=>now()]);$computer->delete();});return back()->with('success','Computer registration deleted.');}
    public function settings(){return view('admin.settings',['grades'=>GradeLevel::orderBy('name')->get(),'subjects'=>Subject::orderBy('name')->get(),'reasons'=>UsageReason::orderBy('name')->get()]);}
    public function storeOption(Request $r,string $type){$map=['grades'=>GradeLevel::class,'subjects'=>Subject::class,'reasons'=>UsageReason::class];abort_unless(isset($map[$type]),404);$d=$r->validate(['name'=>'required|string|max:120']);$map[$type]::create($d+['active'=>true]);return back();}
    public function toggleOption(string $type,int $id){$map=['grades'=>GradeLevel::class,'subjects'=>Subject::class,'reasons'=>UsageReason::class];abort_unless(isset($map[$type]),404);$m=$map[$type]::findOrFail($id);$m->update(['active'=>!$m->active]);return back();}
    public function deleteOption(Request $request,string $type,int $id){$map=['grades'=>[GradeLevel::class,'grade_level_id'],'subjects'=>[Subject::class,'subject_id'],'reasons'=>[UsageReason::class,'usage_reason_id']];abort_unless(isset($map[$type]),404);[$model,$foreignKey]=$map[$type];$option=$model::findOrFail($id);if(LaboratorySession::where($foreignKey,$option->id)->exists())return back()->withErrors(['delete'=>'This option is used by existing sessions and cannot be deleted. Deactivate it to keep historical records.']);DB::transaction(function()use($request,$type,$option){DB::table('audit_logs')->insert(['user_id'=>auth()->id(),'action'=>'option.deleted','subject_type'=>$type,'subject_id'=>$option->id,'details'=>json_encode(['name'=>$option->name]),'ip_address'=>$request->ip(),'created_at'=>now(),'updated_at'=>now()]);$option->delete();});return back()->with('success','Option deleted.');}
    public function resolve(Request $r,LaboratorySession $session){$d=$r->validate(['resolution'=>'required|string|max:255']);abort_unless($session->status==='ACTIVE'||$session->status==='INTERRUPTED',422);$session->update(['status'=>'INTERRUPTED','resolution'=>$d['resolution']]);DB::table('audit_logs')->insert(['user_id'=>auth()->id(),'action'=>'session.resolved_as_interrupted','subject_type'=>'session','subject_id'=>$session->id,'details'=>json_encode($d),'ip_address'=>$r->ip(),'created_at'=>now(),'updated_at'=>now()]);return back();}
    private function interruptActiveSessions(Computer $computer,string $resolution):void{
        $endedAt=now();
        $computer->sessions()->where('status','ACTIVE')->get()->each(function($session)use($resolution,$endedAt){
            $session->update(['status'=>'INTERRUPTED','resolution'=>$resolution,'time_out'=>$endedAt,'duration_seconds'=>max(0,(int)$session->time_in->diffInSeconds($endedAt))]);
        });
    }
    private function auditComputer(Request $request,string $action,Computer $computer,array $details=[]):void{
        DB::table('audit_logs')->insert(['user_id'=>auth()->id(),'action'=>$action,'subject_type'=>'computer','subject_id'=>$computer->id,'details'=>$details?json_encode($details):null,'ip_address'=>$request->ip(),'created_at'=>now(),'updated_at'=>now()]);
    }
}
