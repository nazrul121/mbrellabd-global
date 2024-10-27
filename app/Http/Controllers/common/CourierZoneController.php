<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Courier_company;
use App\Models\Courier_zone;
use Illuminate\Http\Request;
use Validator;

class CourierZoneController extends Controller
{
    public function index(Request $request){
        if($request->draw){
            return datatables()::of(Courier_zone::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($div) {
                $data = '';
                if(check_access('edit-courier-representative')){
                    $data .= "<a href='javaScript:;' class='btn btn-sm btn-secondary edit' data-id='".$div->id."'><span class='fas fa-edit'></span></a>";
                }
                if(check_access('delete-courier-representative')){
                    $data .= "<a href='javaScript:;' class='btn btn-sm btn-danger delete' id='".$div->id."' ><span class='fas fa-trash-alt'></span></a>";
                }
                return $data;
            })
            ->addColumn('company', function($zone){
                return $zone->courier_company->name;
            })
            ->addColumn('orders', function($zone){
                return $zone->courier_company_orders()->count().' <a href="#">Orders</a>';
            })
            ->editColumn('status', function($zone){
                if($zone->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })
            ->rawColumns(['company','orders','status','modify'])->make(true);
        }
        return view('common.courier.zone.list');
    }

    public function store(Request $request){
      
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $data = [ 'courier_company_id'=>$request->company,'name'=>$request->name,'duration'=>$request->duration, 
        'delivery_cost'=>$request->delivery_cost,'return_cost'=>$request->return_cost, 'description'=>$request->description, 'status'=>$request->status];
        Courier_zone::create($data);
        return response()->json(['success' => 'Courier zone has been created successfully!']);
    }

    public function show(Courier_zone $courier_zone){ return $courier_zone;}


    public function update(Request $request,Courier_zone $courier_zone){
        $validator = $this->fields($courier_zone->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'courier_company_id'=>$request->company,'name'=>$request->name,'duration'=>$request->duration, 
        'delivery_cost'=>$request->delivery_cost, 'return_cost'=>$request->return_cost, 'description'=>$request->description, 'status'=>$request->status];

        $courier_zone->update($data);
        $courier_zone->update($data);
        return response()->json(['success' => 'The Courier zone hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'name'=>'required',
            'duration'=>'required', 'description'=>'sometimes|nullable',
            'delivery_cost'=>'required', 
            'return_cost'=>'required',
            'status'=>'required',
        ]); return $validator;
    }


    public function destroy(Courier_zone $courier_zone){
        try {
            $courier_zone->delete();
            return response()->json(['success' => 'Courier_zone hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function company_zones(Courier_company $courier_company){
        return Courier_zone::where('courier_company_id',$courier_company->id)->get();
    }

}
