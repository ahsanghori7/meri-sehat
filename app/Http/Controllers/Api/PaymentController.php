<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\{AppointmentResource, UserSubscriptionResource};
use App\Models\{ Appointment, Language, Subscription, Transaction, User, UserSubscription, Settings };
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Common\{Constant, Helper as CustomHelper, ResponseHelper};
use Illuminate\Support\Facades\Http;
use App\Http\Common\Helper;
use stdClass;

class PaymentController extends Controller
{
    public function makePayment(Request $request)
    {
        try {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'reference_id' => ['required'],
                'reference_type' => ['required', 'in:appointment,subscription,one_time'],
                'payment_method' => ['required', 'in:pay_cash_in_clinic,credit_debit_card,easypaisa_mobile_wallet,bank_transfer,alfa_wallet,alfa_bank_account,easypaisa'],
                'amount' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            if(Request()->segment(2) === "v2"){
                if(\Auth::user()) {
                    $userId = \Auth::user()->id;
                }
            }else{
                if(!$request->header('user_id')){
                    return $this->returnResponse(400, "User not authenticated");
                }
                $userId = $request->header('user_id');
            }

            if($request->reference_type == 'appointment'){
                $getAppointment = Appointment::find($request->reference_id);
                $getAmount = Appointment::find($request->reference_id)->grand_total;
            }elseif($request->reference_type == 'one_time'){
                //$getAppointment = Appointment::find($request->reference_id);
                $instant_consultation_discounted_fees = Settings::where('key', 'instant_consultation_discounted_fees')->get()->pluck('value');
                $createSubscription = new \stdClass();
                $createSubscription->id = 0;
                $getAmount = (isset($instant_consultation_discounted_fees[0]))?$instant_consultation_discounted_fees[0]:env('INSTANT_CONSULTATION_FEES');
            }else{
                $getSubscription = Subscription::find($request->reference_id);
                if($getSubscription->price == 0 || $getSubscription->discounted_price == 0){
                    $userSubs = UserSubscription::where([
                        ['subscription_id', $getSubscription->id],
                        ['user_id', $userId],
                        ['status', 1]
                    ])->get();
                    if(!$request->has('is_yearly_pkg')){
                        if(count($userSubs)){
                            foreach($userSubs as $sub){
                                $difference = Carbon::now()->diffInDays(Carbon::parse($sub->start_date));
                                if($difference < 30){
                                    return $this->returnResponse(400, 'You already avail this subscription.');
                                }
                            }
                        }
                    }elseif($request->is_yearly_pkg==0){

                        if(count($userSubs)){
                            foreach($userSubs as $sub){
                                $difference = Carbon::now()->diffInDays(Carbon::parse($sub->start_date));
                                if($difference < 365 && $sub->is_yearly){
                                    return $this->returnResponse(400, 'You already avail this subscription.');
                                }elseif($difference < 30){
                                    return $this->returnResponse(400, 'You already avail this subscription.');
                                }
                            }
                        }
                    }
                }
                $refill_date = Carbon::now();
                if($request->has('is_yearly_pkg') && $request->is_yearly_pkg){
                    $discounted_price = $getSubscription->discounted_price_yearly;
                    $price = $getSubscription->price_yearly;
                    $duration = $getSubscription->duration_yearly;
                    $is_yearly = 1;
                }else{
                    $discounted_price = $getSubscription->discounted_price;
                    $price = $getSubscription->price;
                    $duration = $getSubscription->duration;
                    $is_yearly = 0;
                }
                $getAmount = $discounted_price ?? $price;
                // $getUserSubscriptions = UserSubscription::where(['user_id' => $userId, 'status' => true])->get();
                // if(count($getUserSubscriptions)){
                //     foreach($getUserSubscriptions as $getPackage){
                //         $getPackage->update(['is_expired' => true]);
                //     }
                // }
                $getSubscription = Subscription::find($request->reference_id);
                // if($getSubscription->id==1){

                //     $createSubscription = UserSubscription::create([
                //         'user_id' => $request->header('user_id'),
                //         'subscription_id' => $request->reference_id,
                //         'receipt_data' => $getSubscription->rules,
                //         'start_date' => Carbon::now(),
                //         'end_date' => Carbon::now()->addDays($duration),
                //         'status' => true,
                //         'is_paid' => true,
                //         'is_yearly' => $is_yearly,
                //         'refill_date' => $refill_date
                //     ]);

                //     $getUserTransaction = Transaction::where([
                //         ['reference_type', 'subscription'],
                //         ['user_id',$request->header('user_id')],
                //         ['is_avail', 0]
                //     ])->orderBy('created_at', 'desc')->first();

                //      $data=Transaction::where('id',$getUserTransaction->id)
                //      ->update(['status'=>true]);


                //     $endPoint= '/payment-proccess?res=succeed';
                //     if(env('APP_ENV') == 'local'){
                //         $base_domain = "https://staging.merisehat.pk".$endPoint;
                //     }else{
                //         $base_domain = "https://merisehat.pk".$endPoint;
                //     }
                //     return redirect()->to($base_domain)->send();
                // }

                if($discounted_price == 0 || $price == 0){
                    UserSubscription::where(['user_id' => $userId, 'status' => true])->update(['is_expired' => true]);
                    $createSubscription = UserSubscription::create([
                        'user_id' => $userId,
                        'subscription_id' => $request->reference_id,
                        'receipt_data' => $getSubscription->rules,
                        'start_date' => Carbon::now(),
                        'end_date' => Carbon::now()->addDays($duration),
                        'status' => true,
                        'is_paid' => true,
                        'is_yearly' => $is_yearly,
                        'refill_date' => $refill_date,
                        'is_expired' => 0
                    ]);
                    $endPoint= '/payment-proccess?res=succeed';
                    if(env('APP_ENV') == 'staging' || env('APP_ENV') == 'local'){
                        $base_domain = "https://staging.merisehat.pk".$endPoint;
                    }else{
                        $base_domain = "https://merisehat.pk".$endPoint;
                    }
                    return $this->returnResponse(200, 'redirect', ['redirect' => true, 'url' => $base_domain]);
                    // return redirect()->to($base_domain)->send();
                    // return redirect()->route('redirectToPage',  ['url' => $base_domain]);
                }else{
                    $createSubscription = UserSubscription::create([
                        'user_id' => $userId,
                        'subscription_id' => $request->reference_id,
                        'receipt_data' => $getSubscription->rules,
                        'start_date' => Carbon::now(),
                        'end_date' => Carbon::now()->addDays($duration),
                        'status' => false,
                        'is_yearly' => $is_yearly,
                        'refill_date' => $refill_date,
                    ]);
                }
            }
            $createTransaction = Transaction::create([
                'user_id' => $userId,
                'reference_id' => $request->reference_type == 'appointment' ? $getAppointment->id : $createSubscription->id,
                'reference_type' => $request->reference_type,
                'payment_method' => strtolower($request->payment_method),
                'amount' => $getAmount,
                'type' => "credit",
                'status' => false,
            ]);
            if(!$createTransaction){
                return $this->returnResponse(400, 'Unable to make a transaction.');
            }
            if($request->reference_type == 'subscription'){
                UserSubscription::find($createSubscription->id)->update(['transaction_id' => $createTransaction->id]);
            }
            if($createTransaction->payment_method == 'pay_cash_in_clinic'){
                return $this->returnResponse(200, 'Make Transaction Successfully', [
                    'transaction_for' => $request->reference_type,
                    'heading' => $request->header('locale') == Language::ENGLISH ? 'Your Appointment is confirmed!' : 'آپ کی ملاقات کی تصدیق ہو گئی ہے!',
                    'description' => $request->header('locale') == Language::ENGLISH ? 'Here are the details. You can edit any information from the user account area.' : 'تفصیلات یہ ہیں۔ آپ صارف کے اکاؤنٹ کے علاقے سے کسی بھی معلومات میں ترمیم کر سکتے ہیں۔',
                    'appointment_details' => new AppointmentResource(Appointment::find($request->reference_id))
                ]);
            }
            if($createTransaction->payment_method == 'bank_transfer'){
                return $this->returnResponse(200, 'Make Transaction Successfully', [
                    'transaction_for' => $request->reference_type,
                    'heading' => $request->header('locale') == Language::ENGLISH ? 'Payment is under reviewing!' : 'آپ کی ملاقات کی تصدیق ہو گئی ہے!',
                    'description' => $request->header('locale') == Language::ENGLISH ? 'Once payment received you will received an email of appointment confirmation. You can edit any information from the user account area.' : 'تفصیلات یہ ہیں۔ آپ صارف کے اکاؤنٹ کے علاقے سے کسی بھی معلومات میں ترمیم کر سکتے ہیں۔',
                    'appointment_details' => new AppointmentResource(Appointment::find($request->reference_id))
                ]);
            }
            return $this->returnResponse(200, 'Make Transaction Successfully', ['redirect_url' => route('user-payment', [$createTransaction->e_id, $request->id])]);

        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to redirect the user to the application
     */
    public function redirect(Request $request, $id,$iframe_id)
    {
        if($iframe_id==61876 && env('APP_ENV') != 'staging'){
            $iframe_id=66108;
        }

        elseif($iframe_id==62039 && env('APP_ENV') != 'staging'){
            $iframe_id=110162;
        }

        elseif($iframe_id==73948 && env('APP_ENV') != 'staging'){
            $iframe_id=73948;
        }

        elseif($iframe_id==73948 && env('APP_ENV') == 'staging'){
            $iframe_id=73948;
        }

        elseif($iframe_id==61876 && env('APP_ENV') == 'staging'){
            $iframe_id=61876;
        }
        else{
            $iframe_id=110162;
        }


        if(env('APP_ENV') == 'staging'){
            $base_domain = "https://staging.merisehat.pk/";
        }else{
            $base_domain = "https://merisehat.pk/";
        }
        $getTransaction = Transaction::find(decrypt($id));
        $id = decrypt($id);

        $orderId = 'MS_OTPayment_'.time().'_'.$id;
        if($getTransaction->reference_type == 'subscription'){
            $orderId = 'MS_SUB_'.time().'_'.$id;
        }
        if($getTransaction){
            $getTransaction->merchant_order_id = $orderId;
            $getTransaction->save();
        }
        $userId=User::find($getTransaction->user_id);

        $firstname=$userId->name? $userId->name : "merisehat";
        $email=$userId->email? $userId->email : "user".time()."@merisehat.app";
        $phone=$userId->phone? $userId->phone :"03331234567";
        // return redirect()->route('payment-confirmation-static',['id' => $id]);
        return view("payment.paymob", ['transction_id' => $id, 'amount' => $getTransaction->amount, 'transaction' => $getTransaction, 'orderId' => $orderId,'name' => $firstname,'email' => $email,'phone' => $phone, 'iframe_id' => $iframe_id]);
    }
    /**
     * This method is used to confirm the transaction
     */
    public function paymentConfirmation(Request $request)
    {

        $endPoint = '';
        $data=$request->query();
        $type = isset($data["success"]) && $data["success"] && $data["data_message"] && $data["data_message"]=="Approved" || $data["data_message"]=="Success" ? 'succeed' : 'failed';
        $getExplodedId = explode('_', $data["merchant_order_id"]);
        $id = end($getExplodedId);
        $getTransaction = Transaction::find($id);
        \DB::table('paymob_responses')->insert(['transaction_id' => $id, 'response' => json_encode($data), 'created_at' => date('Y-m-d H:i:s')]);
        if($getTransaction->reference_type == "appointment"){
            $getParentDetails = Appointment::find($getTransaction->reference_id);
            if($type == 'succeed'){
                $getParentDetails->update(['is_paid' => true, 'status' => true]);
            }
            $response = [
                'transaction_for' => $getTransaction->reference_type,
                'heading' => 'Your Appointment is confirmed!',
                'description' => 'Here are the details. You can edit any information from the user account area.',
                'appointment_details' => new AppointmentResource($getParentDetails)
            ];
        }elseif ($getTransaction->reference_type == "one_time"){
            $response = [
                'transaction_for' => $getTransaction->reference_type,
                'heading' => 'Your One time payment is recived!',
                'description' => 'Your One time payment is recived!',
                'transaction_details' => new AppointmentResource($getTransaction)
            ];
            $endPoint= '/instant-patient-info';
            if($type == 'succeed'){
                $getUser = User::find($getTransaction->user_id);
                if($getUser){
                    $sms = new stdClass;
                    $sms->to = [$getTransaction->user_id];
                    $sms->send_via = 'sms';
                    $link = env('WEB_URL').'search-for-doctor';
                    $customerName = $getUser->name ?? 'customer';
                    $settings = Settings::getValues(['uan_number']);
                    $sms->message = "MERI SEHAT -  Dear ".$customerName.", your Doctor Now payment has been processed. Click here to join the lobby: ". $link ." For more info, call us at ". $settings['uan_number'];
                    Helper::sendToUser([$sms]);
                    $email = new stdClass;
                    $email->to = [$getUser->id];
                    $email->send_via = 'email';
                    $email->templatePath = 'email_templates.release_1.one_time_payment';
                    $email->templateData = ['name' => $getUser->name ?? 'customer', 'link' => env('WEB_URL').'start', 'uan' => $settings['uan_number']];
                    $email->subject = 'Success! Your Doctor Now appointment payment is confirmed!';
                    Helper::sendToUser([$email]);

                    $name = $getUser->name ?? 'User';
                    $notification = new stdClass;
                    $notification->send_via = 'notification';
                    $notification->to = [$getUser->id];
                    $notification->ref_id = null;
                    $notification->title = "Success! Your payment has been processed 💳💳";
                    $notification->sub_title = '';
                    $notification->key = null;
                    $notification->type = 'payment_success';
                    $notification->type_data = $getUser->id;
                    $notification->text = "Dear $name, your Doctor Now payment has been processed. Tap to join the lobby. 👥";
                    $notification->module = 'transactions';
                    $notification->message = "Dear $name, your Doctor Now payment has been processed. Tap to join the lobby. 👥";
                    $notification->payload = json_encode($notification);
                    Helper::sendToUser([$notification]);
                }
            }
        }else{
            $getParentDetails = UserSubscription::find($getTransaction->reference_id);
            if($type == 'succeed'){
                $getUserSubscriptions = UserSubscription::where(['user_id' => $getParentDetails->user_id, 'status' => true])->get();
                if(count($getUserSubscriptions)){
                    foreach($getUserSubscriptions as $getPackage){
                        if($getPackage->id !=  $getParentDetails->id){
                            $getPackage->update(['is_expired' => true]);
                        }
                    }
                }
                $getParentSubscription = Subscription::where('id', $getParentDetails->subscription_id)->first();
                if($getParentDetails->is_yearly){
                    $duration = $getParentSubscription->duration_yearly;
                }else{
                    $duration = $getParentSubscription->duration;
                }
                $subscription = Subscription::find($getParentDetails->subscription_id);
                $getParentDetails->update(['is_paid' => true, 'status' => true, 'is_expired' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays($duration)]);
                User::find($getParentDetails->user_id)->update(['is_subscribed' => true]);

                $getUser = User::find($getTransaction->user_id);
                if($getUser){
                    $sms = new stdClass;
                    $sms->to = [$getTransaction->user_id];
                    $sms->send_via = 'sms';
                    $link = env('WEB_URL').'payment-proccess?res='.$type;
                    $customerName = $getUser->name ?? 'customer';
                    $settings = Settings::getValues(['uan_number']);
                    $sms->message = "Dear ".$customerName.", click below to get your subscription receipt ". $link ." For more info, call us at ". $settings['uan_number'];
                    Helper::sendToUser([$sms]);
                    $email = new stdClass;
                    $email->to = [$getUser->id];
                    $email->send_via = 'email';
                    $email->templatePath = 'email_templates.release_1.subscription_paid';
                    $email->templateData = ['name' => $getUser->name ?? 'customer', 'link' => $link, 'subscription_type' => $subscription->name, 'uan' => $settings['uan_number']];
                    $email->subject = 'Success! Your Subscription payment is confirmed!';
                    Helper::sendToUser([$email]);

                    $name = $getUser->name ?? 'User';
                    $notification = new stdClass;
                    $notification->send_via = 'notification';
                    $notification->to = [$getUser->id];
                    $notification->ref_id = null;
                    $notification->title = "Your Subscription is active! 💪💪";
                    $notification->sub_title = '';
                    $notification->key = 'subscription';
                    $notification->type = 'user_subscriptions_active';
                    $notification->type_data = $getUser->id;
                    $notification->text = "Thank you for choosing the ". $getParentDetails->package->name ." package. Your payment has been confirmed. Tap here to view the receipt 📃";
                    $notification->module = 'user_subscriptions';
                    $notification->message = "Thank you for choosing the ". $getParentDetails->package->name ." package. Your payment has been confirmed. Tap here to view the receipt 📃";
                    $notification->payload = json_encode($notification);
                    Helper::sendToUser([$notification]);
                }
            }
            $response = [
                'transaction_for' => $getTransaction->reference_type,
                'heading' => 'Your Subscription is purchased successfully!',
                'description' => 'Here are the details. You can edit any information from the user account area.',
                'subscription_details' => new UserSubscriptionResource($getParentDetails)
            ];
            // dd($getTransaction->reference_id);
            $subs_yearly = UserSubscription::where('id',$getTransaction->reference_id)->first();
            $is_yearly =$subs_yearly->is_yearly;
            $subscription=Subscription::find($getParentDetails->subscription_id);
            $refer_id=$subscription->id;
            $endPoint= '/payment-proccess?res='.$type.'&refer_id='.$refer_id.'&is_yearly='.$is_yearly;

        }
        // $response = json_encode($response);
        // dd($response);
        if(env('APP_ENV') == 'staging'){
            $base_domain = "https://staging.merisehat.pk".$endPoint;
        }else{
            $base_domain = "https://merisehat.pk".$endPoint;
        }

        $payment_method = '';
        if(isset($data['source_data_sub_type']) && !empty($data['source_data_sub_type'])){
            if($data['source_data_sub_type'] == 'EASYPAISA'){
                $payment_method = Constant::PAYMENT_METHOD_EASYPAISA_MOBILE_WALLET;
            }
            elseif($data['source_data_sub_type'] == 'MasterCard'){
                $payment_method = Constant::PAYMENT_METHOD_CREDIT_DEBIT_CARD;
            }
        }

        if($type == 'succeed'){
            $getTransaction->update([
                'status' => true,
                'type' => 'debit',
                'payment_method' => $payment_method,
                'merchant_order_id' => $data['merchant_order_id'],
                'payment_confirmation_date' => Carbon::now()
            ]);
            return redirect($base_domain);
        }
        return redirect()->to($base_domain)->send();
    }

    public function paymentConfirmationProcessed(Request $request){
        $data=$request->query();
        $getExplodedId = explode('_', $data["merchant_order_id"]);
        $id = end($getExplodedId);
        \DB::table('paymob_responses')->insert(['transaction_id' => $id, 'response' => json_encode($data), 'created_at' => date('Y-m-d H:i:s')]);

    }
    public function paymentConfirmationStatic(Request $request)
    {
        $type = 'succeed';
        $id = $request->id;
        $getTransaction = Transaction::find($id);
        if(!$getTransaction){
            return ResponseHelper::returnJsonResponse(400, 'No Trasaction found', ['status' => false]);
        }
        if($getTransaction->reference_type == "appointment"){
            $getParentDetails = Appointment::find($getTransaction->reference_id);
            if($type == 'succeed'){
                $getParentDetails->update(['is_paid' => true, 'status' => true]);
            }
            $response = [
                'transaction_for' => $getTransaction->reference_type,
                'heading' => 'Your Appointment is confirmed!',
                'description' => 'Here are the details. You can edit any information from the user account area.',
                'appointment_details' => new AppointmentResource($getParentDetails)
            ];
        }else{
            $getParentDetails = UserSubscription::find($getTransaction->reference_id);
            if($type == 'succeed'){
                $getParentDetails->update(['is_paid' => true, 'status' => true]);
                User::find($getParentDetails->user_id)->update(['is_subscribed' => true]);
            }
            $response = [
                'transaction_for' => $getTransaction->reference_type,
                'heading' => 'Your Subscription is purchased successfully!',
                'description' => 'Here are the details. You can edit any information from the user account area.',
                'subscription_details' => new UserSubscriptionResource($getParentDetails)
            ];
        }
        $response = json_encode($response);
        if($type == 'succeed'){
            $getParentDetails->update(['is_paid' => true]);
            $getTransaction->update([
                'status' => true,
                'type' => 'debit'
            ]);
            return redirect($base_domain . "appointment/payment-confirmation", 302);
        }
        return ResponseHelper::returnJsonResponse(200, 'Static payment done', ['status' => true]);
    }

    public function bankalfalah_handshake(Request $request){
        // URL
        $apiURL = $request->handShakeUrl;

        // POST Data
        $postInput = [
            'HS_ChannelId' => $request->HS_ChannelId,
            'HS_MerchantId' => $request->HS_MerchantId,
            'HS_StoreId' => $request->HS_StoreId,
            'HS_ReturnURL' => $request->HS_ReturnURL,
            'HS_MerchantHash' => $request->HS_MerchantHash,
            'HS_MerchantUsername' => $request->HS_MerchantUsername,
            'HS_MerchantPassword' => $request->HS_MerchantPassword,
            'HS_TransactionReferenceNumber' => $request->HS_TransactionReferenceNumber,
            'HS_RequestHash' => $request->HS_RequestHash,
            'HS_IsRedirectionRequest' => $request->HS_IsRedirectionRequest
        ];

        // Headers
        $headers = [
            //...
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        echo $statusCode;  // status code

        dd($responseBody); // body response
    }

    public function redirectToPage(Request $request){
        return redirect()->to($request->url)->send();
    }

}
