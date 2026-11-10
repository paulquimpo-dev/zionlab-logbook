@include('admin.shell',['title'=>'Session history'])
<div class="page-intro"><p>Review student activity, filter records, and close interrupted sessions.</p></div>
<section class="admin-card session-card">
    <form class="filters" method="get">
        <label class="search-field">Search student or ID<input name="q" placeholder="Name or student ID" value="{{ request('q') }}"></label>
        <label>Status<select name="status"><option value="">Any status</option>@foreach(['ACTIVE','COMPLETED','INTERRUPTED'] as $x)<option value="{{ $x }}" @selected(request('status')===$x)>{{ ucfirst(strtolower($x)) }}</option>@endforeach</select></label>
        <label>Computer<select name="computer_id"><option value="">All computers</option>@foreach($computers as $c)<option value="{{ $c->id }}" @selected(request('computer_id')==$c->id)>{{ $c->pc_number }}</option>@endforeach</select></label>
        <label>Date<input type="date" name="date" value="{{ request('date') }}"></label>
        <div class="filter-actions"><button class="action-button primary" type="submit">Apply filters</button><a class="action-button secondary" href="{{ route('admin.sessions',array_merge(request()->query(),['csv'=>1])) }}">Export CSV</a></div>
    </form>
    <div class="table-scroll"><table><thead><tr><th>Student</th><th>Student ID</th><th>Grade / subject</th><th>Reason</th><th>PC unit</th><th>Time in</th><th>Time out</th><th>Status</th><th>Resolution</th></tr></thead><tbody>
    @forelse($sessions as $s)<tr><td class="student-cell"><strong>{{ $s->first_name }} {{ $s->last_name }}</strong></td><td>{{ $s->student_id??'—' }}</td><td>{{ $s->gradeLevel->name }}<span class="table-subtext">{{ $s->subject->name }}</span></td><td>{{ $s->usageReason->name }}@if($s->other_reason_details)<span class="table-subtext">{{ $s->other_reason_details }}</span>@endif</td><td><span class="pc-pill">{{ $s->computer->pc_number }}</span></td><td>{{ $s->time_in->format('M j, g:i A') }}</td><td>{{ $s->time_out?->format('M j, g:i A')??'—' }}</td><td><span class="status-pill status-{{ strtolower($s->status) }}">{{ ucfirst(strtolower($s->status)) }}</span></td><td>@if($s->status==='ACTIVE'||$s->status==='INTERRUPTED')<form class="resolve-form" method="post" action="{{ route('admin.resolve',$s) }}">@csrf<input name="resolution" required placeholder="Add resolution"><button class="action-button primary compact">Resolve</button></form>@else{{ $s->resolution??'—' }}@endif</td></tr>
    @empty<tr><td colspan="9" class="empty-state">No sessions match these filters.</td></tr>@endforelse
    </tbody></table></div>
    <div class="pagination-wrap">{{ $sessions->links() }}</div>
</section>
