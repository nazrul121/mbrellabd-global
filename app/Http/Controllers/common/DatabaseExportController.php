<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use ZipArchive;
class DatabaseExportController extends Controller
{
    public function index(Request $request){
        return view('common.backup.index');
    }

    public function exportDatabase(Request $request)
    {

        try {
            $db_name = env('DB_DATABASE');
            $db_user = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $today = date('Y-m-d-H');

            // Create the backup directory if it doesn't exist
            if (!file_exists('storage/backup')) {
                mkdir('storage/backup', 0755, true);
            }

            $backupFileName = "{$db_name}-{$today}.sql";
            $backupFilePath = "storage/backup/{$db_name}-{$today}.sql";

        
            if (!file_exists( $backupFilePath )) {
                $command = "mysqldump --user={$db_user} --password={$db_password} {$db_name} > {$backupFilePath}";
                // Execute the mysqldump command
                exec($command, $output, $returnCode);
            }
          
          
            $filePath = public_path($backupFilePath);
            $zipFileName = "{$db_name}-{$today}.zip";
            $zipFilePath = public_path("storage/backup/{$zipFileName}");

            if (file_exists($filePath)) {
                $zip = new ZipArchive();
                if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                    $zip->addFile($filePath, $backupFilePath);
                    $zip->close();
                    unlink($backupFilePath);
                    // return response()->download($zipFilePath)->deleteFileAfterSend();
                }
            }
            

            // dd( public_path($backupFilePath) ); 

            if($request->send_mail && $request->email){
                try {
                    $data["email"] = $request->email;
                    $data["subject"] = "Mbrellabd.com : Database backup";
                    $data["body"] = "The database is attached. Please look and save for further emergency uses...!";
                    $data["mail_from"] = "IT department [nazrul islam]";

                    $filePath = storage_path('app/public/backup/' . $zipFileName);

                    Mail::send('emails.datanase-vai-email', $data, function($message) use ($data, $filePath) {
                        $message->to($data["email"])->subject($data["subject"]);
                        $message->attach($filePath);
                    });

                    session()->flash('success', 'Database backup saved successfully aslo the file is sent Email!. Path: '.$filePath);
                    return redirect()->back();
                } catch (\Throwable $th) {
                    session()->flash('error', 'Database get backedup but sending email is failed! '.$th->getMessage());
                    return redirect()->back();
                }           
            }

            // download externally
            if($request->external_download){
                $command = "mysqldump --user={$db_user} --password={$db_password} {$db_name} > {$db_name}-{$today}.sql";
                exec($command);
                // return response()->download("{$db_name}-{$today}.sql")->deleteFileAfterSend(true);
                session()->flash('success', 'Database backup saved successfully inside the project. You may also get a backup yourself!!');
                session()->flash('link', $zipFileName);
                return redirect()->back();
                // return redirect("{$db_name}-{$today}.sql", 301);
            }

            session()->flash('success', 'Database backup saved successfully!');
            return redirect()->back();

        } catch (\Exception $e) {
            session()->flash('error', $e);
            return redirect()->back();
        }
    }


   
}
