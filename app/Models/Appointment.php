<?php

namespace App\Models;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\Models\PatientInfo;

class Appointment extends Model
{
    use HasFactory;
    protected $connection= 'mysql';

    protected $guarded = ['id'];
    protected $hidden = [
        'updated_at'
    ];
    protected $casts = [
        'status' => 'boolean',
        'is_paid' => 'boolean',
    ];
    protected $appends = [
        'days_left',
        'seconds_left',
        'e_id',
        'patient_name',
        'patient_detail',
        'agent_check',
        'device_check',
        'appointment_date',
        'appointment_time',
        'payment_type',
        'cancellation_fee',
        'amount',
        'platform_fee',
        'type_text'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
            'email' => 'required',
        ];
    }

    public function getAgentCheckAttribute(){
        $agent = new Agent();
        $agent->setUserAgent($this->user_agent);
        $platform = $agent->platform();
        $platform_ver = $agent->version($platform);
        $browser = $agent->browser();
        $browser_ver = $agent->version($browser);
        $device = $agent->device();
        $device_type = $agent->deviceType();
        if ($agent->isPhone()) {
            return $browser . ' ' . $browser_ver . ' @ ' . $platform . ' ' . $platform_ver;
        } else {
            return $browser . ' ' . $browser_ver . ' @ ' . $platform . ' ' . $platform_ver;
        }
    }

    public function getDeviceCheckAttribute(){
        if (Str::contains($this->user_agent, 'dart')) {
            return 'mobile';
        } else {
            return 'web';
        }
    }

    public function getAppointmentDateAttribute(){
        return Carbon::parse($this->created_at)->format('y-m-d');
    }

    public function getAppointmentTimeAttribute(){
        return Carbon::parse($this->created_at)->format('h:i:s');
    }
    
    public function getPatientNameAttribute(){
        $name = isset($this->user->name) ? $this->user->name : 'MS-Patient';
        $patientInfo = PatientInfo::where('appointment_id', $this->id)->first();
        if($patientInfo){
            $name = $patientInfo->name;
        }
        return $name;
    }

    public function getTypeTextAttribute()
    {
        $type = $this->type;
        if($type==Constant::APPOINTMENT_TYPE_INSTANT){
            $type = "Instant Video Call";
            return $type;
        }
        return $type;
    }

    public function getPatientDetailAttribute(){
        $patientInfo = PatientInfo::where('appointment_id', $this->id)->first();
        if(!$patientInfo){
            return null;

        }
        return $patientInfo;
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patientInfo()
    {
        return $this->hasOne(PatientInfo::class, 'appointment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctorClinic()
    {
        return $this->belongsTo(DoctorClinic::class, 'doctor_clinic_id');
    }

    public function member()
    {
        return $this->belongsTo(UserFamilyMember::class, 'family_member_id');
    }

    public function getPrescription()
    {
        return $this->hasOne(Prescription::class, 'appointment_id')->orderBy('updated_at', 'ASC')->with('prescribedMedicine', 'prescribedLab', 'prescribedPrescription');
    }

    public function getCondition()
    {
        return $this->hasMany(AppointmentCondition::class,'appointment_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class,'appointment_id');
    }

    public function questionaire_data()
    {
        return $this->hasMany(QuestionaireFormData::class,'appointment_id');
    }

    public function patient()
    {
        if($this->family_member_id){
            return $this->belongsTo(UserFamilyMember::class, 'family_member_id');
        }else{
            return $this->belongsTo(User::class, 'user_id');
        }
    }

    public function transactionDetails()
    {
        return $this->hasOne(Transaction::class, 'reference_id')->where('reference_type', 'appointment');
    }

    public function getDateAttribute($value){
        return $value ?? Carbon::parse($this->created_at)->toDateString();
    }

    public function getTimeAttribute($value){
        return $value ?? Carbon::parse($this->created_at)->toTimeString();
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function getDaysLeftAttribute()
    {
        if($this->type == Constant::APPOINTMENT_TYPE_IN_PERSON || $this->type == Constant::APPOINTMENT_TYPE_SCHEDULE){
            return Carbon::now()->diffInDays(Carbon::parse($this->date), false);
        }else{
            return Carbon::now()->diffInDays(Carbon::parse($this->created_at), false);
        }
    }


    public function getSecondsLeftAttribute()
    {
        if($this->type == Constant::APPOINTMENT_TYPE_IN_PERSON || $this->type == Constant::APPOINTMENT_TYPE_SCHEDULE){
            return Carbon::now()->diffInSeconds(Carbon::parse($this->date), false);
        }else{
            return Carbon::now()->diffInSeconds(Carbon::parse($this->created_at), false);
        }
    }

    public function getAppointmentCount($doctor_id)
    {
        $appointment = new Appointment();
        $constant = new Constant();
        $today = date('Y-m-d');
        $total_earning = DoctorEarning::where('doctor_id', $doctor_id)->sum('income');
        $total_receivable = DoctorPayable::whereHas('doctorEarning', function($q) use($doctor_id){
            $q->where('doctor_id', $doctor_id);
        })
        ->where('status', 'paid')->sum('total_receivable');
        if($doctor_id != null) {
            return [
                'total_appointment' => $appointment->where('doctor_id',$doctor_id)->count(),
                'appointment_today_total' => $appointment->where(['doctor_id'=>$doctor_id,'date'=>$today])->count(),
                'physical_appointment' => $appointment->where(['doctor_id'=>$doctor_id,'type'=> Constant::APPOINTMENT_TYPE_IN_PERSON ])->count(),
                'online_appointment' => $appointment->where(['doctor_id'=>$doctor_id,'type'=> Constant::APPOINTMENT_TYPE_INSTANT ])->count(),
                'virtual_appointment' => $appointment->where(['doctor_id'=>$doctor_id,'type'=> Constant::APPOINTMENT_TYPE_SCHEDULE ])->count(),
                'completed_appointment' => $appointment->where(['doctor_id' => $doctor_id, 'progress' => $constant->APPOINTMENT_STATUS_CANCELLED ])->count(),
                'cancelled_by_patient' => $appointment->where(['doctor_id' => $doctor_id, 'progress' => $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER ])->count(),
                'cancelled_by_doctor' => $appointment->where(['doctor_id' => $doctor_id, 'progress' => $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR ])->count(),
                'total_earning' => isset($total_earning) ? $total_earning : 0,
                'total_receivables' => isset($total_receivable) ? $total_receivable : 0,
            ];
        }
    }

    public function getAppointmentPrescription()
    {
        return $this->hasMany(AppointmentPrescription::class, 'appointment_id');
    }

    public function getCancellationFeeAttribute()
    {
        return 'NA';
    }

    public function getPaymentTypeAttribute()
    {
        $paymentType = 'One Time Payment';
        if($this->booked_via_subscription == 'subscription'){
            $subscription = Subscription::find($this->subscription_id);
            if($subscription){
                $paymentType = $subscription->name;
            }
            $paymentType = 'NA';
        }else if($this->booked_via_subscription == 'free_trail'){
            $paymentType = 'Free Trail';
        }
        return $paymentType;
    }

    public function getAmountAttribute()
    {
        $amount = 0;
        $instant_consultation_discounted_fees = Settings::where('key', 'instant_consultation_discounted_fees')->pluck('value');
        if($this->booked_via_subscription == 'one_time_payment'){
            $amount = (isset($instant_consultation_discounted_fees[0]))?$instant_consultation_discounted_fees[0]:env('INSTANT_CONSULTATION_FEES');
        }
        return $amount;
    }

    public function getPlatformFeeAttribute()
    {
        return 50;
    }
}
