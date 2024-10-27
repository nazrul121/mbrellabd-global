<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    function single_one($phone){
        $contacts = Contact::where('phone',$phone)->orderBy('id','DESC')->get();
        return view('common.contact.messages',compact('contacts'));
    }
}
