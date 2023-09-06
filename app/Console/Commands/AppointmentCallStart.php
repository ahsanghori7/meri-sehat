<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{DoctorEarning, DoctorEarningDetail, Appointment, Settings};
use Carbon\Carbon;
use App\Http\Common\Constant;
use App\Http\Common\Helper;

class AppointmentCallStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'callstart:cron';

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
        $instant_consultation_fees = Settings::where('key', 'instant_consultation_fees')->get()->pluck('value');
        $appointments=Appointment::where([
            ['type', 'instant-consultation'],
            ['status', 1],
            ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING],
            ['call_started', null],
            ['created_at', '<', Carbon::now()->subMinutes(10)->toDateTimeString()]
        ])->get();
        $percentage = 80;
        $value = ($percentage / 100) * $instant_consultation_fees[0];
        foreach($appointments as $key => $appointment){
            $getSecondAppointment = Appointment::where([
                ['type','instant-consultation'],
                ['status', 1],
                ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING],
                ['call_started', null],
                ['id', '!=', $appointment->id],
                ['doctor_id', $appointment->doctor_id],
            ])->first();
            if($getSecondAppointment){
                $appointment->progress=(new Constant)->APPOINTMENT_STATUS_CANCELLED;
                $appointment->reason="System cancelled an appointment because doctor did not start the call.";
                $appointment->save();
                Helper::cancelAppointment($appointment->id);
                $getDoctorEarning = DoctorEarning::where('doctor_id', $appointment->doctor_id)->where('status', (new Constant)->EARNING_STATUS_UNPAID)->first();
                if(!$getDoctorEarning){
                    $getDoctorEarning = DoctorEarning::create([
                        'parent_id' => 0,
                        'doctor_id' => $appointment->doctor_id, 
                        'total_payable' => 0, 
                        'remaining_payable' => 0, 
                        'income' => 0, 
                        'deduction' => 0, 
                        'status' => (new Constant)->EARNING_STATUS_UNPAID
                    ]);
                }
                DoctorEarningDetail::create([
                    'doctor_earning_id' => $getDoctorEarning->id,
                    'appointment_id' => $appointment->id,
                    'status' => (new Constant)->APPOINTMENT_STATUS_CANCELLED
                ]);
            }else{
                $getLastAppointment = Appointment::where([
                    ['type','instant-consultation'],
                    ['status', 1],
                    ['doctor_id', $appointment->doctor_id],
                    ['updated_at', '<', Carbon::now()->subMinutes(10)->toDateTimeString()]
                ])->first();
                if($getLastAppointment){
                    $appointment->progress=(new Constant)->APPOINTMENT_STATUS_CANCELLED;
                    $appointment->reason="System cancelled an appointment because doctor did not start the call.";
                    $appointment->save();
                    Helper::cancelAppointment($appointment->id);
                    $getDoctorEarning = DoctorEarning::where('doctor_id', $appointment->doctor_id)->where('status', (new Constant)->EARNING_STATUS_UNPAID)->first();
                    if(!$getDoctorEarning){
                        $getDoctorEarning = DoctorEarning::create([
                            'parent_id' => 0,
                            'doctor_id' => $appointment->doctor_id, 
                            'total_payable' => 0, 
                            'remaining_payable' => 0, 
                            'income' => 0, 
                            'deduction' => 0, 
                            'status' => (new Constant)->EARNING_STATUS_UNPAID
                        ]);
                    }
                    DoctorEarningDetail::create([
                        'doctor_earning_id' => $getDoctorEarning->id,
                        'appointment_id' => $appointment->id,
                        'status' => (new Constant)->APPOINTMENT_STATUS_CANCELLED
                    ]);
                }else{
                    $checkAppointment = Appointment::where([
                        ['type','instant-consultation'],
                        ['status', 1],
                        ['doctor_id', $appointment->doctor_id]
                    ])->count();
                    if($checkAppointment == 1){
                        $appointment->progress=(new Constant)->APPOINTMENT_STATUS_CANCELLED;
                        $appointment->reason="System cancelled an appointment because doctor did not start the call.";
                        $appointment->save();
                        Helper::cancelAppointment($appointment->id);

                        $getDoctorEarning = DoctorEarning::where('doctor_id', $appointment->doctor_id)->where('status', (new Constant)->EARNING_STATUS_UNPAID)->first();
                        if(!$getDoctorEarning){
                            $getDoctorEarning = DoctorEarning::create([
                                'parent_id' => 0,
                                'doctor_id' => $appointment->doctor_id, 
                                'total_payable' => 0, 
                                'remaining_payable' => 0, 
                                'income' => 0, 
                                'deduction' => 0, 
                                'status' => (new Constant)->EARNING_STATUS_UNPAID
                            ]);
                        }
                        DoctorEarningDetail::create([
                            'doctor_earning_id' => $getDoctorEarning->id,
                            'appointment_id' => $appointment->id,
                            'status' => (new Constant)->APPOINTMENT_STATUS_CANCELLED
                        ]);
                    }
                }
            }
        }
    }
}
