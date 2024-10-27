
<form class="container-fluid" action="{{ route('common.product-variants.create',$product->id) }}" id="addVarient" method="post"> @csrf
    <h3 class="bg-light p-2 text-center">
        @foreach ($options as $option)
            <span class="badge badge-warning"><b class="text-info">{{ $option->variation->title }}</b>: {{ $option->title }}</span>
            <input type="hidden" name="option_ids[]" value="{{ $option->id }}">
        @endforeach
        @if($options->count() >0)
        <div class="container-fluid mt-4">
            <div class="addVresult"></div>
            <div class="row mt-5">
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend"> <button class="btn bg-light" type="button">Product Quantity</button> </div>
                        <input type="number" class="form-control" placeholder="" name="qty" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend"> <button class="btn bg-light" type="button">Barcode</button></div>
                        <input type="number" class="form-control" placeholder="" name="barcode" value="{{ rand(9,99999999) }}" required>
                    </div>
                </div>
                <div class="col-md-3 float-right"> <button class="btn btn-info" style="submit">Update Product variant</button> </div>
            </div>
        @endif
    </h3>
</form>


<script>
    $(function(){

        //submit varient_product form after product selected form select tag
        var frm = $('#addVarient');
        frm.submit(function (e) {
            let x = e;
            e.preventDefault();
            $.ajax({
                type: frm.attr('method'),  url: frm.attr('action'), data: frm.serialize(),
                success: function (data) {
                    if(data.success){
                        html = '<div class="col-md-12 alert alert-success alert-dismissible fade show" role="alert"><b class="text-info">Success! </b> ' + data.success + '</div>';
                        $('.varientTable').DataTable().ajax.reload();
                        $('.addVresult').html(html);
                    }
                    if(data.error){
                        html = '<div class="col-md-12 alert alert-warning alert-dismissible fade show" role="alert"><b class="text-danger">Warning! </b> ' + data.error + '</div>';
                    }

                    $('[name=barcode]').val( parseInt( $('[name=barcode]').val() ) +1 );

                    $('.addVresult').html(html);
                },
                error: function (data) {  console.log(data) },
            });

        });


    })


</script>
