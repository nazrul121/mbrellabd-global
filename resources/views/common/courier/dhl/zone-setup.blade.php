
@extends('common.layouts')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"> <h5>DHL - Weight wize Price list</h5>  
                <div class="card-header-right">
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button> 
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <p class="alert p-3 text-center text-success bg-light border-2 border-success col-6"><i class="fas fa-check"></i> {{ session()->get('success') }}</p>
                @endif
                @if (session('errors'))
                    <p class="alert p-3 text-center text-danger bg-light border-2 border-danger col-6"><i class="fas fa-times"></i> {{ $errors->first() }}</p>
                @endif
                <table class="table table-hover bg-white">
                    <tr>
                        <th>Kg</th> 
                        @foreach ($dhl_zones as $zone)
                            @php
                                $zoneCountries = DB::table('countries')->where('zone',$zone->zone)->get();
                            @endphp
                            <th>Zone <b>{{ $zone->zone }}</b>
                                @foreach ($zoneCountries as $country)
                                    <b class="badge badge-info">{{ $country->short_name }}</b> 
                                @endforeach
                            </th>
                        @endforeach 
                        <th class="text-right">Action</th>
                    </tr>
                    <tbody>
                        @foreach ($kGs as $kg)
                            <tr>
                                <td>{{ number_format($kg->kg_from,1) }}</td>
                                @foreach ($dhl_zones as $zone)
                                    @php
                                        $zonePrice = DB::table('dhl_zone_prices')->where(['zone'=>$zone->zone,'kg_from'=>$kg->kg_from])->pluck('price')->first();
                                    @endphp
                                    <td>{{ $zonePrice }}</td>
                                @endforeach
                                <td class="text-right">
                                    <button class="btn-info btn-sm edit" id="{{ $kg->kg_from }}"> <i class="fa fa-edit text-white"></i></button>
                                    <button class="btn-danger btn-sm delete" id="{{ $kg->kg_from }}"> <i class="fa fa-trash text-white"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>
@include('common.courier.dhl.modal')
@endsection


@push('scripts')
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); 
        })


        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let kg = $(this).attr('id');

            $.ajax({
                url: url+"/common/courier/single-dhl-zone/"+kg,
                type: 'get', dataType: 'json',
                success: function (data) {
                    console.log(data);
                    $('[name=weight_from]').val( parseFloat(data.kg_from).toFixed(2));
                    $('[name=weight_to]').val( parseFloat(data.kg_to).toFixed(2));

                    $.each( data, function( index, value ){
                        $('.price'+value.zone).val(parseFloat(value.price).toFixed(2));
                    });
                   
                    $('#editForm').attr('action', url+'/common/courier/update-dhl-zone/'+kg);
                }
            });
        })



        $('.table').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id');
                $.ajax({
                    url: url+"/common/courier/delete-dhl-zone/"+id,
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) location.reload();
                    }
                });
            }
        });



    });
</script>

@endpush
