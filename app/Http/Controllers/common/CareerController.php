<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Career_candidate;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
use Session;

class CareerController extends Controller
{
    public function index(Request $request){
        if($request->draw){
            return datatables()::of(Career::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-career')){
                    $data .='<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-career')){
                    $data .='<a href="'.route('common.career.single-item',$cat->id).'" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></a>';
                }
                if(check_access('career-applicants')){
                    $data .='<button type="button" href="'.route('common.career.applicants',$cat->id).'" class="btn btn-primary btn-sm applicants" id="'.$cat->id.'"><span class="feather icon-users"></span></button>';
                }
                return '</div>'.$data;
            })
            ->editColumn('description', function($cat){
                if(utf8_encode(strlen($cat->description)) > 50 ) return substr(utf8_encode(strip_tags($cat->description)), 0, 50).' ...';
                else return utf8_encode(strip_tags($cat->description));
            })
            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })
            ->editColumn('last_date', function($cat){
                return date('F d, Y',strtotime($cat->last_date));
            })
            ->editColumn('applicants', function($cat){
                return '<b>'.$cat->career_candidates()->count().'</b> applicants';
            })

            ->editColumn('country', function($cat){
                $country = '';
                foreach($cat->countries()->select('short_name','flag')->get() as $cnt){
                 $country .= '<img src="'.url($cnt->flag).'" title="'.$cnt->short_name.'"> ';
                }
                return $country;
            })

            ->rawColumns(['country','applicants','last_date','description','status','modify'])->make(true);
        }
        return view('common.career.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title, 
            'description'=>$request->description,
            'meta_title'=>$request->meta_title, 
            'meta_description'=>$request->meta_description,
            'last_date'=>$request->last_date, 
            'status'=>$request->status
        ];
        $data['slug'] = Str::slug($request->title, '-');

        $career = Career::create($data);
        $career->countries()->attach($request->langs);
        $career->save();
        return response()->json(['success' => 'Career has been created successfully!']);
    }

    public function show(Career $career){ 
        return view('common.career.edit', compact('career'));
    }

    public function update(Request $request,Career $career){
        $validator = $this->fields($career->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title, 'description'=>$request->description,
        'meta_title'=>$request->meta_title, 'meta_description'=>$request->meta_description,
        'last_date'=>$request->last_date, 'status'=>$request->status];
        $data['slug'] = Str::slug($request->title, '-');
        
        $career->update($data);
        $career->countries()->sync($request->langs);
        Session::flash('success', 'The Career hasn been updated successfully!');
        return view('common.career.index');
    }

    function applicants(Request $request,Career $career){
        if($request->draw){
            return datatables()::of(Career_candidate::where('career_id',$career->id))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                
             
                return '</div>'.$data;
            })
            ->editColumn('applicant_info', function($row){
                $data =  'Name: '.$row->name.'<br/>Phone: '.$row->phone;
                if($row->email !=null){
                    $data.= 'Email: '.$row->email;
                }
                return $data;
            })
            ->editColumn('cover_latter', function($row){
                if(strlen($row->cover_latter) >30){
                    return mb_substr($row->cover_latter,0,30).' ..<a href="#" class="more" data-letter="'.$row->cover_latter.'">More</a>';
                }else return $row->cover_latter;
            })
            ->editColumn('date', function($row){
                return date('F d, Y',strtotime($row->created_at));
            })
            
            ->editColumn('resume', function($row){
                if(file_exists($row->cv)){
                    return '<a target="_blank" href="/'.$row->cv.'">CV attached</a>';
                }else return 'No attached';
            })

            ->rawColumns(['applicant_info','cover_latter','resume','date','modify'])->make(true);
        }
        return view('common.career.applicants', compact('career'));
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required',
            'description'=>'required',
            'last_date'=>'required',
            'status'=>'required',
        ]); return $validator;
    }

    public function destroy(Career $career){
        try {
            $career->delete();
            return response()->json(['success' => 'Career info hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
