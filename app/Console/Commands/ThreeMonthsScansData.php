<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Sheets;
use DB;

class ThreeMonthsScansData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'threemonthshealth:scans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Report of 3 months health scan sheets and update on google sheet and mail the sheet';

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
        $startDate = '2023-04-01';
        $endDate = '2023-04-30';

        $results = DB::table('health_scans')
        ->select('users.phone', 'health_scans.user_id',
            DB::raw("COUNT(CASE WHEN health_scans.created_at BETWEEN '2023-03-01' AND '2023-03-31' THEN 1 END) AS user_health_scan_march"),
            DB::raw("COUNT(CASE WHEN health_scans.created_at BETWEEN '2023-04-01' AND '2023-04-30' THEN 1 END) AS user_health_scan_april"),
            DB::raw("COUNT(CASE WHEN health_scans.created_at BETWEEN '2023-05-01' AND '2023-05-31' THEN 1 END) AS user_health_scan_may")
        )
        ->join('users', 'users.id', '=', 'health_scans.user_id')
        ->where('health_scans.user_id', '!=', 6969)
        ->where('health_scans.user_id', '>=', 1245)
        ->whereBetween('health_scans.created_at', ['2023-03-01', '2023-05-31'])
        ->groupBy('health_scans.user_id', 'users.phone')
        ->get();
    //   dd($results);
        $sheets = Sheets::spreadsheet(env('THREE_MONTHS_HEALTH_SCANS_LIST'))->sheet('Sheet1')->get();

        // $sheets = Sheets::spreadsheet(env('USER_METRICS_SHEET'))->sheet('May')->get();
        $dataForGoogleSheet = [];
        $lastRecord = count($sheets) > 5 ? count($sheets) - 5 : 0;
        $createRange = count($sheets) + 1;
        foreach ($results as $key => $data) {
            $row['number ']                    = $data->phone ? $data->phone : 'NULL';
            $row['march ']           = $data->user_health_scan_march ?$data->user_health_scan_march :'NULL';
            $row['april ']           = $data->user_health_scan_april ?$data->user_health_scan_april :'NULL';
            $row['may ']           = $data->user_health_scan_may ?$data->user_health_scan_may :'NULL';
            // fputcsv($file, $row);

            $dataForGoogleSheet[] = $row;
        }
        $sheets = Sheets::spreadsheet(env('THREE_MONTHS_HEALTH_SCANS_LIST'))->sheet('Sheet1')->range('A'.$createRange)->append($dataForGoogleSheet);
        return 0;
    }
}
