@if($user_type->title=='admin' || $user_type->title=='superAdmin')
    <h4 class="text-success text-center"><b class="text-uppercase">{{ $user_type->title }}</b> has every operations permission</h4>
@else

@php
    $permissionType = \DB::table('settings')->where('type','staff-permission-type')->pluck('value')->first();
@endphp

@if($permissionType=='role-base' || $permissionType==null)
    <table class="table table-styling ">
        <thead>
            <tr>
                <th><label for="checkAll"> <input type="checkbox" id="checkAll"/> Check all</label> &nbsp; &nbsp; | &nbsp; &nbsp; Permissions [<code>check the checkbox of permission to allow access</code>] </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $key=>$label)
                <tr>
                    <td>
                        <label class="text-primary">{{ $label->title }}</label> <br>
                        <ul>
                            @foreach ($label->permission_groups()->get() as $key0=>$group)
                            <li class="text-info"> {{ $group->name }} <br>
                                <ul>
                                    @foreach ($group->permissions()->get() as $key1=>$per)
                                    @php
                                        $check = \DB::table('permission_user_type')->where(['user_type_id'=>$user_type->id, 'permission_id'=>$per->id]);
                                        if($check->count()>0) $checked = 'checked'; else $checked = '';
                                    @endphp
                                    <li class="text-secondary">
                                        <input type="checkbox" class="checkbox" id="p{{ $key.$key0.$key1 }}" {{ $checked }} value="{{ $label->id }}|{{ $per->id }}" name="ids[]">
                                        <label for="p{{ $key.$key0.$key1 }}">{{ $per->name }}</label>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <input type="hidden" name="user_type_id" value={{ $user_type->id }}>
@else
    <p class="alert text-center text-white bg-secondary"> The system permission is <b> Staff individual</b>. Please change Permission type as <b>Role base</b> to apply the action</p>
@endif

<style scoped>
    .permission_table td, .table th {
        border-top: 1px solid #eaeaea;
        white-space: nowrap;
        padding: 0px 8px;
    }
</style>
@endif

<script>
    $(function(){
        $(".checkbox").change(function() {
            var numberOfChecked = $('input:checkbox:checked').length;
            if(numberOfChecked >0){
                $('.updatePermission').attr('disabled',false);
            }else{
                $('.updatePermission').attr('disabled',true);
            }
        });

        $("#checkAll").change(function () {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
            $('.updatePermission').attr('disabled',false);
        });
    })
</script>
