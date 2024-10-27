<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Courier_company;
use App\Models\Courier_company_order;
use App\Models\Courier_order_bundle;
use App\Models\Courier_representative;
use App\Models\Order;
use App\Models\Order_status;
use Illuminate\Http\Request;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CourierController extends Controller
{
    function index(Request $request){
        if($request->draw){
            return datatables()::of(Courier_company::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('edit-courier-company')){
                    $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';
                }
                if(check_access('delete-courier-company')){
                    $data .= ' <button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }

                if(check_access('view-courier-representative')){
                    $data .= ' <button type="button" class="btn btn-primary btn-sm representative" id="'.$row->id.'"><span class="feather icon-user-plus"></span></button>';
                }
                if(check_access('view-courier-zone')){
                    $data .= '<button type="button" class="btn btn-secondary btn-sm zone" id="'.$row->id.'"><span class="feather icon-map-pin"></span></button>';
                }
                $data.= '</div>'; return $data;
            })
            ->editColumn('logo', function($row){
                return '<img style="max-width:40px" src="'.url('storage/'.$row->logo).'">';
            })
            ->editColumn('bundles', function($row){
               
                return '<a target="_blank" href="'.route("common.courier.company.report",$row->id).'"><b>'.$row->courier_order_bundles()->count().'</b> bundles</a>';
            })

            ->editColumn('status', function($row){
                if($row->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['logo','bundles','status','modify'])->make(true);
        }
        return view('common.courier.companies.index');
    }

    function company_report($courier_company=null, Request $request){
		/*
        $courier_company_orders = Courier_company_order::all();
        foreach($courier_company_orders as $cco){
        //    dd($cco->courier_order_bundle->courier_company_id, $cco->courier_zone->courier_company_id);
            
            if($cco->courier_company_id ==null){
                $cco->update([
                    'courier_company_id'=>$cco->courier_order_bundle->courier_company_id,
                ]);
            }
            
            if($cco->delivery_cost ==null){
                $courier_zone = DB::table('courier_zones')->where('id',$cco->courier_zone_id)->first();
                $cco->update([
                    'delivery_cost'=>$courier_zone->delivery_cost,
                    'return_cost'=>$courier_zone->return_cost,
                ]);
            }
        } */

        // dd($courier_company);
        $courier_company = Courier_company::where('id',$courier_company)->first();
        if($courier_company==null){
            $bundles = null;
        }else{

            if($request->start && $request->end){
                // dd($request->start);
                $date1 = Carbon::parse($request->start);
                $date2 = Carbon::parse($request->end);
                if ($request->start==$request->end) {
                    $bundles = $courier_company->courier_order_bundles()
                    ->whereDate('created_at',$request->end)
                    ->orderBy('created_at','DESC')
                    ->get();
                } else {
                    $bundles = $courier_company->courier_order_bundles()
                    ->whereBetween('created_at',[$request->start,$request->end])
                    ->orderBy('created_at','DESC')
                    ->get();
                }
            }else{
                $bundles = $courier_company->courier_order_bundles()->orderBy('created_at','DESC')->get();
            }
        }
        return view('common.courier.companies.report', compact('courier_company','bundles'));
    }

    function courier_order_bundle(Courier_order_bundle $courier_order_bundle){
       $orderIDs = Courier_company_order::where('courier_order_bundle_id',$courier_order_bundle->id)->select('order_id')->get()->toArray();
       $orders = Order::whereIn('id',$orderIDs)->get();
    //    dd($courier_order_bundle->courier_company_id);
       return view('common.courier.companies.bunle-orders', compact('courier_order_bundle','orders'));
    }

    function ready_to_ship(){
        $order_status= Order_status::where('relational_activity','prepare-to-ship')->first();
        if($order_status ==null) $order_status = new Order_status();
        if($order_status !=null){
            $orders = Order::where('order_status_id',$order_status->id)->orderBy('id', 'DESC')->paginate(50);
        }else $orders = new Order();
        return view('common.order.index', compact('order_status','orders'));
    }


    public function store(Request $request)
    {
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'name'=>$request->name,'location'=>$request->address,
        'commission_in'=>$request->commission_in, 'amount'=>$request->amount,'status'=>$request->status];

        $courier_company = Courier_company::create($data);

        $this->storeImage($courier_company);
        return response()->json(['success' => 'Company hasn been created successfully!']);
    }

    public function show(Courier_company $courier_company){
        return Courier_company::find($courier_company->id);
    }


    public function update(Request $request,Courier_company $courier_company)
    {
        $validator = $this->fields($courier_company->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'name'=>$request->name,'location'=>$request->address,
        'commission_in'=>$request->commission_in, 'amount'=>$request->amount,'status'=>$request->status];
        $courier_company->update($data);
        $this->storeImage($courier_company,'update');

        return response()->json(['success' => 'The Company hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'name'=>'required|unique:courier_companies,name,'.$id,
            'logo'=>'sometimes|nullable|image', 'location'=>'sometimes|nullable',
            'commission_in'=>'required', 'amount'=>'required','status'=>'required',
        ]); return $validator;
    }

    function storeImage($courier_company,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(300, 300);
            Storage::disk('public')->put("images/courier/".$imageName, (string) $image->encode());
            $courier_company->update(['logo'=>"images/courier/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }

    public function destroy(Courier_company $courier_company)
    {
        try {
            if(\file_exists(public_path('storage/').$courier_company->logo) && $courier_company->logo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$courier_company->logo);
            }
            $courier_company->delete();
            return response()->json(['success' => 'Company hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function representatives(Courier_company $courier_company){
        $mans = Courier_representative::where('courier_company_id',$courier_company->id)->get();
        return $mans;
        // return view('common.courier.companies.representatives',compact('mans'));
    }




    function monthly_orders (Request $request, Courier_company $courier_company){

        if(request()->get('from_month') && request()->get('to_month')){
            // $to = request()->to_year.'-'.request()->to_month;
            // $startDate = request()->from_year.'-'.request()->from_month.'-01';
            // $endDate =  date('Y-m-t', strtotime("$to-01"));

            // $startDate = Carbon::createFromDate($request->from_year, $request->from_month, 1);
            // $endDate = Carbon::createFromDate($request->to_year, $request->to_month, 1)->endOfMonth();

            // dd(date('Y-m-d',strtotime($startDate)), date('Y-m-d',strtotime($endDate)));

            $startDate = Carbon::create($request->from_year, $request->from_month, 1)->startOfDay();
            $endDate = Carbon::create($request->to_year, $request->to_month, 1)->endOfMonth()->endOfDay();

            $bundles = Courier_order_bundle::where('courier_company_id', $courier_company->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->get();
        }else{
            $startDate = Carbon::now()->subMonths(12);
            $endDate = Carbon::now();


            $bundles = Courier_order_bundle::where('courier_company_id',$courier_company->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as count')
                )->get();

            // $bundles = $courier_company->courier_order_bundles()->orderBy('created_at','DESC')->get();
            // dd($bundles);
            // $bundles = $courier_company->courier_order_bundles()->orderBy('created_at','DESC')->get();
        }

       return view('common.courier.companies.monthly-report', compact('courier_company','bundles'));
    }
}
