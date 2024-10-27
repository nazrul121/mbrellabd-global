<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Invoice_discount;
use Illuminate\Http\Request;
use Validator; use Auth;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class InvoiceDiscountController extends Controller
{
    function index(Request $request){
        if($request->draw){
            return datatables()::of(Invoice_discount::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                return ' <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                    <button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>
                    <button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>
                </div>';
            })

            ->editColumn('min_order_amount', function($row){
                return  $row->min_order_amount.' TK';
            })
            ->editColumn('validity', function($row){
               $data = 'from <b>'.date('d/m/Y',strtotime($row->start_date)).'</b> to <b>'.date('d/m/Y',strtotime($row->end_date)).'</b>';
               return $data;
            })

            ->editColumn('discounts', function($row){
                if($row->type=='general'){
                    if($row->discount_in =='percent'){
                        $data = '<b>'.$row->discount_value.'% </b> discount to every invoice';
                    }else $data = '<b>'.$row->discount_value.' Tk </b> discount to every invoice';
                }else if($row->type=='product'){
                    $data = 'product free..';
                }else $data = 'free delivery';
            
                return $data;
            })

            ->editColumn('invoice_number', function($row){
                $data = 'Includes <b>'.$row->invoice_discount_orders()->count().'</b> invoices';
                return $data;
            })
            ->editColumn('photo', function($row){
                return '<img src="/storage/'.$row->photo.'" height="30">';
            })

            ->editColumn('status', function($row){
                if($row->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })
            ->editColumn('country', function($row){
                $country = '';
                foreach($row->countries()->select('short_name','flag')->get() as $cnt){
                    $country .= '<img src="'.url($cnt->flag).'" title="'.$cnt->short_name.'"> ';
                }
                return $country;
            })


            ->rawColumns(['country','photo','min_order_amount','discounts','validity','invoice_number','status','modify'])->make(true);
        }
        return view('common.ad.invoice-discount.index');
    }

    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'user_id'=>Auth::user()->id, 'type'=>$request->type, 'min_order_amount'=>$request->min_order_amount, 'title'=>$request->title, 'discount_in'=>$request->discount_in, 'discount_value'=>$request->discount_value,
        'start_date'=>date('Y-m-d',strtotime($request->start_date)), 'end_date'=>date('Y-m-d',strtotime($request->end_date)), 'status'=>$request->status];
        $invoice_discount = Invoice_discount::create($data);
        $invoice_discount->save();
        $invoice_discount->countries()->attach($request->langs);
        $this->storeImage($invoice_discount);
        return response()->json(['success' => 'invoice-discount has been saved successfully!']);
    }

    public function show(Invoice_discount $invoice_discount){
        return $invoice_discount; 
    }


    public function update(Request $request,Invoice_discount $invoice_discount){
        $validator = $this->fields($invoice_discount->id);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'user_id'=>Auth::user()->id, 'type'=>$request->type, 'min_order_amount'=>$request->min_order_amount,  'title'=>$request->title, 'discount_in'=>$request->discount_in, 'discount_value'=>$request->discount_value,
        'start_date'=>date('Y-m-d',strtotime($request->start_date)), 'end_date'=>date('Y-m-d',strtotime($request->end_date)), 'status'=>$request->status];
        $invoice_discount->update($data);
        $invoice_discount->countries()->sync($request->langs);
        $this->storeImage($invoice_discount,'update');
        return response()->json(['success' => 'invoice-discount hasn been updated successfully!']);
    }

    function storeImage($invoice_discount,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(request()->width, request()->height);
            Storage::disk('public')->put("images/promotion/invoice/".$imageName, (string) $image->encode());
            $invoice_discount->update(['photo'=>"images/promotion/invoice/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }



    public function destroy(Invoice_discount $invoice_discount){
        try {
            $invoice_discount->delete();
            return response()->json(['success' => 'The record hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required',
            'min_order_amount'=>'required',
            'type'=>'required',
            'discount_in'=>'sometimes|nullable',
            'discount_value'=>'sometimes|nullable',
            'start_date'=>'required',
            'end_date'=>'required',
            'status'=>'required',
        ]); return $validator;
    }
}
