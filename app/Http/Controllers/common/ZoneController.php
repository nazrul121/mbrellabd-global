<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\City_zone;
use App\Models\District;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ZoneController extends Controller
{
    public function index(Request $request){
        if($request->draw){
            return datatables()::of(Zone::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($div) {
                $data = '';
                if(check_access('edit-zone')){
                    $data .="<a href='javaScript:;' class='btn btn-sm btn-secondary edit' data-id='".$div->id."'><span class='fas fa-edit'></span></a> ";
                }
                if(check_access('delete-zone')){
                    $data .="<a href='javaScript:;' class='btn btn-sm btn-danger delete' id='".$div->id."' ><span class='fas fa-trash-alt'></span></a> ";
                }
                if(check_access('add-city-into-zone')){
                    $data .="<a href='javaScript:;' class='btn btn-sm btn-success addCity' data-title='".$div->name."' id='".$div->id."' ><span class='fas fa-plus'></span></a>";
                }

                return $data;
            })
            ->addColumn('city', function($zone){
                return $zone->cities()->count().' <button data-id="'.$zone->id.'" class="viewCities">Cities</button>';
            })
            ->addColumn('orders', function($zone){
                return $zone->orders()->count().' <a href="#">Orders</a>';;
            })
            ->editColumn('status', function($zone){
                if($zone->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })
            ->rawColumns(['city','orders','status','modify'])->make(true);
        }
        return view('common.area.zone.list');
    }

    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $data = [ 'name'=>$request->name,'duration'=>$request->duration, 'delivery_cost'=>$request->delivery_cost, 'description'=>$request->description, 'status'=>$request->status];
        Zone::create($data);
        return response()->json(['success' => 'Zone has been created successfully!']);
    }

    public function show(Zone $Zone){ return Zone::find($Zone->id);}


    public function update(Request $request,Zone $zone){
        $validator = $this->fields($zone->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'name'=>$request->name,'duration'=>$request->duration, 'delivery_cost'=>$request->delivery_cost, 'description'=>$request->description, 'status'=>$request->status];
        $zone->update($data);
        $zone->update($data);
        return response()->json(['success' => 'The Zone hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'name'=>'required|unique:zones,name,'.$id,
            'duration'=>'required', 'description'=>'required',
            'delivery_cost'=>'required', 'status'=>'required',
        ]); return $validator;
    }


    public function destroy(Zone $zone){
        try {
            $zone->delete();
            return response()->json(['success' => 'Zone hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function add_city(Request $request){
        // dd($request->all());
        $new = $exist = 0;
        foreach($request->cities as $city){
            $data = ['zone_id'=>$request->zone_id,'city_id'=>$city];
            $count = City_zone::where($data)->count();
            if($count < 1){
                City_zone::create($data);
                $new ++;
            }else $exist ++;
        }
        if($exist > 0){ return response()->json(['success' => $exist.' cities are exist before']); }
        return response()->json(['success' => $new.' cities are added into the zone']);
    }

    function zone_city(Zone $zone, Request $request){
        // dd($zone->cities);
        if($request->draw){
            return datatables()::of($zone->cities)
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $delete ="<a href='javaScript:;' class='btn btn-sm btn-danger delete' data-id='".$row->id."'  ><span class='fas fa-trash-alt'></span></a> ";
                return $delete;
            })
            ->addColumn('city', function($city){
                // return $city;
                return $city->name.' - <span class="text-info">'.$city->district->name.'</span>';
            })

            ->rawColumns(['city','modify'])->make(true);
        }
        return view('common.area.zone.zone-cities',compact('zone'));
    }

    function cities($zone,$city){
        // dd( DB::table('city_zone')->where(['city_id'=>$city,'zone_id'=>$zone])->count() );
        return DB::table('city_zone')->where(['city_id'=>$city,'zone_id'=>$zone])->count();
    }

    function delete_city_zone( $id){
        try {
            City_zone::where('city_id',$id)->delete();
            return response()->json(['success' => 'The city hasn been deleted form zone list!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
