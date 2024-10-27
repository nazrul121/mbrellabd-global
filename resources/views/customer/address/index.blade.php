@extends('customer.layouts')

@section('title', 'My addresses')
@section('content')
    <div class="card">
        <div class="card-header">
            <p> My shipping addresses
                <button class="btn float-end addModal quickview-btn btn-primary" > <i class="fa fa-plus"></i> &nbsp; Create new</button>
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-left">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Contact</th> <th>Area</th>
                            <th>Street address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
                <tbody></tbody>
            </div>
        </div>
    </div>
    

    @include('customer.address.modal')

@endsection

@push('scripts')

<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>
<style>
    .modal {
        position: fixed;
        z-index: 1060;
        display: none;
        width: 100vw;
        height: 100vh;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
        background: rgba(16, 16, 16, 0.19);
    }
    .modal-backdrop{
        width: auto;
        position: initial,
        height: auto
    }
    body{ overflow: auto }
</style>
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            // $('.modal').css('top','25%');
            // $('.modal').css('left','-1%');
           
        }else{
            // $('.modal').css('top','-27%');
            // $('.modal').css('left','-47%');
        }


        $(function () { table.ajax.reload(); });
        let table = $('.table').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('customer.address',app()->getLocale())}}",
            order: [ [0, 'desc'] ],
            columns: [

                { data: 'full_name', name: 'full_name' , orderable: false,searchable: false},
                {data: 'phone'},{data: 'area', orderable: false, searchable: false},
                {data: 'address',},
                {data: 'modify', orderable: false, searchable: false, class:'text-end'}
            ]
        });

        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('#editModal').css('display','block');
            $('.edit_result').html('');
     
            let id = $(this).attr('id');
            
            $.ajax({
                url: url+"/{{ app()->getLocale() }}/customer/addresses/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    console.log(data)
                    $('[name=fname]').val(data.fname);$('#id').val(data.id);
                    $('[name=lname]').val(data.lname);
                    $('[name=phone]').val(data.phone);
                    $('[name=email]').val(data.email);
                    $('[name=postCode]').val(data.postCode);
                    $('[name=address]').val(data.address);
                    $('#editForm').attr('action', url+'/{{ app()->getLocale() }}/customer/addresses/update/'+data.id);

                    get_district(data.division_id);
                    get_cities(data.district_id);

                    setTimeout(function() {
                        $('[name=division] option[value="'+data.division_id+'"]').prop('selected', true);
                        $('[name=district] option[value="'+data.district_id+'}"]').prop('selected', true);
                        $('[name=city] option[value="'+data.city_id+'"]').prop('selected', true);
                    }, 100);
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show');
            $('#addForm').trigger("reset");
            $('.add_result').html('');
        })

        $("#addForm").submit(function(event) {
            event.preventDefault();

            $(".modal").animate({scrollTop: $('.modal-header').offset().top}, 200);

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
                        html = '<div class="alert alert-warning" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                            { html += '' + data.errors[count] ;break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                        $('.table').DataTable().ajax.reload();
                        setTimeout(function() {
                            $('#editModal').css('display','none'); $('body').css('overflow','scroll');$(".jquery-modal").removeClass("blocker ");
                        }, 1000);
                    }
                    $("[type='submit']").text('Save Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.add_result').html(html);
                }
            });
        });

        $("#editForm").submit(function(event) {
            event.preventDefault();
            $(".modal").animate({scrollTop: $('.modal-header').offset().top}, 200);
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
                        html = '<div class="alert alert-warning" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                            { html += '' + data.errors[count] ;break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                        $('.table').DataTable().ajax.reload();
                        setTimeout(function() {
                            $('#editModal').css('display','none'); $('body').css('overflow','scroll');$(".jquery-modal").removeClass("blocker ");
                        }, 1000);
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
                    url: url+"/customer/addresses/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.close').on('click', function(){
            $('#addModal').modal('hide');
            $('#editModal').modal('hide');
        })

    });

    function get_district(id){
        $("[name=district]").html('');
        $.ajax({
            url:url+"/get-districts/"+ id, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=district]").append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        });
    }

    function get_cities(id){
        $("[name=city]").html('');
        $.ajax({ url:url+"/get-cities/"+ id, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=city]").append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        });
    }


</script>

@endpush
