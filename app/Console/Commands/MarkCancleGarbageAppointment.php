<?php   namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Appointment, DoctorDetail, PreAppointment, DoctorEarning, DoctorEarningDetail};
use Illuminate\Support\Carbon;
use App\Http\Common\Constant;
use App\Http\Common\Helper;

class MarkCancleGarbageAppointment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancle:garbage_appointment';

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
        $doctors = [];
        $result = [];
        
        $getAppointments = Appointment::whereDate('date', Carbon::today()->format('Y-m-d'))->where([
            ['time', '<=', Carbon::now()->addMinutes(Constant::APPOINTMENT_INSTANT_TIME)->format('H:i:m')],
            ['status', 1],
            ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING],
            ['type', 'instant-consultation']
            ])->get();

            
        foreach($getAppointments as $key => $appointment){
            Appointment::find($appointment->id)->update(
                    [
                        'progress' => (new Constant)->APPOINTMENT_STATUS_CANCELLED,
                        'reason' => 'Cancled by system due to expire time.'
                    ]
                );
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
        $getDoctors = DoctorDetail::where(['is_instant_consultation' => 1])
        ->with(['user' => function ($query) {
            $query->where('role_id', 3)->select('id', 'name');
        }])
        ->select(['id','doctor_id','current_appointment','last_call_at'])->get();

        if($getDoctors){     
            foreach ($getDoctors as $key => $doctor){
                if(!isset($doctor->user->name)){
                    continue;
                }
                $getDoctorAppointment = Appointment::where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->where('doctor_id', $doctor->doctor_id)->count();
                if($getDoctorAppointment >= 2){
                    continue;
                }
                $doctors[] = [
                    'id' => $doctor->doctor_id,
                    'title' => $doctor->user->name,
                    'lastCall' => $doctor->last_call_at,
                    'appointmentId' => $doctor->current_appointment,
                ];
                if(!isset($doctor->current_appointment) or empty($doctor->current_appointment) )
                {
                    $pre_appointment = PreAppointment::orderBy('id', 'ASC')->limit(1)->first();
                    if($pre_appointment){
                        Appointment::create([
                            "booked_via_subscription" => $pre_appointment->booked_via_subscription ?? null,
                            "user_id" => $pre_appointment->user_id,
                            "doctor_id" => $pre_appointment->doctor_id,
                            "doctor_clinic_id" => $pre_appointment->doctor_clinic_id,
                            "family_member_id" => $pre_appointment->family_member_id,
                            "consultation_fee" => $pre_appointment->consultation_fee,
                            "reason" => $pre_appointment->reason,
                            "type" => $pre_appointment->type,
                            "priority" => $pre_appointment->priority,
                            "date" => $pre_appointment->date,
                            "time" => $pre_appointment->time,
                            "progress" => $pre_appointment->progress,
                            "agora_link" => $pre_appointment->agora_link,
                            "action_by" => $pre_appointment->action_by,
                            "is_paid" => $pre_appointment->is_paid,
                            "is_notified" => $pre_appointment->is_notified,
                            "status" => $pre_appointment->status,
                            "ms_commission" => $pre_appointment->ms_commission,
                            "ms_commission_is_percentage" => $pre_appointment->ms_commission_is_percentage,
                            "doctor_total" => $pre_appointment->doctor_total,
                            "ms_total" => $pre_appointment->ms_total,
                            "grand_total" => $pre_appointment->grand_total,
                            "is_doctor_connected" => $pre_appointment->is_doctor_connected
                        ]);
                        $pre_appointment->delete();
                        $this->info('Doctor Id: '. $doctor->doctor_id);
                    }
                }
            }
        }
        
        $this->info($getAppointments->count() . 'appointment cancle. id: ' . json_encode($getAppointments));
    }
}
