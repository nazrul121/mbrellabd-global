<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Division;
use App\Models\Country;

use Illuminate\Http\Request;
use Validator;

class AreaController extends Controller
{
    public function index(Request $request){

        if($request->country) $country = Country::where('id',$request->country)->first();
        else $country = Country::where('id',2)->first();

        if($request->draw){
            
            return datatables()::of(Division::where('country_id',$country->id))
            ->addIndexColumn()
            ->editColumn('modify', function ($div) {
                $data = '';
                if(check_access('delete-division')){
                    $data .="<a href='javaScript:;' class='btn btn-sm btn-danger delete' id='".$div->id."' ><span class='fas fa-trash-alt'></span></a>";
                }
                if(check_access('edit-division')){
                    $data .="<a href='javaScript:;' class='btn btn-sm btn-secondary edit' data-id='".$div->id."'><span class='fas fa-pen-fancy'></span></a> ";
                }
                return $data;
            })
            ->addColumn('district', function($div){
                $data = '';
                if(check_access('create-district')){
                    $data .= "<a href='javaScript:;' class='btn-sm text-primary addDis' data-name='".$div->name."' data-id='".$div->id."'><span class='fas fa-plus'> add</span></a> ";
                }

                return $div->districts()->count().'<button class="btn btn-sm getDistricts" data-name="'.$div->name.'" data-id="'.$div->id.'">Districts</button> '.$data;
            })
            ->addColumn('city', function($div){
                $cities = [];
                
                foreach($div->districts()->get() as $dis){
                    $cities[] =  $dis->cities()->count();
                }
                $data = '';
                if(check_access('create-city')){
                    $data .="<a href='javaScript:;' class='btn-sm text-primary addCity' data-name='".$div->name."' data-id='".$div->id."'><span class='fas fa-plus'> add</span></a>";
                }
               $data .= array_sum($cities) .'<button class="btn btn-sm cityList" data-name="'.$div->name.'" data-name="'.$div->name.'" data-id="'.$div->id.'">Cities</button>';
               return $data;
            })
            ->rawColumns(['district','city','modify'])->make(true);
        }
        $countries = Country::where('status','1')->get();
        return view('common.area.area.list', compact('countries','country'));
    }



    function districts(Request $request, Division $division){
        if($request->draw){
            return datatables()::of(District::where('division_id',$division->id)->get())
            ->addIndexColumn()
            ->editColumn('modify', function ($dis) {
                $data = '';
                if(check_access('edit-district')){
                    $data .= '<a href="#" class="btn btn-sm btn-info editDistrict" data-id="'.$dis->id.'"><span class="fa fa-edit"></span></a> ';
                }
                if(check_access('delete-district')){
                    $data .= '<a href="#" class="btn btn-sm btn-danger deleteDistrict" data-id="'.$dis->id.'"><span class="fa fa-trash"></span></a> ';
                }
                return $data;
            })
            ->rawColumns(['modify'])->make(true);
        }
      return view('common.area.area.districts',compact('division'));
    }

    function cities(Request $request, Division $division){
        return view('common.area.area.cities',compact('division'));
    }




    public function save_division(Request $request, Country $country){

        $validator = $this->divisionFields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        Division::create(['country_id'=>$request->country, 'name'=>$request->name, 'url'=>$request->url]);
        return response()->json(['success' => 'District hasn been created successfully!']);
    }







    public function save_district(Request $request){
        // dd($request->all());
        $validator = $this->districtFields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        District::create(['division_id'=>$request->division_id, 'name'=>$request->name, 'url'=>$request->url, 'delivery_cost'=>$request->delivery_cost]);
        return response()->json(['success' => 'District hasn been created successfully!']);
    }

    public function update_district(Request $request){
        // dd($request->all());
        $validator = $this->districtFields($request->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        District::where('id',$request->id)->update(['division_id'=>$request->division_id, 'name'=>$request->name, 'url'=>$request->url, 'delivery_cost'=>$request->delivery_cost]);
        District::find($request->id);
        return response()->json(['success' => 'District hasn been updated successfully!']);
    }

    function single_district(District $district){
        $div = Division::where('id',$district->division_id)->first()->name;
        $district->divisionName = $div; return $district;
    }

    function delete_district(District $district){
        try {
            return $district->delete();
        } catch ( \Exception $e) {
            return response()->json(['warning' => 'This district cannot be deleted! It is maybe the cause of Foreign key constraint!! ']);
        }
    }

    function delete_division(Division $division){
        try {
            return $division->delete();
        } catch ( \Exception $e) {
            return response()->json(['warning' => 'This division cannot be deleted! It is maybe the cause of Foreign key constraint!! ']);
        }
    }



    public function save_city(Request $request){
        // dd($request->all());
        $validator = $this->cityFields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $checkCity = City::where(['district_id'=>$request->district_id, 'name'=>$request->name]);
        if($checkCity->count() >0){
            return response()->json(['errors' =>['The city <b>'. $request->name .'</b> already exist']]);
        }

        City::create(['district_id'=>$request->district_id, 'name'=>$request->name, 'url'=>$request->url]);
        return response()->json(['success' => 'The city hasn been created successfully!']);
    }

    public function update_city(Request $request){
        // dd($request->all());
        $validator = $this->cityFields($request->id);
        if ($validator->fails()) {
            echo '<span class="text-danger">form-validation error</span>'; exit;
        }
        $checkCity = City::where(['district_id'=>$request->district_id, 'name'=>$request->name]);
        if($checkCity->count() >1){
            echo '<span class="text-danger">The city <b>'. $request->name .'</b> More then <b>1 name</b> exist</span>'; exit;
        }

        City::where('id',$request->id)->update(['district_id'=>$request->district_id, 'name'=>$request->name, 'url'=>$request->url]);
        City::where('id',$request->id)->first();
        echo '<span class="text-success">Data updated successfully</span>';
    }

    function delete_city(City $city){
        try {
            return $city->delete();
        } catch ( \Exception $e) {
            return 0;
        }
    }


    private function divisionFields($id=null){
        $validator = Validator::make(request()->all(), [
            'country'=>'required',
            'name'=>'required|unique:districts,name,'.$id,

        ]); return $validator;
    }
    private function districtFields($id=null){
        $validator = Validator::make(request()->all(), [
            'division_id'=>'required',
            'name'=>'required|unique:districts,name,'.$id,
            'delivery_cost'=>'required',
            'url'=>'sometimes|nullable',

        ]); return $validator;
    }
    private function cityFields($id=null){
        $validator = Validator::make(request()->all(), [
            'district_id'=>'required',
            'name'=>'required'
        ]); return $validator;
    }



}
