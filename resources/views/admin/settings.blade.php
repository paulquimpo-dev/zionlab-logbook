@include('admin.shell',['title'=>'Dropdown options'])
<div class="page-intro"><p>Keep the student check-in choices accurate and easy to use.</p></div>
<div class="settings-grid">
@foreach(['grades'=>'Grade levels','subjects'=>'Subjects','reasons'=>'Usage reasons'] as $type=>$label)
<section class="admin-card settings-card">
    <div class="section-heading"><div><h2>{{ $label }}</h2><p>Manage options available in the student form.</p></div></div>
    <form class="settings-add-form" method="post" action="{{ route('admin.settings.add',$type) }}">@csrf
        <label class="sr-only" for="add-{{ $type }}">Add {{ strtolower($label) }}</label><input id="add-{{ $type }}" name="name" placeholder="Add {{ strtolower($label) }}" required maxlength="120"><button class="action-button primary" type="submit">Add</button>
    </form>
    <div class="option-list">
        @forelse($$type as $item)
        <div class="option-row"><span class="option-name">{{ $item->name }}</span><span class="status-pill {{ $item->active?'status-completed':'status-inactive' }}"><span class="status-dot"></span>{{ $item->active?'Active':'Inactive' }}</span>
            <div class="option-actions"><form method="post" action="{{ route('admin.settings.toggle',[$type,$item->id]) }}">@csrf<button class="action-button secondary compact">{{ $item->active?'Deactivate':'Activate' }}</button></form><form method="post" action="{{ route('admin.settings.delete',[$type,$item->id]) }}" onsubmit="return confirm('Delete this option? Options used in session history cannot be deleted.')">@csrf @method('DELETE')<button class="action-button danger compact">Delete</button></form></div>
        </div>
        @empty <p class="empty-state">No {{ strtolower($label) }} have been added.</p> @endforelse
    </div>
</section>
@endforeach
</div>
