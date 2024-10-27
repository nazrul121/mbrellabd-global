<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Career_candidate;
use Illuminate\Http\Request;
use Validator;

use Session;
use Illuminate\Support\Facades\Auth;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;


class CareerController extends Controller
{

    function index(){
        $ids = \App\Models\Career_country::where('country_id',session('user_currency')->id)->select('career_id')->get()->toArray();
        $careers = Career::whereIn('id',$ids)->where('status','1')->paginate(10);
        return view('career', compact('careers'));
    }

    function career_job(Request $request, $lang, $slug){
        $career = Career::where(['slug'=>$slug, 'status'=>'1'])->first();
        if($career !=null){
            $isInCountry = \App\Models\Career_country::where(['country_id'=>session('user_currency')->id,'career_id'=>$career->id]);

            if($isInCountry->count()>0){
                return view('career-details', compact('career'));
            }
        }
        
        return view('errors.408');
    }

    function save_applicant(Career $career, Request $request){

        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $check = Career_candidate::where(['career_id'=>$career->id, 'phone'=>$request->phone]);
        if($check->count() >0){
            return response()->json(['alert' => 'You have already applied over the job!!']);
        }

        if($career->status =='0'){
            return response()->json(['alert' => 'The job no more exist! Please refresh your page!']);
        }



        $data = [
            'career_id'=>$career->id, 'name'=>$request->name,
            'email'=>$request->email, 'phone'=>$request->phone,
            'cover_latter'=>$request->cover_letter,
        ];

        $candidate = Career_candidate::create($data);
        $this->fileUpload($candidate, $request);

        return response()->json(['success' => 'You have successfully applied for the post! One of our representative will contact you soon!!']);
    }

    public function fileUpload($candidate, $request){
        $filename = $request->name.time() . '.' . $request->cv_resume->extension();
        $request->file('cv_resume')->move('storage/cv/',$filename);
        $candidate->update(['cv'=>'storage/cv/'.$filename]);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'cover_letter'=>'required|min:100',
            'cv_resume'=>'required|mimes:pdf,doc,docx',
        ]); return $validator;
    }
  
}

