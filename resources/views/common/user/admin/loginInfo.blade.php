<p class="alert alert-info"><b>Email</b>: {{ $user->email }}</p>
<p class="alert alert-info"><b>Mobile No</b>: {{ $user->phone }}</p>


@if(Auth::user()->user_type_id=='1')
    @if($user->status=='1')
        <p class="p-2 bg-light">The user has login access
            <a href="/common/user/admin/update-login-access/{{  $user->id }}/remove"><button type="button" class="tab btn-warning form-control">Remove login access</button></a> </p>
    @else
        <p class="p-2 bg-light">User dont have login access
           <a href="/common/user/admin/update-login-access/{{  $user->id }}/allow"><button  type="button" class="tab btn-success form-control">Allow login access</button> </a> </p>
    @endif
@endif
