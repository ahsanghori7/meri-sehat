<?php

namespace App\Models;

use App\Http\Common\Constant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class DoctorClinic extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection= 'mysql';
    protected $table = 'doctor_clinics';

    protected $guarded = ['id'];
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function clinic(){
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function clinicTimings()
    {
        return $this->hasMany(ClinicTiming::class)->orderBy('start_time', 'asc');
    }

    /**
     * This method is used to break the doctor clinic's time slots
     */
    public static function doctorClinicTimings($doctorClinic, $date)
    {
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $day = $days[date('w', strtotime($date))]; // Get the day of the week
        $getDateTime = Carbon::now();
        $getClinicTimings = ClinicTiming::where(['doctor_clinic_id' => $doctorClinic->id, 'day' => $day, 'status' => true])
        ->where(function($query) use($getDateTime, $date){
            if($date == $getDateTime->toDateString()){
                $query->where('start_time', '>', $getDateTime->toTimeString());
            }
        })
        ->orderBy('start_time', 'ASC')->get();
        $physicalResponse = [];
        $onlineResponse = [];
        foreach($getClinicTimings as $getTiming){
            $getAppointment = Appointment::where([
                'status' => true,
                'progress' => (new Constant)->APPOINTMENT_STATUS_PENDING,
                'date' => $date,
                'time' => $getTiming->start_time,
                'doctor_id' => $doctorClinic->doctor_id,
                'doctor_clinic_id' => $doctorClinic->id,
            ])
            ->first();
            if($getAppointment){
                continue;
            }
            $response = [
                'id' => $getTiming->id,
                'doctor_clinic_id' => $doctorClinic->id,
                'doctor_id' => $doctorClinic->doctor_id,
                'clinic_id' => $doctorClinic->clinic_id,
                'consultation_fee' => $doctorClinic->consultation_fee,
                'consultation_duration' => $doctorClinic->consultation_duration . ' minutes',
                'is_physical' => $getTiming->is_physical,
                'day' => ucfirst($getTiming->day),
                'start_time' => date('h:i A', strtotime($getTiming->start_time)),
                'end_time' => date('h:i A', strtotime($getTiming->end_time)),
                'status' => $getTiming->status,
            ];
            if($getTiming->is_physical == true){
                $physicalResponse[] = $response;
            }else{
                $onlineResponse[] = $response;
            }
        }
        return [
            'physicalTimeSlots' => $physicalResponse,
            'onlineTimeSlots' => $onlineResponse,
        ];
    }


    public static   function data_uniquely($key, $array) {
        $_array = array();
        foreach ($array as $v) {
            if (isset($_array[$v[$key]])){
                $_array[$v[$key]]['day'] = $v['day'];
                $_array[$v[$key]]['data'][] = self::getClinicTimingStandard($v);

                continue;
            }

            $_array[$v[$key]]['day'] = $v['day'];
            $_array[$v[$key]]['data'][] = self::getClinicTimingStandard($v);
        }
        $array = array_values($_array);
        return $array;
    }
    /**
     * This method is used to get the time slots w.r.t to days wise timeslots
     */
    public static function getClinicWithTimeSlots($doctorId, $is_physical = false)
    {
        $clinicData = [];
//        $doctorClinics = DoctorClinic::where(['doctor_id' => $doctorId])->get();


        DB::select('SET SESSION group_concat_max_len = 20000000');

        $query = 'SELECT c.id,c.`name`,c.address,c.lat,c.long,c.phone,c.phone_secondary,c.email,c.`status` ,dc.id,dc.`status`, dc.`clinic_id`, dc.`consultation_duration`, dc.`consultation_fee`, c.`name` , CONCAT("[" , GROUP_CONCAT(DISTINCT CONCAT(\'{"id":"\', tg.`id`, \'", "doctor_clinic_id":"\',tg.`doctor_clinic_id`,\'" , "day" : "\',tg.day,\'", "start_time" : "\',tg.`start_time`,\'" , "end_time" : "\',tg.`end_time`,\'" , "is_physical" : "\',tg.`is_physical`,\'", "status" : "\',tg.`status`,\'" }\')  SEPARATOR \',\') , "]") AS gday
FROM doctor_clinics dc
LEFT JOIN clinic_timings tg ON tg.`doctor_clinic_id` = dc.`id`
LEFT JOIN clinics c ON dc.`clinic_id` = c.`id`
 WHERE dc.doctor_id = '.$doctorId.' AND dc.`deleted_at` IS NULL
 GROUP BY c.`name` limit 10';

        $doctorClinics = DB::select($query);

        foreach ($doctorClinics as $key =>$clinics) {
            $clinicData[$key]['id'] = $clinics->id;
            $clinicData[$key]['consultation_fee'] = $clinics->consultation_fee;
            $clinicData[$key]['consultation_duration'] = $clinics->consultation_duration;
            $clinicData[$key]['status'] = $clinics->status;

            $clinicData[$key]['clinic']['id'] = $clinics->clinic_id;
            $clinicData[$key]['clinic']['name'] = $clinics->name;
            $clinicData[$key]['clinic']['address'] = $clinics->address;
            $clinicData[$key]['clinic']['lat'] = $clinics->lat;
            $clinicData[$key]['clinic']['long'] = $clinics->long;
            $clinicData[$key]['clinic']['phone'] = $clinics->phone;
            $clinicData[$key]['clinic']['phone_secondary'] = $clinics->phone_secondary;
            $clinicData[$key]['clinic']['email'] = $clinics->email;
            $clinicData[$key]['clinic']['status'] = $clinics->status;

            if ($clinics->gday) {
                $dateData = [];

                $date = json_decode($clinics->gday , true);

                $clinicData[$key]['time_slots'] = self::data_uniquely('day' , $date);
                    //   dd(self::data_uniquely('day' , $date));

                $clinicDatad = [];
//                foreach ($date as $dateKey => $fdate) {
//                    dd($fdate);
//                    $strf = explode("|", $fdate);
//                    $day = trim($strf[0]);
//                    $time = array_splice($strf, 1);
//                    $timeslots['id'] = $time[2];
//                    $timeslots['doctor_clinic_id'] = $time[3];
//                    $timeslots['day'] = $time[4];
//                    $timeslots['start_time'] = $time[1];
//                    $timeslots['end_time'] = $time[2];
//                    $timeslots['is_physical'] = $time[5];
//                    $timeslots['status'] = $time[6];
//                    $clinicDatad[$dateKey][$day]['data'] = self::getClinicTimingStandard($timeslots);
//                }
//                if($key == 3) {
//                    dd($clinicDatad);
//
//                    dd($clinicDatad);
//                }

            }
        }
        return $clinicData;
//
//        if(count($doctorClinics)){
//            $clinicDataIndex = 0;
//            foreach ($doctorClinics as $doctorClinic) {
//                $clinicData[$clinicDataIndex]['id'] = $doctorClinic->id;
//                $clinicData[$clinicDataIndex]['consultation_fee'] = $doctorClinic->consultation_fee;
//                $clinicData[$clinicDataIndex]['consultation_duration'] = $doctorClinic->consultation_duration;
//                $clinicData[$clinicDataIndex]['status'] = $doctorClinic->status;
//                $clinicData[$clinicDataIndex]['clinic'] = $doctorClinic->clinic;
//                $clinicTimingData = [];
//            }
////            foreach($doctorClinics as $doctorClinic){
////
////                $clinicData[$clinicDataIndex]['id'] = $doctorClinic->id;
////                $clinicData[$clinicDataIndex]['consultation_fee'] = $doctorClinic->consultation_fee;
////                $clinicData[$clinicDataIndex]['consultation_duration'] = $doctorClinic->consultation_duration;
////                $clinicData[$clinicDataIndex]['status'] = $doctorClinic->status;
////                $clinicData[$clinicDataIndex]['clinic'] = $doctorClinic->clinic;
////
////                $clinicTimingData = [];
////                if(count($doctorClinic->clinicTimings)){
////                    $clinicTimingIndex = 0;
////                    foreach($doctorClinic->clinicTimings as $clinicTiming){
////                        // if($is_physical == true){
////                            $day = $clinicTiming['day'];
////                            $ifExists = 0;
////                            for($i = 0; $i < count($clinicTimingData); $i++){
////                                if($clinicTimingData[$i]['day'] == $day){
////                                    $clinicTimingData[$i]['data'][] = self::getClinicTimingStandard($clinicTiming);
////                                    $ifExists++;
////                                    break;
////                                }
////                            }
////                            if($ifExists > 0){
////                                continue;
////                            }
////                            $clinicTimingData[$clinicTimingIndex]['day'] = $day;
////                            $clinicTimingData[$clinicTimingIndex]['data'][] = self::getClinicTimingStandard($clinicTiming);
////                            $clinicTimingIndex++;
////                        // }
////                    }
////                }
////                $clinicData[$clinicDataIndex]['time_slots'] = $clinicTimingData;
////                $clinicDataIndex++;
////            }
//        }
//        return $clinicData;
    }
    private static function getClinicTimingStandard($clinicTiming)
    {
        $clinicTiming = (object) $clinicTiming;

        return [
            'id' => $clinicTiming->id,
            'doctor_clinic_id' => $clinicTiming->doctor_clinic_id,
            'day' => $clinicTiming->day,
            'start_time' => date('h:i A', strtotime($clinicTiming->start_time)),
            'end_time' => date('h:i A', strtotime($clinicTiming->end_time)),
            'is_physical' => $clinicTiming->is_physical,
            'status' => $clinicTiming->status,
        ];
    }
}
