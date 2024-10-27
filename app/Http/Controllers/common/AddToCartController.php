<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Cartlist;
use Illuminate\Http\Request;

class AddToCartController extends Controller
{
    function index(Request $request){
        $cartlist  = Cartlist::where('status','1')->orderBy('id','DESC')->distinct('session_id')->select('session_id')->paginate(20);
        return view('common.addToCart.index', compact('cartlist'));
    }

    function cart_items($session_id){
        $cartlist = Cartlist::where('session_id',$session_id)->get(); 
        return view('common.addToCart.cart-items', compact('cartlist'));
    }

}
