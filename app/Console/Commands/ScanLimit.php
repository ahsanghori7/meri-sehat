<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSubscription;
use Carbon\Carbon;

class ScanLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $date=Carbon::now();
        
        //cron update consume_scan_limit set to be 0 daily
        UserSubscription::where(['status' => 1])->where('is_expired', 0)->where('end_date' ,'>' ,$date)->where('receipt_data->scan_limit','!=','unlimited')->whereNotNull('receipt_data->consume_scan_limit')
        ->update(['receipt_data->consume_scan_limit' => '0']);

        //cron that update status of expire subscription 0
        UserSubscription::where('end_date' ,'<' ,$date)->update(['is_expired' => 1]);
        
    }
}
