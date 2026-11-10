<?php
namespace App\Http\Controllers;

use App\Models\{Computer,GradeLevel,LaboratorySession,Subject,UsageReason};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cookie,DB};

class LogbookController extends Controller
{
    private function station(Request $request): ?Computer {
        $credential=$request->cookie('lzds_station'); if(!$credential) return null;
        $pc=Computer::where('station_token_hash',hash('sha256',$credential))->where('active',true)->first();
        if($pc) $pc->update(['last_seen_at'=>now()]); return $pc;
    }
    public function enroll(string $credential) {
        $pc=Computer::where('station_token_hash',hash('sha256',$credential))->where('active',true)->first();
        abort_unless($pc, 404, 'This computer is not registered. Please contact the laboratory administrator.');
        $sessionSecret=\Illuminate\Support\Str::random(48);
        $pc->update(['station_token_hash'=>hash('sha256',$sessionSecret)]);
        return redirect('/')->withCookie(cookie('lzds_station',$sessionSecret,60*24*365,null,null,false,true,false,'Lax'));
    }
    public function index(Request $request) {
        $computer=$this->station($request); $active=$computer?LaboratorySession::where('computer_id',$computer->id)->where('status','ACTIVE')->first():null;
        return view('logbook', ['computer'=>$computer,'active'=>$active,'grades'=>GradeLevel::where('active',true)->orderBy('name')->get(),'subjects'=>Subject::where('active',true)->orderBy('name')->get(),'reasons'=>UsageReason::where('active',true)->orderBy('name')->get()]);
    }
    public function checkin(Request $request) {
        $pc=$this->station($request); abort_unless($pc,403,'This computer is not registered. Please contact the laboratory administrator.');
        $data=$request->validate(['first_name'=>'required|string|max:80','last_name'=>'required|string|max:80','student_id'=>'nullable|string|max:60','grade_level_id'=>'required|exists:grade_levels,id','subject_id'=>'required|exists:subjects,id','usage_reason_id'=>'required|exists:usage_reasons,id','other_reason_details'=>'nullable|string|max:255']);
        $session=DB::transaction(function() use($pc,$data){ if(LaboratorySession::where('computer_id',$pc->id)->where('status','ACTIVE')->lockForUpdate()->exists()) return null; return LaboratorySession::create($data+['computer_id'=>$pc->id,'time_in'=>now(),'status'=>'ACTIVE']); });
        return $session?redirect('/')->with('success','You are checked in. Remember to check out when you finish.'):back()->withInput()->withErrors(['session'=>'This computer already has an active session. Please check out the previous user first.']);
    }
    public function checkout(Request $request) {
        $pc=$this->station($request); abort_unless($pc,403,'This computer is not registered.');
        $done=DB::transaction(function() use($pc){$s=LaboratorySession::where('computer_id',$pc->id)->where('status','ACTIVE')->lockForUpdate()->first(); if(!$s)return false; $out=now(); $s->update(['time_out'=>$out,'duration_seconds'=>$s->time_in->diffInSeconds($out),'status'=>'COMPLETED']); return true;});
        return redirect('/')->with($done?'success':'notice',$done?'Check-out recorded. Thank you.':'There is no active session on this computer.');
    }
}
