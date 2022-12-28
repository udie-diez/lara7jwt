<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SitrendyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $jenis;
    public $code;
    public $nama;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $jenis, String $code, String $nama)
    {
        $this->code = $code;
        $this->nama = $nama;
        $this->jenis = $jenis;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $code = Str::random(10);

        if ($this->jenis == 'aktivasi') {
            return $this->from('sitrendy.info@gmail.com')
                ->view('email.newuser')
                ->with(
                    [
                        'nama' => $this->nama,
                        'website' => 'http://sitrendy.id/userakun/aktivasi/' . $this->code .'/' //.Crypt::encrypt($code),
                    ]
                );
        }else if($this->jenis == 'reset'){
            return $this->from('sitrendy.info@gmail.com')
                ->view('email.reset')
                ->with(
                    [
                        'nama' => $this->nama,
                        'website' => 'http://sitrendy.id/userakun/resetpassword/' . $this->code .'/' //.Crypt::encrypt($code),
                    ]
                );
        } else if ($this->jenis == 'backup') {

            $filename = $this->code . "_backup-" . Carbon::now()->format('Y-m-d') . ".sql.gz";

            return $this->from('sitrendy.info@gmail.com')
                ->view('email.backup')
                ->subject('Backup DB ' . $this->code)
                ->attach(storage_path() . "/app/backup/" . $filename)
                ->with(
                    [
                        'db' => $filename
                    ]
                );

            }
        
    }
}
