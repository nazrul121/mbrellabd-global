<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    function index(Request $request, $lang){
        $ids = \App\Models\Blog_country::where('country_id',session('user_currency')->id)->select('blog_id')->get()->toArray();
        $blogs = Blog::whereIn('id',$ids)->where('status','1')->orderBy('title','DESC')->paginate(10);
        return view('blog', compact('blogs'));
    }

    function show(Request $request, $lang, $slug){

        $blog = Blog::where(['slug'=>$slug,'status'=>'1'])->first();
        return view('blog-info', compact('blog'));
    }
}
