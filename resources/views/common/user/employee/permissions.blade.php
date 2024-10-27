@php
    $permissionType = \DB::table('settings')->where('type','staff-permission-type')->pluck('value')->first();
@endphp

<p class="alert alert-info"><b>Name: </b>{{ $staff->first_name.' '.$staff->last_name }} ({{ $staff->staff_type->title }}), <b>Access type:</b>{{ $staff->user->user_type->title }}, <b>Phone: </b> {{ $staff->user->phone }}</p>

@if($permissionType=='staff-individual')
<div class="table-responsive">
    <table class="table table-styling ">
        <thead>
            <tr>
                <th>Permissions [<code>check the checkbox of permission to allow access</code>] <br><br>
                <input type="checkbox" id="checkAll"> 
                <label for="checkAll">Check All</label>
            </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $key=>$label)
                <tr>
                    <td>
                        <label class="text-primary"> {{ $label->title }}</label> <br>
                        <ul>
                            @foreach ($label->permission_groups()->get() as $key0=>$group)
                            <li class="text-info"> {{ $group->name }} <br>
                                <ul>
                                    @foreach ($group->permissions()->get() as $key1=>$per)
                                        @php
                                            $check = \DB::table('permission_user')->where(['user_id'=>$staff->user_id, 'permission_id'=>$per->id]);
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
    <input type="hidden" name="user_id" value="{{ $staff->user_id }}">
</div>
@else
    <p class="alert text-center text-white bg-secondary"> The system permission is <b>Role base</b>. Please change Permission type as <b> Staff individual</b> to apply the individual access</p>
@endif

<script>
    $(function(){
        // $('li label:contains("delete"), li label:contains("Delete")').css('color', 'red');
        $('li label:contains("delete"), li label:contains("Delete")').each(function() {
            var html = $(this).html();
            // Replace occurrences of "delete" or "Delete" with a span that has the class "highlight"
            var highlightedHtml = html.replace(/(delete|Delete)/g, '<span class="text-danger">$1</span>');
            // Update the HTML of the <p> tag with the highlighted text
            $(this).html(highlightedHtml);
        });

        $('#checkAll').on('change', function() {
            // Get the state of the "Check All" checkbox
            var isChecked = $(this).prop('checked');
            
            // Set the state of all other checkboxes to match the "Check All" checkbox
            $('.checkbox').prop('checked', isChecked);
        });

        // Uncheck the "Check All" checkbox if any individual checkbox is unchecked
        $('.checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
            $('#checkAll').prop('checked', false);
            }
        });

        $(".checkbox").change(function() {
            if(this.checked) {
                var id = $(this).val();
                $('.updatePermission').attr('disabled',false);
            }else{
                $('.updatePermission').attr('disabled',true);
            }
        });
    })
</script>
