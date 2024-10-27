<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Payment_gateway;
use Illuminate\Http\Request;
use Validator;

class PaymentGatewayController extends Controller
{
    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Payment_gateway::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = ' <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('eidt-payment-method')){
                    $data .='<button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }

                return $data.'</div>';
            })
            ->editColumn('icon', function($cat){
                return '<span class="'.$cat->icon.'"></span>';
            })
            ->editColumn('description', function($cat){
                return $cat->description;
            })

            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['icon','description','status','modify'])->make(true);
        }
        return view('common.payment.method.index');
    }


    public function show(Payment_gateway $payment_gateway){ return Payment_gateway::find($payment_gateway->id);}


    public function update(Request $request,Payment_gateway $payment_gateway){
        $validator = $this->fields($payment_gateway->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'name'=>$request->name, 'icon'=>$request->icon,'description'=>$request->description,'status'=>$request->status];

        $payment_gateway->update($data);

        return response()->json(['success' => 'The payment_gateway hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'name'=>'required|unique:payment_gateways,name,'.$id,
            'icon'=>'required',
            'name_origin'=>'sometimes|nullable',
            'description'=>'sometimes|nullable',
            'status'=>'required',
        ]); return $validator;
    }

}
