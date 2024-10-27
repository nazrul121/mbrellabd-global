
<div class="row">
    <form class="container" id="addLighlightItem" action="{{ route('common.highlight.add-product',$highlight->id) }}">@csrf
        <div class="form-group row">
            <label class="col-sm-3 col-form-label text-right">Search Product</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="title" id="title">
                <input type="hidden" name="product_id">
            </div>
            <div class="col-sm-2">
                <button type="submit" disabled class="btn btn-primary"><span class="feather icon-plus"></span></button>
            </div>
        </div>
        <div id="product_list" class="col-sm-8 offset-2 pl-5" ></div>
    </form>

    <div class="container-fluid mt-3">
        <div class="table-responsive">
            <table class="table table-hover bg-white productTable" style="width:100%">
                <thead>
                    <tr><th>ID</th> <th>Image</th> <th>Product title</th> <th>Actions</th></tr>
                </thead>
            </table>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(function () { table.ajax.reload(); });

        let table = $('.productTable').DataTable({
            processing: true,serverSide: true,
            "bFilter": false, "lengthChange": false,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{ route('common.highlight.add-product',$highlight->id) }}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'title', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });


        $('#title').on('keyup',function () {
            var query = $(this).val();
            if(query.length >1){
                $.ajax({
                    url:"{{ route('common.highlight.searach-products',$highlight->id) }}",
                    type:'GET', data:{'name':query},
                    success:function (data) { $('#product_list').html(data);}
                })
            }else $('#product_list').html('');

        });

        $(document).on('click', 'li', function(){
            var value = $(this).text();
            var id = $(this).data('id');
            $('[name=product_id]').val(id);
            $('#title').val(value);
            $('#product_list').html("");
            $("[type='submit']").prop('disabled',false);
        });

        //submit highlight_product form after product selected form select tag
        var frm = $('#addLighlightItem');
        frm.submit(function (e) {
            e.preventDefault();
            $.ajax({
                type: frm.attr('method'),  url: frm.attr('action'), data: frm.serialize(),
                success: function (data) {
                    if(data.success){ $('.productTable').DataTable().ajax.reload();}
                    if(data.error){ alert(data.error);  }
                    $('#title').val('');
                },
                error: function (data) {  alert(data) },
            });
        });

        $('.productTable').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url: url+"/common/catalog/product/highlight/product/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.productTable').DataTable().ajax.reload();
                    }
                });
            }
        });

    });
</script>
