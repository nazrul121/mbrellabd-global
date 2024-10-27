<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class MaintenanceController extends Controller
{
    public function down(Request $request)
    {
        Artisan::call('down');
        return response()->json(['message' => 'Application is now in maintenance mode.']);
    }

    public function up()
    {
        Artisan::call('up');
        Log::info('Application brought back online');
        return response()->json(['message' => 'Application is now live.']);
    }
}
