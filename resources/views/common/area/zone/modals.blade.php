<div class="modal fade"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addForm" class="modal-content" action="{{ route('common.area.zone.create') }}" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create Zone</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="add_result"></div>
                @include('common.area.zone.form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Data</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editForm" class="modal-content" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Zone</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="edit_result"></div>
                @include('common.area.zone.form')
                <input type="hidden" name="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>



<div class="modal fade" id="addCityModal" tabindex="-1" role="dialog" aria-labelledby="addCityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addCityForm" class="modal-content" method="post" action="{{ route('common.area.zone.addCity',) }}" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addCityModalLabel">Add cities into zone</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="addCity_result"></div>
                @include('common.area.zone.add-city-form')
                <input type="hidden" name="zone_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="CityModal" tabindex="-1" role="dialog" aria-labelledby="CityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="zoneCity" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CityModalLabel">Zone cities</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body citylist">  </div>
        </div>
    </div>
</div>
