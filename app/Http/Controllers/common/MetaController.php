<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Meta;
use App\Models\News;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Validator;
use Symfony\Component\String\UnicodeString;
use Illuminate\Support\Str;

class MetaController extends Controller
{
    function index(Request $request){
        if ($request->draw) {
            $data = Meta::select('*');
    
            return DataTables::of($data)
                ->editColumn('modify', function($row){
                   $btn = '<button class="btn-info btn-sm edit" id="'.$row->id.'"> <i class="fa fa-edit text-white"></i></button> ';
                  
                   $btn .= '<button class="btn-warning btn-sm delete" id="'.$row->id.'"> <i class="fa fa-trash text-white"></i></button>';
                   return $btn;
                })

                ->editColumn('description', function($row){
                    return Str::limit($row->description, 100);
                })

                ->rawColumns(['description','modify'])
                ->make(true);
        }
        return view('common.meta.index');
    }

    public function store(Request $request){
        
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
     
        $data = [ 
            'type'=>$request->keywords,
            'description'=>$request->description,
            'pageFor'=>$request->pageFor
        ];
        Meta::create($data);
        return response()->json(['success' => 'The Meta item has been saved successfully!']);
    }

    function show(Meta $meta){
        return $meta;
    }
 
 
    public function update(Request $request, $id){
   
        $validator = $this->fields($request->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 
            'type'=>$request->keywords,
            'description'=>$request->description,
            'pageFor'=>$request->pageFor
        ];
        Meta::where('id',$request->id)->update($data);
        
        return response()->json(['success' => 'The Meta item has been udpated successfully!']);

    }

    public function destroy( Meta $meta ){
        try {
            $meta->delete();
            return response()->json(['success' => 'Data hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
        // return request()->validate([
            'keywords'=>'required',
            'description'=>'required',
        ]); 
        return $validator;
    }

}
