@include('admin.shell',['title'=>'Laboratory computers'])
<section class="card">
    <h2>Register a computer</h2>
    <form class="inline" method="post" action="{{ route('admin.computers.add') }}">
        @csrf
        <label>PC Unit Number<input name="pc_number" placeholder="PC-01" required maxlength="20"></label>
        <button class="button">Register</button>
    </form>
</section>
<section class="card"><div class="table-scroll"><table>
    <tr><th>PC unit</th><th>Status</th><th>Last seen</th><th>Actions</th></tr>
    @foreach($computers as $pc)
    <tr>
        <td>{{ $pc->pc_number }}</td>
        <td>{{ $pc->active?'Active':'Inactive' }}</td>
        <td>{{ $pc->last_seen_at?->format('M j, Y g:i A')??'Never' }}</td>
        <td>
            <form class="inline" method="post" action="{{ route('admin.computers.rotate',$pc) }}">@csrf<button class="small">Regenerate setup link</button></form>
            <form class="inline" method="post" action="{{ route('admin.computers.toggle',$pc) }}">@csrf<button class="small">{{ $pc->active?'Deactivate':'Activate' }}</button></form>
            <form class="inline" method="post" action="{{ route('admin.computers.delete',$pc) }}" onsubmit="return confirm('Delete this computer? Computers with session history cannot be deleted.')">@csrf @method('DELETE')<button class="small danger">Delete</button></form>
        </td>
    </tr>
    @endforeach
</table></div></section>
</main></body></html>
