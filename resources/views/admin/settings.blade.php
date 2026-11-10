@include('admin.shell',['title'=>'Form dropdowns'])
@foreach(['grades'=>'Grade levels','subjects'=>'Subjects','reasons'=>'Usage reasons'] as $type=>$label)
<section class="card">
    <h2>{{ $label }}</h2>
    <form class="inline" method="post" action="{{ route('admin.settings.add',$type) }}">
        @csrf<label>Add {{ strtolower($label) }}<input name="name" required maxlength="120"></label><button class="button">Add</button>
    </form>
    <div class="chips">
        @foreach($$type as $item)
        <span>{{ $item->name }} ({{ $item->active?'active':'inactive' }})
            <form class="inline" method="post" action="{{ route('admin.settings.toggle',[$type,$item->id]) }}">@csrf<button class="small">{{ $item->active?'Deactivate':'Activate' }}</button></form>
            <form class="inline" method="post" action="{{ route('admin.settings.delete',[$type,$item->id]) }}" onsubmit="return confirm('Delete this option? Options used in session history cannot be deleted.')">@csrf @method('DELETE')<button class="small danger">Delete</button></form>
        </span>
        @endforeach
    </div>
</section>
@endforeach
</main></body></html>
