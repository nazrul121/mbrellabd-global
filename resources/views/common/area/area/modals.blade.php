<!-- add division  -->
<div class="modal" id="addDivision"  data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
    <div class="modal-dialog" role="document">
        <form class="modal-content" id="divisionForm" method="post" action="{{ route('save-division',$country->id) }}"> @csrf
            <div class="modal-header">
                <h4 class="modal_title">New Division/Region in <span class="disName text-info"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="div_result" style="width:100%"></div>
                @include('common.area.area.form.division')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary ml-2">Save Division</button>
            </div>
        </form>
    </div>
</div>




<!-- Show districts with div id -->
<div class="modal" id="districtModal" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
    <div class="modal-dialog  modal-lg max-w-2xl">
        <div class="modal-content full">
            <div class="modal-header">
                <h4 class="modal-title">Divisions/Regions of <span class="disName text-info"></span></h4>
                <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
            </div>
            <div class="modal-body showDistricts"> Working...</div>
        </div>
    </div>
</div>

<!-- add district  -->
<div class="modal" id="addDistrict"  data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
    <div class="modal-dialog" role="document">
        <form class="modal-content" id="districtForm" method="post" action="{{ route('save-district',$country->id) }}"> @csrf
            <div class="modal-header">
                <h4 class="modal_title">New District in <span class="disName text-info"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="dis_result" style="width:100%"></div>
                @include('common.area.area.form.district')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary ml-2">Save District</button>
            </div>
        </form>
    </div>
</div>




<!-- Modal for showing cities -->
<div class="modal" id="cityModal"  data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal_title">Cities of <span class="disName text-info"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body showCities">Working.... </div>
        </div>
    </div>
</div>

<!--  add city -->
<div class="modal" id="addCity"  data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
    <div class="modal-dialog" role="document">
        <form class="modal-content" id="CityFormAdd" method="post" action="{{ route('save-city') }}"> @csrf
            <div class="modal-header">
                <h4 class="modal_title">New City in <span class="disName text-info"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="city_result" style="width:100%"></div>
                @include('common.area.area.form.city')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary ml-2">Save City</button>
            </div>
        </form>
    </div>
</div>
