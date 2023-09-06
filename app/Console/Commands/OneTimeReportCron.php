<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Sheets;

class OneTimeReportCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:onetime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Report of Onetime payment and update on google sheet and mail the sheet';

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
        $transactions = Transaction::with('user')
        ->where('reference_type', 'one_time')
        ->where('status', 1)
        ->whereDate('created_at', Carbon::yesterday())
        ->get();

        $fileName = date('ymd-His').'.csv';

        $columnNames = [
            'So.No', 'Name', 'Email Address', 'Phone Number', 'Amount Paid', 'Amount Paid on (Date)',
            'Billing City', 'Call Status', 'Followup call made on', 'Remarks, queries and comments by the customer'
        ];

        $file = fopen(public_path($fileName), 'w');
        fputcsv($file, $columnNames);
        $dataForGoogleSheet = [];
        $sheets = Sheets::spreadsheet(env('OTP_SHEET_ID'))->sheet('One-time paid customers')->get();
        // dd(count($sheets));
        $lastRecord = count($sheets) > 5 ? count($sheets) - 5 : 0;
        $createRange = count($sheets) + 1;
        foreach ($transactions as $key => $data) {
            $row['So.No']                   = $lastRecord + ($key + 1);
            $row['Name']                    = $data->user->name;
            $row['Email Address']           = $data->user->email;
            $row['Phone Number']            = $data->user->phone;
            $row['Amount Paid']             = $data->amount;
            $row['Amount Paid on (Date)']   = $data->created_at->format('Y-m-d');
            $row['Billing City']            = isset($data->user->city->name) ? $data->user->city->name : "--";
            $row['Call Status']             = '';
            $row['Followup call made on']   = '';
            $row['Remarks, queries and comments by the customer']             = '';
            fputcsv($file, $row);
            $dataForGoogleSheet[] = $row;
        }

        fclose($file);
        $sheets = Sheets::spreadsheet(env('OTP_SHEET_ID'))->sheet('One-time paid customers')->range('A'.$createRange)->append($dataForGoogleSheet);
        if(env('OTP_REPORT_SEND_EMAIL') !== null && env('OTP_REPORT_SEND_EMAIL') !== ''){
            $emails = explode(',', env('OTP_REPORT_SEND_EMAIL'));
        }else{
            $emails = ['oneeb@merisehat.pk'];
        }
        
        foreach($emails as $email){
            Mail::send('email_templates.release_1.one_time_payment_report', [], function($message) use ($fileName, $email)
            {
                $message->to($email)->subject('Daily Onetime Report CSV');
                $message->attach(public_path($fileName));
            });
        }
        
        if (File::exists(public_path($fileName))) {
            File::delete(public_path($fileName));
        }
    }
}