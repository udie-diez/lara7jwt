<?php

namespace App\Http\Controllers;

use App\Mail\MyEmail;
use App\Mail\SitrendyEmail;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BackupController extends Controller
{


    public function db($db='sitrendy')
    {
        try {
            // start the backup process
            Artisan::call('database:backup '.$db);
            // $output = Artisan::output();
            // log the results
            // Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);
            // return the results as a response to the ajax call
            // Alert::success('New backup created');

            echo 'backup '.$db.' sukses <br>' ;

            $this->email($db);

        } catch (Exception $e) {
            // Flash::error($e->getMessage());
            echo $e->getMessage();
        }
    }

    public function email($db){

        Mail::to('sitrendy.official@gmail.com')->send(new SitrendyEmail('backup', $db, ''));
        // Mail::to('sipbj.sulsel@gmail.com')->send(new MyEmail('bajubodo_file', $db, ''));

        echo "email ok";
    }
    
}


?>
