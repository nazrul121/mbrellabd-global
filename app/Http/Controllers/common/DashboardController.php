<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{

    public function index(){
        return view('common.includes.dashboard');
    }

}
