@extends('common.layouts')

@section('title','Area and locations')

@section('content')
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Areas DataTable</h5>
                    <div class="card-header-right">
                        @if(check_access('create-area'))
                        <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button> @endif

                        <select name="group" style="padding: 10px;" class="border border-info rounded">
                            <option value="">Choose country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" @if(request()->get('country')==$country->id)selected @endif >{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover bg-white" style="width:100%">
                            <thead>
                                <tr> <th>#</th>
                                    <th class="text-left uppercase">Division</th>
                                    <th class="text-left uppercase">Districts</th>
                                    <th class="text-left uppercase">City</th>
                                    <th class="text-right uppercase">Modify</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('common.area.area.modals')

@endsection


@push('scripts')
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $('[name=group]').on('change', function(){
            var status = $(this).val();
            var url = new URL(window.location.href);
            url.searchParams.set('country',status);
            window.location.href = url.href;
        })


        let table = $('.table').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.area', ['country'=>request()->country])}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable:false,searchable:false},
                {data: 'name'}, {data: 'district', orderable: false, searchable: false},
                {data: 'city', orderable: false, searchable: false},
                 {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        // add division with div id
        $('.addModal').on('click',function(){
            $('#addDivision').modal('show');
        })


        // show districts with div id
        $('.table').on('click','.getDistricts',function(){
            let id = $(this).data('id');
            $('#districtModal').modal('show')
            let name = $(this).data('name');
            $('.disName').text(name);
            $.get( url+"/common/area/districts/"+id,function(data){$('.showDistricts').html(data);})

        })

        // add district with div id
        $('.table').on('click','.addDis',function(){
            let id = $(this).data('id');
            let name = $(this).data('name'); 
            $('.disName').text(name);
            $('[name=division_id]').val(id);
            $('#addDistrict').modal('show');
        })


        //show cities
        $('.table').on('click', '.cityList',function(){
            let id = $(this).data('id');
            $('#cityModal').modal('show');
            let name = $(this).data('name'); $('.disName').text(name);
            // $.get( ""+id,function(data){$('.showCities').html(data);})
            $.get( url+"/common/area/district/city/"+id,function(data){$('.showCities').html(data);})
        })

        //add city
        $('.table').on('click', '.addCity',function(){
            $("#CityFormAdd")[0].reset();
            let id = $(this).data('id'); $('.city_result').html('')
            $('#addCity').modal('show');
            $('[name=division_id]').val(id)
            let name = $(this).data('name'); $('.disName').text(name);
            $('[name=district_id]').html('');
            $('[name=district_id]').append('<option value="">Select District</option>');
            $.get( url+"/get-districts/"+id,  function( data ) {
                $.each( data, function( key, value ) {
                    $('[name=district_id]').append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            });
        })


        
        $("#divisionForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.div_result').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false, dataType:"json",
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div style="width:100%" class="alert alert-warning alert-dismissible" role="alert"> <div class="alert-message"> <strong>Warning!</strong> ';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div style="width:100%" class="alert alert-success alert-dismissible" role="alert"> <div class="alert-message"> <strong>Success!</strong> ' + data.success +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button> </div>';
                        $('.table').DataTable().ajax.reload();
                        setTimeout(function() { $('#divisionForm').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Save Division');
                    $("[type='submit']").prop('disabled',false);
                    $('.div_result').html(html);
                }
            });
        });

        $("#districtForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.form_result').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false, dataType:"json",
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div style="width:100%" class="alert alert-warning alert-dismissible" role="alert"> <div class="alert-message"> <strong>Warning!</strong> ';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div style="width:100%" class="alert alert-success alert-dismissible" role="alert"> <div class="alert-message"> <strong>Success!</strong> ' + data.success +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button> </div>';
                        $('.table').DataTable().ajax.reload();
                        setTimeout(function() { $('#addDistrict').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Save District');
                    $("[type='submit']").prop('disabled',false);
                    $('.dis_result').html(html);
                }
            });
        });

        $("#CityFormAdd").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.form_result').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false, dataType:"json",
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div style="width:100%" class="alert alert-warning alert-dismissible" role="alert"> <div class="alert-message"> <strong>Warning!</strong> ';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div style="width:100%" class="alert alert-success alert-dismissible" role="alert"> <div class="alert-message"> <strong>Success!</strong> ' + data.success +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button> </div>';
                        $('.table').DataTable().ajax.reload();
                        setTimeout(function() { $('#addCity').modal('hide');}, 1000);

                    }
                    $("[type='submit']").text('Save City');
                    $("[type='submit']").prop('disabled',false);
                    $('.city_result').html(html);
                }
            });
        });


        $('.table').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record??')){
                let id = $(this).attr('id')
                $.get(url+"/common/area/delete-division/"+id, function(data, status){
                    if(data.warning) alert(data.warning);
                    else $('.table').DataTable().ajax.reload();
                });
            }
        });

    });
</script>

@endpush
