<?php $countPermission= 0;?>
<form method="get">@csrf
    <div class="add_result">Result board</div>
    <div class="table-responsive">
        <table class="table table-styling ">
            <tbody>
                @foreach ($permissions as $key=>$per)
                    @if($per->permission_group_id !=null)
                        <?php $showForm = false; $countPermission=$countPermission+1;?>
                    @else
                    <?php $showForm = true;  ?>
                        <tr>
                            <td><input type="checkbox" id="l{{ $key }}" name="ids[]" value="{{ $per->id }}"></td>
                            <td> <label for="l{{ $key }}">   {{ $per->name }} </label></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>


    <select name="group_id" class="form-control" required>
        <option value="">Select </option>
        @foreach ($permission_label->permission_groups()->get() as $group)
            <option value="{{ $group->id }}">{{ $group->name }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-info float-right mr-0 mt-3">Assign to Group</button>

</form>


@if($showForm==false && $countPermission>0)
    <div class="table-responsive">
        <table class="table table-styling ">
            <tbody>
                @foreach ($permission_label->permission_groups()->get() as $group)
                    <tr>
                        <td>{{ $group->name }}</td>
                        <td>
                            <ul>
                                @foreach ($group->permissions()->get() as $per)
                                <li>{{ $per->name }}  </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
