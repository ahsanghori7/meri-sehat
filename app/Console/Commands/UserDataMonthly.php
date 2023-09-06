<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Sheets;
use DB;

class UserDataMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userdata:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Report of User data monthly sheets and update on google sheet and mail the sheet';

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
    {   $month=5;
        $now = Carbon::now()->month($month);
        $monthStartDate = $now->startOfMonth()->format('Y-m-d H:i');
        $monthEndDate = $now->endOfMonth()->format('Y-m-d H:i');

        $userData=User::select('id','name','platform','phone','city_id','email','created_at')->where('id','>=',1245)
        ->where('id','!=',6969)
        ->where('role_id' ,2)
        ->whereBetween('created_at',[$monthStartDate,$monthEndDate])
        ->with('city','healthScans')
        ->with(['userSubscription' => function($q) {
                $q->where(['status' => 1 ,'is_paid' => 1]);
        }])
        ->with(['transactionDetails' => function($query){
            $query->select('id','user_id','reference_type')->where(['reference_type' => 'one_time' ,'type' => 'debit' , 'status' => 1] )->get();
        }])
        ->get();
        $dataForGoogleSheet = [];
        $sheets = Sheets::spreadsheet(env('USER_METRICS_SHEET'))->sheet('May')->get();
        $lastRecord = count($sheets) > 5 ? count($sheets) - 5 : 0;
        $createRange = count($sheets) + 1;
        foreach ($userData as $key => $data) {
            $row['Full Name ']                    = $data->name ? $data->name : 'NULL';
            $row['Phone Number ']           = $data->phone ? $data->phone :'NULL';
            $row['Email ']            = $data->email ? $data->email : 'NULL' ;
            $row['City ']             = isset($data->city)? $data->city->name : 'NULL';
            $row['Acquisition Channel ']   =$data->platform ? $data->platform : 'NULL' ;
            $row['Date of signup/registration ']            = $data->created_at->format('d-m-y');
            $row['Subscription type']             = isset($data->userSubscription->subscription_id) ? ($data->userSubscription->subscription_id == 1 ? 'Lite' : ($data->userSubscription->subscription_id == 2 ? 'Plus' : 'Premium')) :'No Subscription';
            $row['Health scan']   = isset($data->healthScans[0]->id) ? 'Yes' : 'No';
            $row['Doctor Now']             = isset($data->transactionDetails[0]->id) ? 'Yes' : 'No';
            // fputcsv($file, $row);

            $dataForGoogleSheet[] = $row;
        }
        $sheets = Sheets::spreadsheet(env('USER_METRICS_SHEET'))->sheet('May')->range('A'.$createRange)->append($dataForGoogleSheet);


        dd($dataForGoogleSheet);
        return 0;
    }
}
