<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SiteMapController extends Controller
{
    function index(){
        $site = App::make('sitemap');
        $site->add(URL::to('/'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/truck'),date('Y-m-d h:i:s'), 1, 'daily');

        $site->add(URL::to('/instagram-feed'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/products'),date('Y-m-d h:i:s'), 1, 'daily');

        $site->add(URL::to('/faqs'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/add-to-cart'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/my-cart'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/wishlist'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/categories'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/promotions'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/blog'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/auth/google'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/auth/google/callback'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/auth/facebook'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/auth/facebook/callback'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/autocomplete-search'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/search'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/change-currency'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/register'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/dashboard'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/change-password'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/checkout'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/showrooms'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/subscribe'),date('Y-m-d h:i:s'), 1, 'daily');
        $site->add(URL::to('/sitemap'),date('Y-m-d h:i:s'), 1, 'daily');

        $categories = \App\Models\Group::all();
        foreach($categories as $g){
            $site->add(URL::to('/group/'.$g->slug),$g->created_at, 1, 'daily');
        }

        $categories = \App\Models\Inner_group::all();
        foreach($categories as $g){
            $site->add(URL::to('/group-in/'.$g->slug),$g->created_at, 1, 'daily');
        }

        $categories = \App\Models\Child_group::all();
        foreach($categories as $g){
            $site->add(URL::to('/child-in/'.$g->slug),$g->created_at, 1, 'daily');
        }

        $policies = \App\Models\Policy_type::all();
        foreach($policies as $p){
            $site->add(URL::to('/about/policy/'.$p->slug),$p->created_at, 1, 'daily');
        }

        $campaigns = \App\Models\Campaign::all();
        foreach($campaigns as $c){
            $site->add(URL::to('/campaign/'.str_replace(' ','-',$c->title)),$c->created_at, 1, 'daily');
        }

        $blogs = \App\Models\Blog::all();
        foreach($blogs as $b){
            $site->add(URL::to('/news/'.$b->slug),$b->created_at, 1, 'daily');
        }

        $seasons = \App\Models\Season::all();
        foreach($seasons as $b){
            $site->add(URL::to('/season-items/'.$b->slug),$b->created_at, 1, 'daily');
        }

        $products = \App\Models\Product::all();
        foreach($products as $product){
            $site->add(URL::to('/product/'.$product->slug),$product->created_at, 1, 'daily');
        }

        $promotions = \App\Models\Promotion::all();
        foreach($promotions as $product){
            $site->add(URL::to('/promo-items/'.$product->slug),$product->created_at, 1, 'daily');
        }

        // $orders = \App\Models\Order::all();
        // foreach($orders as $order){
        //     $site->add(URL::to('/print-invoice/'.$order->transaction_id),$order->created_at, 1, 'daily');
        //     $site->add(URL::to('/order-info/'.$order->transaction_id),$order->created_at, 1, 'daily');
        // }


        $site->store('xml','sitemap');

        echo '<p style="text-align:center;margin:5px;padding:10%;color: white;font-size:25px;background:#ebcccc;height:50vh;">Site Map has been updated successfully! <br/>
            <button onclick="history.back();" type="button" style="font-size:20px;padding: 1em;margin-top: 2em;">Get Back</button>
            OR <a href="'.url('/sitemap.xml').'" target="blank" style="background:#efefef;padding:18px;border:1px solid;text-decoration:none;">view SiteMap</a>
        </p>';
    }
}
