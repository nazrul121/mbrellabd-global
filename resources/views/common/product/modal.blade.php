{{-- show/add/remove product variants  --}}
<div class="modal fade" id="VarientModal" tabindex="-1" role="dialog" aria-labelledby="varientLable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 70%">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Varients of <b id="varientLable"></b></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body variantArea"> </div>

        </div>
    </div>
</div>

<div class="modal fade" id="colorModal" tabindex="-1" role="dialog" aria-labelledby="colorLable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Colors and photos <b id="colorLable"></b></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body colorResult"> </div>

        </div>
    </div>
</div>


{{-- meta modal  --}}
<div class="modal fade" id="metaModal" tabindex="-1" role="dialog" aria-labelledby="metaLable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product meta information <b id="colorLable"></b></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body metaResult"> </div>

        </div>
    </div>
</div>

{{-- quick edit modal  --}}
<div class="modal fade" id="quickEditModal" tabindex="-1" role="dialog" aria-labelledby="metaLable" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick edit <b id="colorLable"></b></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="quick_result"></div>

                <form id="quickEditForm" action="" method="POST" class="needs-validation" >@csrf
                    <input type="hidden" name="id" id="id">
                   
                    @foreach (get_currency() as $item)
                        <div class="form-check mb-2">
                            <input class="form-check-input newCountries" name="langs[]" type="checkbox" value="{{ $item->id }}" id="c{{ $item->short_name }}"> 
                            <img src="{{ url($item->flag) }}" style="height:12px; margin:5px;">
                            <label class="form-check-label" for="c{{ $item->short_name }}"> {{ $item->name }} </label>
                        </div>
                    @endforeach
                  
                   
                    <div class="mb-3 col-md-12">
                        <div class="row">
                            <button type="submit" class="btn btn-primary float-md-right submitQuickEdit"><i class="fas fa-edit"></i> Submit edit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
