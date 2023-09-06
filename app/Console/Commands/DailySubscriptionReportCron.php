<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Sheets;

class DailySubscriptionReportCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DailySubscriptionReportCron';

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

        $getYesterDaySubscriptions = UserSubscription::with(['user.city', 'package'])
                                        ->whereHas('transaction')
                                        ->where('is_test', 0)->where('status', 1)->where('is_paid', 1)
                                        ->whereDate('created_at', Carbon::yesterday())->get();
        $fileName = date('ymd-His').'.csv';
        
        $columnNames = ['So.No', 'Name', 'Email', 'Phone Number', 'Subscription Type', 'Subscription Amount', 'Subscription Start Date', 'Billing City', 'Subscription Status', 'Call Status Deatil', 'Assigned', 'Call Status', 'Time and Date'];
        
        $file = fopen(public_path($fileName), 'w');
        fputcsv($file, $columnNames);
        $dataForGoogleSheet = [];
        $sheets = Sheets::spreadsheet(env('SPREADSHEET_ID'))->sheet('Sheet1')->get();
        $lastRecord = count($sheets) > 5 ? count($sheets) - 5 : 0;
        $createRange = count($sheets) + 1;
        foreach ($getYesterDaySubscriptions as $key => $data) {
            $row['So.No']                   = $lastRecord + ($key + 1);
            $row['Name']                    = $data->user->name;
            $row['Email']                   = $data->user->email;
            $row['Phone Number']            = $data->user->phone;
            $row['Subscription Type']       = $data->package->name;
            $row['Subscription Amount']     = isset($data->transaction) ? $data->transaction->amount : '';
            $row['Subscription Start Date'] = $data->created_at->format('M');
            $row['Billing City']            = isset($data->user->city->name) ? $data->user->city->name : "--";
            $row['Subscription Status']     = $data->status ? 'Active' : 'Pending';
            $row['Call Status Deatil']      = '';
            $row['Assigned']                = '';
            $row['Call Status']             = '';
            $row['Time and Date']           = $data->created_at->format('d/m/y h:i a');
            fputcsv($file, $row);
            $dataForGoogleSheet[] = $row;
        }

        fclose($file);
        $sheets = Sheets::spreadsheet(env('SPREADSHEET_ID'))->sheet('Sheet1')->range('A'.$createRange)->append($dataForGoogleSheet);
        if(env('DAILY_SUBSCRIPTION_REPORT_SEND_EMAIL') !== null && env('DAILY_SUBSCRIPTION_REPORT_SEND_EMAIL') !== ''){
            $emails = explode(',', env('DAILY_SUBSCRIPTION_REPORT_SEND_EMAIL'));
        }else{
            $emails = ['oneeb@merisehat.pk'];
        }
        
        foreach($emails as $email){
            Mail::send('email_templates.release_1.send_daily_subscription_report', [], function($message) use ($fileName, $email)
            {
                $message->to($email)->subject('CSV email');
                $message->attach(public_path($fileName));
            });
        }
        
        if (File::exists(public_path($fileName))) {
            File::delete(public_path($fileName));
        }
    }
}
