<?php

namespace App\Http\Common;

use App\Models\User;
use GuzzleHttp\Client;

class EmailHelper{
    
    public static function sendMail($template_path, $template_data, $to_email, $subject){
        $id = User::where('email', $to_email)->value('id');
        try{
            // $client = new Client();
            //     $fields = array(
            //         'app_id' => env('ONE_SIGNAL_APP_ID'),
            //         'channel_for_external_user_ids' => 'email',
            //         'include_external_user_ids' => ["$id"],
            //         'email_subject' => $subject,
            //         'email_from_address' => env('MAIL_FROM_ADDRESS'),
            //         'email_body' => 'Hello Subscribe users',
            //     );
            // $fields = json_encode($fields);
            // $response = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
            //     'body' => $fields,
            //     'headers' => [
            //         'Authorization' => 'Basic '.env('ONE_SIGNAL_AUTHORIZE'),
            //         'accept' => 'application/json',
            //         'Content-Type' => 'application/json'
            //       ],
            // ]);
            \Mail::send(['html' => $template_path], $template_data,
                function ($message) use ($to_email,$subject) {
                    $message->to($to_email)
                    ->subject($subject)
                    ->from(env('MAIL_FROM_ADDRESS'));
              });
            return true;
        }catch(\Exception $e) {
            return false;
        } 
    }
}