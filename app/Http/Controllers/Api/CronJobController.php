<?php

namespace App\Http\Controllers\Api;

use App\Http\Common\{FcmHelper, SmsHelper};
use App\Http\Controllers\Controller;
use App\Models\{Appointment, Settings, User, UserSubscription};
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronJobController extends Controller
{
    /**
     * This method is used to Expire Reminders
     */
    public function expireSubscription()
    {
        $getUserSubscriptions = UserSubscription::where('end_date', '<=', Carbon::now()->toDateString())->where('status', true)->where('is_expired', 0)->get();
        foreach($getUserSubscriptions as $getUserSubscription)
        {
            $getUserSubscription->update(['is_expired' => 1]);
        }
        return $this->returnResponse(200, 'Run successfully.');
    }
    /**
     * This method is used to Appointment Reminders
     */
    public function appointmentReminder()
    {
        return $this->returnResponse(200, 'Run successfully.');

        $appointmentReminderTime = Settings::where('key', 'appointment_reminder_time')->first(['value'])->value ?? 15;
        $getAppointments = Appointment::where([
            'date' =>Carbon::now()->toDateString(),
            'status' => true,
            'is_notified' => false,
            'progress' => $this->getConstantByValue('APPOINTMENT_STATUS_PENDING')
        ])
        ->whereBetween('time',  [Carbon::now()->toTimeString(), Carbon::now()->addMinutes($appointmentReminderTime)->toTimeString()])
        ->get();
        foreach($getAppointments as $getAppointment)
        {
            $doctor = User::find($getAppointment->doctor_id);
            $user = User::find($getAppointment->user_id);

            $doctorMessage = "Your Appointment will be start within $appointmentReminderTime minutes";
            $userMessage = "Your Appointment will be start within $appointmentReminderTime minutes with " . (isset($doctor->doctorDetail->prefix) ? $doctor->doctorDetail->prefix . ". " : ""). $doctor->name;

            // Send Message on Phone Numbers
            $smsHelper = new SmsHelper();
            $smsHelper->send($doctor->phone, $doctorMessage);
            $smsHelper->send($user->phone, $userMessage);

            // Send Push Notification to Doctor
            $this->sendPushNotificationsById(
                $getAppointment->doctor_id,
                "Appointment Reminder",
                $doctorMessage,
                [ 'module' => 'dashboard', 'id' => 1, ]
            );
            // Send Push Notification to User
            $this->sendPushNotificationsById(
                $getAppointment->user_id,
                "Appointment Reminder",
                $userMessage,
                [ 'module' => 'dashboard', 'id' => 1, ]
            );
            $getAppointment->update(['is_notified' => true]);
        }
        return $this->returnResponse(200, 'Run successfully.');
    }
}
