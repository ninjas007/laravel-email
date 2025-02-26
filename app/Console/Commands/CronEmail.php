<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CronEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command untuk running pengiriman email';

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
        $data = User::where('is_sent', 0)->limit(15)->get();

        if ($data) {
            foreach ($data as $d) {
                app(\App\Http\Controllers\EmailController::class)->send($d);
            }
        }
        
        echo 'selesai';
    }
}
