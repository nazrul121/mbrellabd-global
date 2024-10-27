
<div class="row">
    <div class="container" method="get">
        <form class="container-fluid" id="preVariationForm" action="{{ route('common.save-product-variation-option',$product->id); }}"> @csrf
            <div class="row">
                <div class="col md-9 text-left">
                    <div class="row">
                    @foreach ($variations as $key1=>$v)
                        @if($v->variation_options()->count() >0)
                        <div class="col-md-3 col-sm-4 col-xs-6">
                            <select name="{{ $v->origin }}" class="form-control mt-2">
                                <option value="">Choose {{ $v->title }}</option>
                            @foreach ($v->variation_options()->get() as $key=>$vo)
                            <option value="{{ $vo->id }}">{{ $vo->title }}</option>
                                {{-- <label for="variant{{ $v->title.$vo->title }}">
                                    <input type="radio" class="variation" data-variation_id="{{ $v->id }}" data-option_id="{{ $vo->id }}" name="{{ $v->title.$v->id }}[]" id="variant{{ $v->title.$vo->title }}"> {{ $vo->title }}
                                </label> --}}
                                {{-- <button class="btnVariant" data-variation_id="{{ $v->id }}" data-option_id="{{ $vo->id }}" type="button">{{ $vo->title }}</button> --}}
                            @endforeach
                            </select>
                        </div>
                        @endif
                    @endforeach
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    @if(check_access('create-product-variation'))
                        <button class="btn btn-secondary resetVariation" type="button">Reset variation</button>
                        <button class="btn btn-primary generateVariation" type="submit">Generate records</button>
                    @else <span class="text-danger">You have no access to create product variations</span>
                    @endif
                </div>
            </div>
        </form>
        <div class="row showProductVariationOptions"></div>
    </div>

    <div class="container  mt-5">
        <div class="table-responsive">
            <table class="table table-hover bg-white varientTable" style="width:100%">
                <thead>
                    <tr><th>ID</th> <th>Variants</th> <th>Qty</th> <th>Barcode</th> <th>Actions</th></tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- edit modal  --}}
<div class="modal fade" id="editVariationModal" tabindex="-1" role="dialog" aria-labelledby="colorLable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit product variation</h5>
                <button type="button" class="close-VEmodal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body productVariationFields"> </div>

        </div>
    </div>
</div>

<style> .btnVariant{ border:2px solid silver;} </style>
<script>
    $(function(){
        $('.resetVariation').on('click',function(){
            $("option:selected").prop("selected", false);
            $('.showProductVariationOptions').html('');
        });

        var frm = $('#preVariationForm');
        frm.submit(function (e) {
            let x = e;  e.preventDefault();
            $.ajax({
                type: frm.attr('method'),  url: frm.attr('action'), data: frm.serialize(),
                success: function (data) {
                    $("[type='submit']").prop('disabled',false);
                    $('.showProductVariationOptions').html(data);
                },
                error: function (data) {  console.log(data) },
            });

        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $(function () {  table.ajax.reload() });

        let table = $('.varientTable').DataTable({
            processing: true,serverSide: true,
            "bFilter": false, "lengthChange": false,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{ route('common.product-variants',$product->id) }}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'varient_info', orderable: false, searchable: false},
                {data: 'qty'},{data: 'barcode'},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.varientTable').on('click', '.edit' ,function(e){
            let id = $(this).attr('id'); $('.productVariationFields').html('Loading...')
            $('#editVariationModal').modal('show');
            $.get( url+"/common/catalog/product/edit-product-variation/"+id , function( data ) {
                $('.productVariationFields').html(data);
            });
            $('body').addClass('modal-open')
        });

        $('.varientTable').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record? -- All the relavent products will be permanently removed after the action executed')){
                let id = $(this).attr('id');
                
                // $.get( url+"/common/catalog/product/product-variant-delete/"+id, function(data){
                //     if(data.error) alert(data.error);
                // }).always(function(data) {
                //     console.log(data)
                //     $('.varientTable').DataTable().ajax.reload();
                // });
                // window.open(url+"/common/catalog/product/product-variant-delete/"+id);
                $.ajax({
                    url: url+"/common/catalog/product/product-variant-delete/"+id,
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.varientTable').DataTable().ajax.reload();
                    }
                });
                $('body').addClass('modal-open')
            }
        });

        $('.varientTable').on('click', '.editQty' ,function(e){

            var id = $(this).attr('id');
            var qty = $('.vQty'+id).val();

            // alert(id+' = '+qty); return false;
            $.get( url+"/common/catalog/product/update-combination-qty/"+id+'/'+qty , function( data ) {
                alert(data.success);
            });
        });


        $('.close-VEmodal').on('click',function(){
            $('#editVariationModal').modal('hide');
        })

    });

</script>
