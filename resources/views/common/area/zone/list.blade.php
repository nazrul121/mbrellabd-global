@extends('common.layouts')
@section('title','Area and locations')
@section('content')
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Zone DataTable</h5>
                    <div class="card-header-right">
                        @if(check_access('create-zone'))
                        <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button> @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover bg-white" style="width:100%">
                            <thead>
                                <tr> <th>#</th>
                                    <th class="text-left uppercase">Zone Name</th>
                                    <th class="text-left uppercase">Delivery Charge</th>
                                    <th class="text-left uppercase">Cities</th>
                                    <th class="text-left uppercase">Orders</th>
                                    <th class="text-left uppercase">Status</th>
                                    <th class="text-right uppercase">Modify</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('common.area.zone.modals')

@endsection


@push('scripts')
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        var countSelected = 0;
        var countDeselected = 0;

        $(function () { table.ajax.reload(); });

        let table = $('.table').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{ route('common.area.zone') }}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'}, {data: 'name'},{data: 'delivery_cost'},
                {data: 'city', orderable: false, searchable: false},
                {data: 'orders', orderable: false, searchable: false},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).data('id');
            $.ajax({
                url: url+"/common/area/zone/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=name]').val(data.name);$('#id').val(data.id);
                    $('[name=delivery_cost]').val(data.delivery_cost);
                    $('[name=duration]').val(data.duration);
                    $('[name=description]').val(data.description);

                    $('#editForm').attr('action',url+'/common/area/zone/update/'+id);
                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src','/storage/images/thumbs_photo.png');
        })

        $("#addForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.add_result').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! <br/> </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                        $('.table').DataTable().ajax.reload();
                        setTimeout(function() { $('#addModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Save Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.add_result').html(html);
                }
            });
        });

        $("#editForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html('Loading...');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success + '</div>';
                        $('.table').DataTable().ajax.reload();
                        setTimeout(function() { $('#editModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Update Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.edit_result').html(html);
                }
            });
        });

        $('.table').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url:url+"/common/area/zone/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.table').on('click','.addCity',function(){
            $('#addCityModal').modal('show'); $('.addCity_result').html('');
            let id = $(this).attr('id');
            let title = $(this).data('title');
            $('#addCityModalLabel').html('Add cities into zone <b>'+title+'</b>')
            $('[name=zone_id]').val(id);
            $('.cities').html('');
            $('[name=district] option:selected').prop('selected', false);
        })

        $('#district').on('change',function(){
            let id = $(this).val();
            $('.cities').html('');
            var zoneID = $('[name=zone_id]').val();
          
            $.get( url+"/get-cities/"+id,  function( data ) {
                $.each( data, function( key, value ) {
                    $.get( url+"/common/cheack-area-cities/"+zoneID+'/'+value.id,  function( data ) {
                        // window.open(url+"/common/cheack-area-cities/"+zoneID+'/'+id);
                        // console.log(data);
                        // console.log(url+"/common/cheack-area-cities/"+zoneID+'/'+value.id);
                        if(data <1){
                            countSelected ++;
                            $('.cities').append('<option value="'+value.id+'">'+value.name+'</option>');
                        }
                        else countDeselected --;

                        $('.zoneCityResult').html('The city count;  <b class="text-primary">Selectable: '+countSelected+'</b> , <b class="text-danger">Previously selected: '+countDeselected+'</b>');
                    });
                });
                countSelected = 0;
                countDeselected = 0;
            });
        })

        $("#addCityForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html('Loading...');$('.addCity_result').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                        $('.table').DataTable().ajax.reload();
                        $('#addCityModal').modal('hide');
                    }
                    if(data.warning){
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Info: </strong> ' + data.success +'</div>';
                    }
                    $("[type='submit']").text('Save Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.addCity_result').html(html);
                }
            });
        });

        $('.table').on('click', '.viewCities' ,function(e){
            let id = $(this).data('id');
            $('#CityModal').modal('show'); $('.citylist').html('');
            $.get( url+"/common/area/zone/zone-cities/"+id,  function( data ) { $('.citylist').html(data); });
        })

    });

</script>


@endpush
