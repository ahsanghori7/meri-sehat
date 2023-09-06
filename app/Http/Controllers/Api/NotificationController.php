<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\{Controller, Api\InstantConsultationController};
use App\Http\Resources\User\{AppointmentResource, UserNotificationResource, UserResource, UserSubscriptionResource};
use App\Models\{Appointment, Language, Subscription, Transaction, User, UserNotification, UserSubscription};
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Common\Constant;

class NotificationController extends Controller
{
    /**
     * This method is used to confirm the transaction
     */
    public function getNotificationByUserId(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // 'user_id' => ['required'],
                'page' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            $order = 'desc';
            $sort = 'id';
            $limit = 5;
            $available_more = 0;
            $first_page = 0;
            $current_page = 0;
            $last_page = 0;
            if ($request->has('sort')) {
                $sort = $request->sort;
            }
            if ($request->has('order')) {
                $order = $request->order;
            }
            if ($request->has('limit')) {
                $limit = $request->limit;
            }
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->user_id;
            }
            $notifications = UserNotification::where('user_id', $user_id)->orderBy($sort, $order)->paginate($limit);
            if ($notifications) {
                $available_more = $notifications->lastPage() - $notifications->currentPage();
                $current_page = $notifications->currentPage();
                $last_page = $notifications->lastPage();
            }
            $sendData = [
                'unread_count' => UserNotification::where([
                    ['user_id', $user_id],
                    ['read_status', 0]
                ])->count(),
                'first_page' => 1,
                'current_page' => $current_page,
                'last_page' => $last_page,
                'limit' => (int)$limit,
                'available_more' => $available_more,
                'notifications' => UserNotificationResource::collection($notifications)
            ];
            $InstantConsultationController = new InstantConsultationController();
            $getWaiting = $InstantConsultationController->getWaitingTimeRes($user_id);
            $checkCurrentAppointment = false;
            $appointment = Appointment::with('doctor')->where('user_id', $user_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->first();
            if($appointment){
                $checkCurrentAppointment = true;
            }
            if(is_int($getWaiting) && Request()->segment(2) === "v1"){
                $sendData['waiting_time'] =  0;
                $sendData['doctor_detail'] = $appointment->doctor;
            }
            if(is_int($getWaiting)){
                if($checkCurrentAppointment && $getWaiting == 0){
                    $sendData['waiting_time'] =  $getWaiting;
                    $sendData['doctor_detail'] = $appointment->doctor;
                    $sendData['doctor_detail']->image = $appointment->doctor->image_url;
                }elseif($getWaiting > 0){
                    $sendData['waiting_time'] =  $getWaiting;
                    $sendData['doctor_detail'] = $appointment->doctor;
                    $sendData['doctor_detail']->image = $appointment->doctor->image_url;
                }
            }

            return $this->returnResponse(200, 'Notification fetch successfully.', $sendData);
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }


    }

    public function getNotificationByRefId(Request $request)
    {
        try{

        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }


    }

    public function getNotificationFetch(Request $request)
    {
        try{

        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }


    }

    public function postNotificationByUserId(Request $request)
    {
        try{
            $user_id = $request->headers->get('user_id');
            if($request->has('mark_all')){
                $notifications = UserNotification::where([
                    ['user_id', $user_id],
                    ['read_status', 0]
                ])->get();
                foreach($notifications as $notification){
                    $notification->update(['read_status' => 1]);
                }
                return $this->returnResponse(200, 'All notification mark as read.');
            }else{
                $notification = UserNotification::find($request->notification_id);
                if(!$notification){
                    return $this->returnResponse(400, 'Invalid notification id.');
                }
                $notification->read_status = 1;
                $notification->save();
                if(!$notification){
                    return $this->returnResponse(400, 'Unable to update notification.');
                }
                return $this->returnResponse(200, 'mark as read successfully.', ['notifications' => $notification]);
            }
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function markRead(Request $request, $notification_id)
    {
        try{
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->headers->get('user_id');
            }
            if($request->has('mark_all')){
                $notifications = UserNotification::where([
                    ['user_id', $user_id],
                    ['read_status', 0]
                ])->get();
                foreach($notifications as $notification){
                    $notification->update(['read_status' => 1]);
                }
                return $this->returnResponse(200, 'All notification mark as read.');
            }else{
                $notification = UserNotification::find($notification_id);
                if(!$notification){
                    return $this->returnResponse(400, 'Invalid notification id.');
                }
                $notification = $notification->update(['read_status' => 1]);
                if(!$notification){
                    return $this->returnResponse(400, 'Unable to update notification.');
                }
                return $this->returnResponse(200, 'mark as read successfully.');
            }
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
