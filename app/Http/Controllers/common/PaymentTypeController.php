<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Payment_type;
use Illuminate\Http\Request;
use Validator;

class PaymentTypeController extends Controller
{
    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Payment_type::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-payment-type')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-payment-type')){
                    $data .= ' <button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                $data .= '</div>';  return $data;
            })
            ->editColumn('transaction', function($row){
                return '<b>'.$row->order_payments()->count().'</b> Transactions';
            })
            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })
            ->rawColumns(['transaction','status','modify'])->make(true);
        }
        return view('common.payment.type.index');
    }

    function create(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title, 'description'=>$request->description,'status'=>$request->status];
        $Faq = Payment_type::create($data);
        $Faq->save();
        return response()->json(['success' => 'Payment type has been created successfully!']);
    }

    public function show(Payment_type $payment_type){ return Payment_type::find($payment_type->id);}


    public function update(Request $request,Payment_type $payment_type){
        $validator = $this->fields($payment_type->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title, 'description'=>$request->description,'status'=>$request->status];

        $payment_type->update($data);

        return response()->json(['success' => 'The payment_type hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:payment_types,title,'.$id,
            'description'=>'sometimes|nullable',
            'status'=>'required',
        ]); return $validator;
    }

    public function destroy(Payment_type $payment_type){
        try {
            if($payment_type->id=='1'){
                return response()->json(['error' => 'Deletion failed. SSL Commerz is default!!']);
            }
            $payment_type->delete();
            return response()->json(['success' => 'Payment type info hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

}
