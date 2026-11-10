@include('admin.shell',['title'=>'Lab computers'])
<div class="page-intro"><p>Manage PC unit numbers, availability, and the station setup links used by the lab computers.</p></div>

<section class="computer-summary" aria-label="Computer unit summary">
    <article class="computer-metric"><span class="metric-icon metric-total">▦</span><div><p>Total units</p><strong>{{ $summary['total'] }}</strong></div></article>
    <article class="computer-metric"><span class="metric-icon metric-available">✓</span><div><p>Available</p><strong>{{ $summary['available'] }}</strong></div></article>
    <article class="computer-metric"><span class="metric-icon metric-occupied">◷</span><div><p>In use</p><strong>{{ $summary['occupied'] }}</strong></div></article>
    <article class="computer-metric"><span class="metric-icon metric-inactive">Ⅱ</span><div><p>Inactive</p><strong>{{ $summary['inactive'] }}</strong></div></article>
</section>

<section class="admin-card register-card">
    <div class="section-heading"><div><h2>Register a computer unit</h2><p>PC unit number is the unique identifier students see in the lab.</p></div></div>
    <form class="register-form" method="post" action="{{ route('admin.computers.add') }}">@csrf
        <label>PC unit number<input name="pc_number" placeholder="e.g. PC-01" required maxlength="20" pattern="[A-Za-z0-9][A-Za-z0-9._-]*" autocomplete="off"><span class="field-hint">Letters, numbers, dots, dashes, and underscores.</span></label>
        <button class="action-button primary" type="submit">Register unit</button>
    </form>
</section>

<section class="admin-card computers-list-card">
    <div class="section-heading"><div><h2>Computer units</h2><p>Update unit numbers, manage access, and refresh setup credentials.</p></div><span class="result-count">{{ $computerRows->count() }} shown</span></div>
    <form class="computer-filters" method="get" action="{{ route('admin.computers') }}">
        <label class="computer-search">Search unit<input type="search" name="q" placeholder="Search PC number" value="{{ $search }}"></label>
        <label>Availability<select name="status"><option value="all" @selected($status==='all')>All units</option><option value="available" @selected($status==='available')>Available</option><option value="occupied" @selected($status==='occupied')>In use</option><option value="inactive" @selected($status==='inactive')>Inactive</option></select></label>
        <div class="filter-actions"><button class="action-button primary" type="submit">Filter</button><a class="action-button secondary" href="{{ route('admin.computers') }}">Clear</a></div>
    </form>
    <div class="table-scroll"><table class="computers-table"><thead><tr><th>PC unit</th><th>Availability / session</th><th>Last connection</th><th>Session history</th><th>Management</th></tr></thead><tbody>
    @forelse($computerRows as $row) @php($pc=$row['computer'])
    <tr>
        <td><div class="unit-cell"><strong class="pc-name">{{ $pc->pc_number }}</strong><span>Lab computer</span></div></td>
        <td><div class="availability-cell"><span class="status-pill status-{{ strtolower($row['state']) }}"><span class="status-dot"></span>{{ $row['state'] }}</span>
            @if($row['currentStudent'])<span class="table-subtext">{{ $row['currentStudent'] }}@if($row['sessionStarted']) · since {{ $row['sessionStarted']->format('g:i A') }}@endif</span>@elseif($row['state']==='Available')<span class="table-subtext">Ready for student check-in</span>@elseif($row['state']==='Inactive')<span class="table-subtext">Station access is disabled</span>@endif
        </div></td>
        <td>{{ $pc->last_seen_at?->format('M j, Y · g:i A')??'Never connected' }}</td>
        <td><span class="history-count">{{ number_format($pc->sessions_count) }}</span><span class="table-subtext">{{ $pc->sessions_count===1?'record':'records' }}</span></td>
        <td><div class="computer-actions">
            <details class="unit-edit"><summary class="action-button secondary compact">Edit unit number</summary><form method="post" action="{{ route('admin.computers.rename',$pc) }}">@csrf @method('PATCH')<label>New PC unit number<input name="pc_number" value="{{ $pc->pc_number }}" required maxlength="20" pattern="[A-Za-z0-9][A-Za-z0-9._-]*"></label><button class="action-button primary compact" type="submit">Save</button></form></details>
            <form method="post" action="{{ route('admin.computers.toggle',$pc) }}" onsubmit="return {{ $pc->active ? 'confirm(\'Deactivate '.$pc->pc_number.'? Any active session will be marked interrupted.\')' : 'true' }}">@csrf<button class="action-button secondary compact" type="submit">{{ $pc->active?'Deactivate':'Activate' }}</button></form>
            @if($pc->active)<form method="post" action="{{ route('admin.computers.rotate',$pc) }}" onsubmit="return confirm('Regenerate setup credentials for {{ $pc->pc_number }}? Its saved setup link will stop working and any active session will be marked interrupted.')">@csrf<button class="action-button secondary compact" type="submit">New setup link</button></form>@else<button class="action-button secondary compact disabled-action" type="button" disabled title="Activate this unit first">New setup link</button>@endif
            @if($pc->sessions_count>0)<button class="action-button danger compact disabled-action" type="button" disabled title="This computer has session history">Delete <span aria-hidden="true">·</span> History protected</button>
            @else<form method="post" action="{{ route('admin.computers.delete',$pc) }}" onsubmit="return confirm('Delete computer unit {{ $pc->pc_number }}? This cannot be undone.')">@csrf @method('DELETE')<button class="action-button danger compact" type="submit">Delete unit</button></form>@endif
        </div></td>
    </tr>
    @empty<tr><td colspan="5" class="empty-state">@if($summary['total']===0)No computer units have been registered yet. Register a unit above to get started.@else>No units match these filters. Change the search or availability filter.@endif</td></tr>@endforelse
    </tbody></table></div>
</section>
