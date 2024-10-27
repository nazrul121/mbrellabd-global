<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Highlight;
use App\Models\Highlight_product;
use App\Models\Product;
use App\Models\Country_product;
use Illuminate\Http\Request;

class HighlightProductController extends Controller
{
    function add_product(Request $request, Highlight $highlight){
        //add product into hightlight_product table
        if($request->product_id){
            $check = Highlight_product::where(['highlight_id'=>$highlight->id,'product_id'=>$request->product_id]);
            if($check->count() <1){
                Highlight_product::create(['highlight_id'=>$highlight->id,'product_id'=>$request->product_id]);
                return response()->json(['success' => 'Item has been integrarted into '.$highlight->title]);
            }
            return response()->json(['success' => 'Item has alreay been integrarted into '.$highlight->title]);
        }

        else if($request->draw){
            return datatables()::of(Highlight_product::where('highlight_id',$highlight->id)->orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                return ' <button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button> ';
            })
            ->editColumn('photo', function($row){
                return '<img style="max-width:40px" src="'.url('storage/'.$row->product->feature_photo).'">';
            })
            ->editColumn('title', function($row){
                return $row->product->title;
            })
            ->rawColumns(['photo','title','modify'])->make(true);
        }else{
            return view('common.product.highlight.add-product',compact('highlight'));
        }

    }

    function search_product(Request $request, Highlight $highlight){
        $highlightCountry = $highlight->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
        // Get products associated with the country IDs
        $productCountry = Country_product::whereIn('country_id', $highlightCountry)->select('product_id')->distinct()->get()->toArray();


        $data = Product::whereIn('id',$productCountry)->where('title','LIKE',$request->name.'%')
        ->orWhere('design_code',$request->name)->get();
        $output = '';
        if ($data->count() >0) {
            $output = '<ul class="list-group ml-4" style="display:block;margin-bottom:3em;border-bottom:1px solid silver;box-shadow:0px 1px 15px;">';
            foreach ($data as $row) {
                $output .= '<li data-id="'.$row->id.'" class="list-group-item" style="cursor:pointer"><img src="'.url('storage/'.$row->thumbs).'" height="20"> &nbsp; '.$row->title.'</li>';
            }
            $output .= '</ul>';
            return $output;
        }else {
            return '<p class="text-danger text-center mb-3" style="border-bottom:1px solid;padding-bottom:1.3em;">'.'No Data Found'.'</p>';
        }

    }

    function destroy(Highlight_product $highlight_product){
        $highlight_product->delete();
        return response()->json(['success' => 'Item hasn been removed successfully!']);
    }
}
