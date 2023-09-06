<?php   namespace App\Console\Commands;

use App\Models\UserSubscription;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Sheets;

class SubscriptionSheetTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SubscriptionSheetTransaction {transaction }';

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
        $transaction = $this->argument('transaction');
        if($transaction){
            $transactionId = explode(',', $transaction);
            if($transactionId){
                $getYesterDaySubscriptions = UserSubscription::with(['user.city', 'package'])
                                            ->whereHas('transaction', function($query) use ($transactionId){
                                                $query->whereIn('id', $transactionId);
                                            })
                                            ->where('is_test', 0)->where('status', 1)->where('is_paid', 1)
                                            ->get();
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
                    $dataForGoogleSheet[] = $row;
                }
                $sheets = Sheets::spreadsheet(env('SPREADSHEET_ID'))->sheet('Sheet1')->range('A'.$createRange)->append($dataForGoogleSheet); 
            }
        } 
        
    }
}
