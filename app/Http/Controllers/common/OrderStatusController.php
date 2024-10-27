<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Order_status;
use Illuminate\Http\Request;
use Session;
class OrderStatusController extends Controller
{

    public function index(Request $request){
        if($request->sortBy=='1'){
            $order_status = Order_status::orderBy('sort_by')->get();
        }else{
            $order_status = Order_status::all();
        }
        
        return view('common.order.status.index',compact('order_status'));
    }

    function order_status_sorting(Request $request){
        for($i=0; $i<count($request->page_id_array); $i++){
            Order_status::where('id',$request->page_id_array[$i])->update([ 'sort_by'=>$i ]);
        }
    }

    function store(Request $request){
        Session::flash('action','alert');
        $data = $this->fields();
        Order_status::create($data);
        Session::flash('action','success');
        Session::flash('alert', 'Status has been created successfully');
        return back();
    }

    function show(Order_status $order_status){
        return $order_status;
    }

    function update(Request $request, Order_status $order_status){
        Session::flash('action','alert');
        $data = $this->fields($order_status->id);
        $order_status->update($data);
        Session::flash('action','success');
        Session::flash('alert', 'Status has been updated successfully');
        return back();
    }


    private function fields($id=null){
        return request()->validate([
            'title'=>'required',
            'description'=>'required',
            'action'=>'required',
            'qty_status'=>'sometimes|nullable',
            'relational_activity'=>'sometimes|nullable|unique:order_statuses,relational_activity,'.$id
        ]);
    }
    
    function destroy(Order_status $order_status){
        try {
            if($order_status->id=='1'){
                Session::flash('action','alert');
                Session::flash('alert', 'Status Cannot be deleted. Its a Primary Status');
                return back();
            }
            $order_status->delete();
            Session::flash('action','success');
            Session::flash('alert', 'Status has been deleted successfully');
            return back();
        } catch (\Throwable $th) {
            Session::flash('action','alert');
            Session::flash('alert', 'Deletion failed. Its may be the foreign key constrate error!!');
            return back();
        }
    }

}
