<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Validator;

class FaqController extends Controller
{
    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Faq::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-faq')){
                    $data .='<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-faq')){
                    $data .='<button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                return '</div>'.$data;
            })
            ->editColumn('answer', function($cat){
                if(strlen($cat->answer) > 80 ) return substr($cat->answer, 0, 80).' ...';
                else return  $cat->answer;
            })
            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->editColumn('country', function($cat){
                $country = '';
                foreach($cat->countries()->select('short_name','flag')->get() as $cnt){
                 $country .= '<img src="'.url($cnt->flag).'" title="'.$cnt->short_name.'"> ';
                }
                return $country;
            })

            ->rawColumns(['country','answer','status','modify'])->make(true);
        }
        return view('common.faq.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'question'=>$request->question, 'answer'=>$request->answer,'status'=>$request->status];
        $Faq = Faq::create($data);
        $Faq->save();
        $Faq->countries()->attach($request->langs);
        return response()->json(['success' => 'Faq has been created successfully!']);
    }

    public function show(Faq $Faq){ 
        $Faq['country'] = $Faq->countries()->select('country_id')->get();
        return $Faq;
    }


    public function update(Request $request,Faq $Faq){
        $validator = $this->fields($Faq->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'question'=>$request->question, 'answer'=>$request->answer,'status'=>$request->status];

        $Faq->update($data);
        $Faq->countries()->sync($request->langs);
        return response()->json(['success' => 'The Faq hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'question'=>'required|unique:faqs,question,'.$id,
            'answer'=>'required',
            'status'=>'required',
        ]); return $validator;
    }



    public function destroy(Faq $Faq){
        try {
            $Faq->delete();
            return response()->json(['success' => 'Faq info hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
