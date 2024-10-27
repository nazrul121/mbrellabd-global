
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5><b>Order Status</b>  Setup</h5>
                <div class="card-header-right">
                    <!-- <input type="checkbox" id="ShowSortBy">
                    <label for="ShowSortBy">Show with <b>Sort By</b></label> -->
                    <div class="form-group d-inline">
                        <div class="checkbox checkbox-info d-inline">
                            <input type="checkbox" id="ShowSortBy" @if(request()->get('sortBy'))checked @endif >
                            <label for="ShowSortBy" class="cr">Show with <b>Sort By</b></label>
                        </div>
                    </div> &nbsp; 
                    
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button>
                </div>
            </div>

            <div class="card-body">
                <div class="result"></div>

                @if(Session::has('alert'))
                    <div class="alert @if(Session::get('action')=='alert') btn-warning @else alert-success @endif alert-dismissible fade show" role="alert">
                        <strong>{{ Session::get('action') }} : </strong> {{ Session::get('alert') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                @endif

                <div class="responsive-table">
                    <table class="table table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr><th>#</th> <th>Status</th> <th>Next Action</th> <th>Qty-status</th> <th>Relational Activity</th> <th class="text-right">Action</th></tr>
                        </thead>
                        <tbody class="row_">
                            @foreach ($order_status as $key=>$status)
                            <tr id="{{$status->id}}">
                                <td>{{ $key+1 }}</td>
                                <td>{{ $status->title }}</td>
                                <td>
                                    @if($status->action=='continue')
                                        <span class="text-info">{{ $status->action }}</span>
                                    @else <span class="text-warning">{{ $status->action }}</span> @endif
                                </td>
                                <td>
                                    @if($status->qty_status=='general')
                                        <span class="text-info">{{ $status->qty_status }}</span>
                                    @else <span class="text-warning">{{ $status->qty_status }}</span> @endif
                                </td>
                                <td>
                                    @if($status->relational_activity=='')
                                        <span class="text-waring">No action</span>
                                    @else <span class="text-primary">{{ $status->relational_activity }}</span> @endif
                                </td>
                                <td class="text-right">
                                    @if($status->description !=null)
                                        <button class="btn btn-primary btn-sm details" data-description="{{ $status->description }}" title="Status Note"><span class="fa fa-info"></span> </button>
                                    @endif
                                    <button class="btn btn-info btn-sm edit" data-id="{{ $status->id }}" title="Edit status"><span class="fa fa-edit"></span> </button>
                                    <button class="btn btn-danger btn-sm delete" data-id="{{ $status->id }}" title="Delete status"><span class="fa fa-trash"></span> </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('common.order.status.modal')

@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

    @if(Session::has('action') && Session::get('action')=='success')
        <script> $(function(){ $('#editModal').modal('hide');}) </script>
    @endif

    @if(Session::has('action') && Session::get('action')=='alert')
        <script>
            $(function(){
                $('#editModal').modal('show');
                let id = $('#id').val();
                if(id==''){
                    $('#editForm').attr('action',url+'/common/settings/order-status/create');
                }else $('#editForm').attr('action',url+'/common/settings/order-status/update/'+id);
            })
        </script>
    @endif

    <script>
        $(function(){
            $('.details').on('click',function(){
                let descrition = $(this).data('description');
                $('#orderModal').modal('show');
                $('.modalData').html(descrition);
            })

            $('.edit').on('click',function(){
                let id = $(this).data('id');  $('#editModal').modal('show');
                $('.modal-title').text("Update Status info");
                $("[type='submit']").html('Update status');
                $.ajax({
                    url: url+"/common/settings/order-status/single-item/"+id,
                    type: 'get', dataType: 'json',
                    success: function (data) {
                        $('[name=title]').val(data.title);$('#id').val(data.id);
                        $('[name=description]').val(data.description);
                        $('#editForm').attr('action',url+'/common/settings/order-status/update/'+id);

                        $('[name=action] option[value="'+data.action+'"]').prop('selected', true);
                        $('[name=qty_status] option[value="'+data.qty_status+'"]').prop('selected', true);
                        $('[name=relational_activity] option[value="'+data.relational_activity+'"]').prop('selected', true);
                    }
                });
            })

            $('.addModal').on('click',function(){
                $('#editModal').modal('show'); $('#editForm').trigger("reset");
                $('.modal-title').text("Create new Status");
                $('#editForm').attr('action',url+'/common/settings/order-status/create');
                $("[type='submit']").html('Save new status');
            })

            $('.delete').on('click',function(){
                if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                    let id = $(this).data('id');
                    window.location.replace('/common/settings/order-status/delete/'+id);
                }

            })

            
            $( ".row_" ).sortable({
                placeholder : "ui-state-highlight",
                update  : function(event, ui){
                    var page_id_array = new Array();
                    $('.row_ tr').each(function(){ page_id_array.push($(this).attr("id")); });

                    $.ajax({
                        url:"{{route('common.order-status-sorting')}}",method:"get",
                        data:{page_id_array:page_id_array},
                        success:function(data) { $('.result').html(data);}
                    });
                }
            });

            $('#ShowSortBy').on('change', function(){
                var isChecked = $(this).prop('checked');
                
                if (isChecked) {
                    window.location.href = url+'/common/settings/order-status?sortBy=1';
                }else window.location.href = url+'/common/settings/order-status?sortBy=0';
            })
        })
    </script>
@endpush
