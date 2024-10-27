
@extends('common.layouts')

@section('content')

@php
    $groups = \App\Models\Group::orderBy('sort_by')->where('status','1')->get();
    $sub_groups = \App\Models\Inner_group::orderBy('sort_by')->where('status','1')->get();
    $child_groups = \App\Models\Child_group::orderBy('sort_by')->where('status','1')->get();
@endphp
<div class="result">Result</div>
<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-header"><h5>Group Ordering</h5> </div>

            <div class="card-body">
                <div class="table-responsive" style="height:100vh;">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr> <th>Category Name</th> </tr>
                        </thead>
                        <tbody class="row_">
                            @foreach ($groups as $group)
                            <tr id="{{$group->id}}">
                                <td><img src="{{ url('storage/'.$group->photo) }}" style="height:50px"></td>
                                <td>{{ $group->title }} [<b class="text-info">{{ $group->group_products()->count() }}</b> items]</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-header"><h5>Sub-Group Ordering</h5> </div>

            <div class="card-body">
                <div class="table-responsive" style="height:100vh;">
                    <table class="table table-hover bg-white">
                        <thead>
                            <tr> <th>Category Name</th> </tr>
                        </thead>
                        <tbody class="sub_row">
                            @foreach ($sub_groups as $group)
                            <tr id="{{$group->id}}">
                                <td><img src="{{ url('storage/'.$group->photo) }}" style="height:50px"></td>
                                <td>{{ $group->title }} [{{ $group->group->title }}] - [<b class="text-info">{{ $group->products()->count() }}</b> items]</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-header"><h5>Child-Group Ordering</h5> </div>

            <div class="card-body">
                <div class="table-responsive" style="height:100vh;">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr>  <th>Image</th> <th>Category Name</th> </tr>
                        </thead>
                        <tbody class="child_row">
                            @foreach ($child_groups as $group)
                            <tr id="{{$group->id}}">
                                <td><img src="{{ url('storage/'.$group->photo) }}" style="height:50px"></td>
                                <td>{{ $group->title }} [{{ $group->inner_group->group->title}}  <i class="fa fa-long-arrow-right text-warning"></i> {{ $group->inner_group->title }}]
                                    - [<b class="text-info">{{ $group->products()->count() }}</b> items]</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
    <style>
        tbody tr{ cursor: pointer;}
    </style>
@endpush
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function(){
            $( ".child_row" ).sortable({
                placeholder : "ui-state-highlight",
                update  : function(event, ui){
                    var page_id_array = new Array();
                    $('.child_row tr').each(function(){ page_id_array.push($(this).attr("id")); });

                    $.ajax({
                        url:"{{route('common.child-group-ordering')}}",method:"get",
                        data:{page_id_array:page_id_array},
                        success:function(data) { $('.result').html(data);}
                    });
                }
            });

            $( ".sub_row" ).sortable({
                placeholder : "ui-state-highlight",
                update  : function(event, ui){
                    var page_id_array = new Array();
                    $('.sub_row tr').each(function(){ page_id_array.push($(this).attr("id")); });

                    $.ajax({
                        url:"{{route('common.sub-group-ordering')}}",method:"get",
                        data:{page_id_array:page_id_array},
                        success:function(data) { $('.result').html(data);}
                    });
                }
            });

            $( ".row_" ).sortable({
                placeholder : "ui-state-highlight",
                update  : function(event, ui){
                    var page_id_array = new Array();
                    $('.row_ tr').each(function(){ page_id_array.push($(this).attr("id")); });

                    $.ajax({
                        url:"{{route('common.group-ordering')}}",method:"get",
                        data:{page_id_array:page_id_array},
                        success:function(data) { $('.result').html(data);}
                    });
                }
            });

        });
    </script>
@endpush
