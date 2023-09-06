<?php

namespace App\Console\Commands;

use App\Models\{User,HealthScan,Transaction,Appointment,
    Review,Prescription,DoctorReview,UserSubscription};
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Sheets;
use DB;



class AutomateMetricSheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automate:metric';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Report of Automate metrics sheets and update on google sheet and mail the sheet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getAlphabets(){
        return [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q'
        ];
    }


    protected function sendEmail($data){
        $fileName = now()->format('Ymd_His').'.csv';
        $columnNames = [
            'Number of New Paid Subscribers', 'Number of total Subscribers', 'Number of recurring Paid subscribers', 'Number of transactions on web', 'Number of transactions on app',
            'Total number of Sehat Scans (web)','Total number of Sehat Scans (app)', 'Number of New Scans (web)', 'Number of New Scans (app)', 'Number of Sehat Scan per user (Lifetime) ',
            'Number of Sehat Scan per user (Average Daily)','Total Earning of Doctors from Doctor Now','Average duration for a consultation ','Number of consultation with a rating of 4 star or above',
            'Number of consultations with a rating below 4','Medicines per prescriptions','Total Number of prescription written','Average wait time for patient to connect with doctor','Number of consultations',
            'Total number of completed consultations','Number of incompleted consultations','Number of reviews on Doctors profile','Number of reviews with a rating of 4.0 or higher on doctor profile',
            'Number of reviews with a rating lower than 4 on doctor profile','Revenue Generated (Sum of Doctor Earning and Paid Subscription)'
        ];
        $file = fopen(public_path($fileName), 'w');
        fputcsv($file, $columnNames);

        fputcsv($file,$data);
        fclose($file);
        if(env('AUTOMATE_METRICS_EMAIL') !== null && env('AUTOMATE_METRICS_EMAIL') !== ''){
            $emails = explode(',', env('AUTOMATE_METRICS_EMAIL'));
        }else{
            $emails = ['oneeb@merisehat.pk'];
        }
        foreach($emails as $email){
            Mail::send('email_templates.release_1.automate_metrics_weekly_report', [], function($message) use ($fileName, $email)
            {
                $message->to($email)->subject('Weekly metrics');
                $message->attach(public_path($fileName));
            });
        }
        if (File::exists(public_path($fileName))) {
            File::delete(public_path($fileName));
        }
        return true;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');

        $newPaidSubscriber=DB::select(DB::raw('SELECT COUNT(*) AS NewPaidSubscriber
        FROM (
        SELECT user_id, COUNT(*) AS COUNT, MIN(created_at) AS first_occurrence
        FROM transactions
        WHERE user_id >= 1245 AND user_id != 6969 AND STATUS = 1 AND reference_type = "subscription" AND TYPE = "debit" AND created_at BETWEEN :weekStartDate AND :weekEndDate
        GROUP BY user_id
        HAVING COUNT = 1
        AND NOT EXISTS (
            SELECT 1
            FROM (
                SELECT user_id, MIN(created_at) AS first_occurrence
                FROM transactions
                WHERE user_id >= 1245 AND user_id != 6969 AND STATUS = 1 AND reference_type = "subscription" AND TYPE = "debit"
                GROUP BY user_id
            ) AS subquery
            WHERE user_id = transactions.user_id AND first_occurrence < MIN(transactions.created_at)
        ))AS subs'),['weekStartDate' => $weekStartDate,'weekEndDate' => $weekEndDate]);


        $TotalPaidSubscriber=DB::select(DB::raw('SELECT COUNT(*) AS TotalPaidSubscriber
        FROM (
        SELECT user_id, COUNT(*) AS COUNT
        FROM transactions
        WHERE user_id >= 1245 AND user_id !=6969  AND status=1 and reference_type="subscription" AND TYPE="debit" AND created_at BETWEEN :weekStartDate AND :weekEndDate
        GROUP BY user_id
        HAVING COUNT = 1) AS subquery'),['weekStartDate' => $weekStartDate,'weekEndDate' => $weekEndDate]);


        $totalRecurringSubscriber=DB::select(DB::raw('SELECT COUNT(*) AS total_count
                FROM (
                SELECT user_id, COUNT(*) AS COUNT, MIN(created_at) AS first_occurrence
                FROM transactions
                WHERE user_id >= 1245 AND user_id != 6969 AND STATUS = 1 AND reference_type = "subscription" AND TYPE = "debit" AND created_at BETWEEN :weekStartDate AND :weekEndDate
                GROUP BY user_id
                HAVING COUNT >= 1
                AND EXISTS (
                    SELECT 1
                    FROM (
                        SELECT user_id, MIN(created_at) AS first_occurrence
                        FROM transactions
                        WHERE user_id >= 1245 AND user_id != 6969 AND STATUS = 1 AND reference_type = "subscription" AND TYPE = "debit"
                        GROUP BY user_id
                    ) AS subquery
                    WHERE user_id = transactions.user_id AND first_occurrence < MIN(transactions.created_at)
                ))AS subs'),['weekStartDate' => $weekStartDate,'weekEndDate' => $weekEndDate]);


        $noOfSehatScanPerUserLifetime = DB::select(DB::raw('SELECT COUNT(*) / COUNT(DISTINCT user_id) as lifetime FROM health_scans
        WHERE user_id >= 1245 AND user_id !=6969
        ' ));


        $noOfSehatScanPerUserWeekly=DB::SELECT(DB::raw('SELECT WEEK(DATE) AS dateavg, AVG(occurance) AS average_occurance
        FROM (
            SELECT DATE(created_at) AS DATE, user_id, COUNT(*) AS occurance
            FROM health_scans
            WHERE user_id >= 1245 AND user_id !=6969 AND created_at BETWEEN :weekStartDate AND :weekEndDate
            GROUP BY DATE, user_id
        ) AS subquery
        GROUP BY dateavg'),['weekStartDate' => $weekStartDate,'weekEndDate' => $weekEndDate]);


        $totalHealthScanApp=HealthScan::where('user_id','>=',1245)
        ->where('user_id','!=',6969)
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count();

        $totalHealthScanWeb=HealthScan::where('user_id','>=',1245)
        ->where('user_id','!=',6969)
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->where('user_agent','web')
        ->count();

        $totalNewHealthScanApp=DB::select(DB::raw('SELECT COUNT(*) as new_scan FROM health_scans hs INNER JOIN users u ON u.id = hs.`user_id`  WHERE u.created_at BETWEEN :weekStartDate AND :weekEndDate
        AND user_id >=1245
          AND user_id !=6969'),['weekStartDate' => $weekStartDate,'weekEndDate' => $weekEndDate]);

        $totalNewHealthScanWeb=HealthScan::where('user_id','>=',1245)
        ->where('user_id','!=',6969)
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->where('user_agent','web')
        ->count();

        $totalEarningDoctorNow=Transaction::where('reference_type','one_time')
        ->where('user_id' ,'>=' ,1245)
        ->where('user_id','!=','6969')
        ->where('type','debit')
        ->where('status','1')
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count();

        $averageConsultation=Appointment::select(
            DB::raw('AVG(TIMESTAMPDIFF(MINUTE, call_started, call_ended)) as avg_consultation')
            )->where('user_id' ,'>=' ,1245)
            ->where('user_id','!=','6969')
            ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
            ->value('avg_consultation');

        $averageWaitingTime=Appointment::select(
            DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, call_started)) as avg_waiting_time')
            )->where('user_id' ,'>=' ,1245)
            ->where('user_id','!=','6969')
            ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
            ->value('avg_waiting_time');

        $review4StarAbove=Review::where('rating','>=',4)
        ->where('user_id' ,'>=' ,1245)
        ->where('user_id','!=','6969')
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count();

        $reviewBelow4=Review::where('rating','<',4)
        ->where('user_id' ,'>=' ,1245)
        ->where('user_id','!=','6969')
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count();

        $medPerPrescription= DB::select(DB::raw('SELECT COUNT(prescribed_elements.id) as total_selected_presricption FROM appointments hs INNER JOIN prescriptions p ON hs.`id`=p.appointment_id
            JOIN prescribed_elements
            ON prescribed_elements.`prescription_id` = p.`id`
            WHERE hs.created_at BETWEEN :weekStartDate AND :weekEndDate
            AND hs.user_id >=1245
            AND hs.user_id !=6969'),['weekStartDate' => $weekStartDate,'weekEndDate' => $weekEndDate]);

        $totalNoPrescriptionWritten=Prescription::whereHas('appointment' ,function($query) use ($weekStartDate,$weekEndDate){
            $query->where('user_id' ,'>=' ,1245)
            ->where('user_id','!=','6969')
            ->whereBetween('created_at',[$weekStartDate,$weekEndDate]);
        })->count();

        $totalNoConsult=Appointment::where('user_id' ,'>=' ,1245)
        ->where('user_id','!=','6969')->whereBetween('created_at',[$weekStartDate,$weekEndDate])->count();

        $totalNoCompleteContultation=Appointment::where('user_id' ,'>=' ,1245)
        ->where('user_id','!=','6969')
        ->where('progress','completed')
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count();

        $totalNoIncompleteContultation=Appointment::where('user_id' ,'>=' ,1245)
        ->where('user_id','!=','6969')
        ->where('progress','cancel')
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count();
// dd($totalNoIncompleteContultation);

        $totalNODoctorReviewProfile=DoctorReview::where('user_id','!=','6969')
        ->where('user_id','>=' ,1245)
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count('doctor_id');

        $totalNoDoctorReviews4above=DoctorReview::where('rating','>=',4)
        ->where('user_id','!=','6969')
        ->where('user_id','>=' ,1245)
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count();

        $totalNoDoctorReviewsBelow4=DoctorReview::where('rating','<',4)
        ->where('user_id','!=','6969')
        ->where('user_id','>=' ,1245)
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count();

        $amountSum=Transaction::where('user_id' ,'>=' ,1245)
        ->where('user_id','!=','6969')
        ->where('type','debit')
        ->where('status','1')
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->sum('amount');


        $weeklyTransactions=Transaction::where('user_id' ,'>=' ,1245)
        ->where('user_id','!=','6969')
        ->where('type','debit')
        ->where('status','1')
        ->whereBetween('created_at',[$weekStartDate,$weekEndDate])
        ->count();

        $temp=$noOfSehatScanPerUserLifetime[0]->lifetime;
        $dataForGoogleSheet = [];
        $dataForGoogleSheet =[
        array($newPaidSubscriber[0]->NewPaidSubscriber),
        array($TotalPaidSubscriber[0]->TotalPaidSubscriber),
        array($totalRecurringSubscriber[0]->total_count),
        array($weeklyTransactions),
        array(""),
        array(""),
        array($totalHealthScanWeb),
        array($totalHealthScanApp),
        array($totalNewHealthScanWeb),
        array($totalNewHealthScanApp[0]->new_scan),
        array($temp),
        array(isset($noOfSehatScanPerUserWeekly[0]->average_occurance) ? $noOfSehatScanPerUserWeekly[0]->average_occurance : 0),
        array(""),
        array($totalEarningDoctorNow),
        array($averageConsultation),
        array($review4StarAbove),
        array($reviewBelow4),
        array($medPerPrescription[0]->total_selected_presricption),
        array($totalNoPrescriptionWritten),
        array($averageWaitingTime),
        array($totalNoConsult),
        array($totalNoCompleteContultation),
        array($totalNoIncompleteContultation),
        array($totalNODoctorReviewProfile),
        array($totalNoDoctorReviews4above),
        array($totalNoDoctorReviewsBelow4),
        array(""),
        array($amountSum)
    ];
    // dd($dataForGoogleSheet);
    $dataForEmail =[
        $newPaidSubscriber[0]->NewPaidSubscriber,
        $TotalPaidSubscriber[0]->TotalPaidSubscriber,
        $totalRecurringSubscriber[0]->total_count,
        $weeklyTransactions,
        $totalHealthScanWeb,
        $totalHealthScanApp,
        $totalNewHealthScanWeb,
        $totalNewHealthScanApp[0]->new_scan,
        $temp,
        isset($noOfSehatScanPerUserWeekly[0]->average_occurance) ? $noOfSehatScanPerUserWeekly[0]->average_occurance :0,
        $totalEarningDoctorNow,
        $averageConsultation,
        $review4StarAbove,
        $reviewBelow4,
        $medPerPrescription[0]->total_selected_presricption,
        $totalNoPrescriptionWritten,
        $averageWaitingTime,
        $totalNoConsult,
        $totalNoCompleteContultation,
        $totalNoIncompleteContultation,
        $totalNODoctorReviewProfile,
        $totalNoDoctorReviews4above,
        $totalNoDoctorReviewsBelow4,
        $amountSum
    ];

    $sheets = Sheets::spreadsheet(env('AUTOMATE_METRICS_SHEET'))->sheet('Sheet1')->get();
    $header = $sheets->pull(2);
    $week = Carbon::now()->weekOfMonth;

    $alphabet = $this->getAlphabets();

    $sheets = Sheets::spreadsheet(env('AUTOMATE_METRICS_SHEET'))->sheet('Sheet1')->range($alphabet[count($header)].'3')->append($dataForGoogleSheet);

    return $this->sendEmail($dataForEmail);
    }
}
