<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Validator; use Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class CurrencyController extends Controller
{
    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Country::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('edit-currency')){
                    $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                if(check_access('delete-currency')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                return $data .'</div>';
            })

            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->editColumn('is_default', function($cat){
                if($cat->is_default=='1') return '<span class="badge badge-success">Default</span>';
            })

            ->editColumn('symbol', function($cat){
                return '<img src="'.$cat->flag.'"> Symbol <b>'.$cat->currencySymbol.'</b>';
            })
            ->editColumn('value', function($cat){
                return '<b>'.$cat->currency_code.'</b> '.$cat->currencyValue;
            })

            ->rawColumns(['symbol','value','is_default','status','modify'])->make(true);
        }
        return view('common.setting.currency.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if (isset($request->is_default)) $is_default = '1'; else $is_default = '0';

        $data = [ 'name'=>$request->name, 
            'short_name'=>$request->short_name,
            'phone_code'=>$request->phone_code,
            'currencySymbol'=>$request->currency_symbol,
            'currencyValue'=>$request->currency_value,
            'currency_code'=>$request->currency_code,
            'zone'=>$request->zone,
            'status'=>$request->status,
        ];

        // if($is_default=='1'){
        //     Currency::where('id','!=',0)->update(['is_default'=>'0']);
        // }
        $country = Country::create($data);
        $this->storeImage($country);
        Session::forget('cart');
        return response()->json(['success' => 'Country has been created successfully!']);
    }

    public function show(Country $country){ 
        return country::find($country->id);
    }


    public function update(Request $request,Country $country){
        $validator = $this->fields($country->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if (isset($request->is_default)) $is_default = '1'; else $is_default = '0';

        $data = [ 'name'=>$request->name, 
            'short_name'=>$request->short_name,
            'phone_code'=>$request->phone_code,
            'currencySymbol'=>$request->currency_symbol,
            'currencyValue'=>$request->currency_value,
            'currency_code'=>$request->currency_code,
            'zone'=>$request->zone,
            'status'=>$request->status,
        ];


        $country->update($data);
        $this->storeImage($country,'update');
        Session::forget('cart');
        return response()->json(['success' => 'The Country hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'name'=>'required|unique:countries,name,'.$id,
            'short_name'=>'required|unique:countries,short_name,'.$id,
            'currency_symbol'=>'required', 
            'currency_value'=>'required', 
            'status'=>'required',
            'phone_code'=>'required'
        ]); return $validator;
    }

    function storeImage($country,$type=null){
        if (request()->has('flag')) {
            $fieldFile = request()->flag;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(23, 15);
            Storage::disk('public')->put("images/flag/".$imageName, (string) $image->encode());
            $country->update(['flag'=>"/storage/images/flag/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/user.jpg') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }

    public function destroy(Country $country)
    {
        try {
            if($country->is_default=='1'){
                return response()->json(['error' => 'Default currency cannot be deleted!']);
            }else{
                $country->delete();
                return response()->json(['success' => 'Currency hasn been deleted successfully!']);
            }


        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

}
