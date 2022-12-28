<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DatabaseBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup {db}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup db Sitrendy';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $db = $this->argument('db');

        $filename = $db."_backup-" . Carbon::now()->format('Y-m-d') . ".sql";

        $command = "mysqldump -u" . env('DB_USERNAME') . " -p'" . env('DB_PASSWORD') . "#' " . env('DB_DATABASE') . "  > " . storage_path() . "/app/backup/" . $filename;
        
        // $command = "/Applications/XAMPP/bin/mysqldump -uroot ".$db."  > " . storage_path() . "/app/backup/" . $filename;

        // $returnVar = NULL;
        // $output = NULL;

        exec($command, $output, $returnVar);

        $sqlfile = storage_path() . "/app/backup/" . $filename;
        $command = "gzip ".$sqlfile;
        exec($command, $output, $returnVar);


    }
}
