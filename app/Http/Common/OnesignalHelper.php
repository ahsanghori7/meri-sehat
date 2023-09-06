<?php

namespace App\Http\Common;

use Exception;
use GuzzleHttp\Client;
use Jenssegers\Agent\Agent;

class OnesignalHelper {

    protected $client;
    public $headers;

    public function __construct()
    {
        $this->client = new Client();
        $this->headers = ['headers' => [
            'Authorization' => 'Basic '.env('ONE_SIGNAL_AUTHORIZE'),
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
          ]];
    }
    
    public function registerUser($userId)
    {
        $fields = [
            'channel' => 'push',
            'message' => 'Register Message',
            'data' => array('Foo' => 'Bar'),
        ];
        try {
            $response = $this->client->request('POST', 'https://onesignal.com/api/v1/players', [
                'body' => $this->getFieldsForRegistration($userId, $fields),
                'headers' => $this->headers['headers'],
            ]);
            $player = json_decode($response->getBody()->getContents());
            return $player->id;
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }

    public function updateUserPush($userID,$email)
    {
        $fields = [
            'channel' => 'email',
            'message' => 'Register Email',
            'identifier' => $email,
            'device_type' => 11,
        ];
        try {
            $response = $this->client->request('POST', 'https://onesignal.com/api/v1/players', [
                'body' => $this->getFieldsForRegistration($userID, $fields),
                'headers' => $this->headers['headers'],
            ]);
            
            return $response->getBody();
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }

    public function sendSignalPush($field){
        try {
            $response = $this->client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                'body' => $field,
                'headers' => $this->headers['headers'],
            ]);
            return $response->getBody();
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }

    protected function getContent($value = '')
    {
        $content = array("en" => $value);
        return $content;
    }

    protected function getFields($id,$field)
    {
        if($field['channel'] == 'ios'){
            $fields = array(
                'app_id' => env('ONE_SIGNAL_APP_ID'),
                'include_external_user_ids' => array($id),
                'data' => $field['data'],
                'isIos' => true,
                'contents' => $this->getContent($field['message']),
            );
        }
        if($field['channel'] == 'android'){
            $fields = array(
                'app_id' => env('ONE_SIGNAL_APP_ID'),
                'include_external_user_ids' => array($id),
                'data' => $field['data'],
                'isAndroid' => true,
                'contents' => $this->getContent($field['message']),
            );
        }
        if($field['channel'] == 'web'){
            $fields = array(
                'app_id' => env('ONE_SIGNAL_APP_ID'),
                'include_external_user_ids' => array($id),
                'data' => $field['data'],
                'isAnyWeb' => true,
                'contents' => $this->getContent($field['message']),
            );
        }
        $fields = json_encode($fields);
        return $fields;
    }

    protected function getFieldsForRegistration($id, $field){
        $agent = new Agent();
        $device_type = 5;
        $device = '';
        if(isset($agent)){
            if($agent->is('iPhone')){
                $device_type = 0;
                $device = $agent->device();
            }
            if($agent->isAndroidOS()){
                $device_type = 1;
                $device = $agent->device();
            }
            if($agent->browser() === 'Chrome'){
                $device_type = 5;
                $device = 'Google Chrome device';
            }
        }
        if($field['channel'] == 'push'){
            $fields = array(
                'app_id' => env('ONE_SIGNAL_APP_ID'),
                'device_type' => $device_type,
                'device_model' => $device,
                'external_user_id' => "$id",
                'notification_types' => isset($field['notification_types']) ? $field['notification_types'] : 1,
                'data' => $field['data'],
                'contents' => $this->getContent($field['message']),
            );
        }
        if($field['channel'] == 'email'){
            $fields = array(
                'app_id' => env('ONE_SIGNAL_APP_ID'),
                'device_type' => isset($field['device_type']) ? $field['device_type'] : 11,
                'external_user_id' => "$id",
                'identifier' => isset($field['identifier']) ? $field['identifier'] : 'error',
                'notification_types' => isset($field['notification_types']) ? $field['notification_types'] : 1,
                'contents' => $this->getContent($field['message']),
            );
        }
        $fields = json_encode($fields);
        return $fields;
    }

    protected function getFieldsForRegisterUpdate($id, $field){
        $fields = array(
            'device_type' => isset($field['device_type']) ? $field['device_type'] : 5,
            'identifier' => isset($field['identifier']) ? $field['identifier'] : 'error',
        );
        $fields = json_encode($fields);
        return $fields;
    }

    public function viewDevices()
    {
        $url = 'https://onesignal.com/api/v1/players?app_id='.env('ONE_SIGNAL_APP_ID').'&limit=3000';
        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Basic '.env('ONE_SIGNAL_AUTHORIZE'),
                    'accept' => 'text/plain',
                ],
            ]);
            $collection = json_decode($response->getBody(), true);
            $externalids = [];
            $result = 'external_user_id';
            foreach($collection as $key => $value){
                if(is_array($value)){
                    foreach($value as $k => $v){
                        if($v[$result]){
                            array_push($externalids, $v[$result]);
                        }
                    }
                }
            }
            return $externalids;
        } catch (\Exception $th) {
            return $th;
        }
    }

    public function pushUserCron($id)
    {
        $fields = [
            'channel' => 'push',
            'message' => 'Users from database to Onesignal Push Channel',
            'device_type' => 5,
            'app_id' => env('ONE_SIGNAL_APP_ID'),
            'external_user_id' => "$id",
            'notification_types' => isset($field['notification_types']) ? $field['notification_types'] : 1,
            'contents' => array("en" => 'New Users'),
        ];
        $fields = json_encode($fields);
        try {
            $response = $this->client->request('POST', 'https://onesignal.com/api/v1/players', [
                'body' => $fields,
                'headers' => $this->headers['headers'],
            ]);
            return $response->getBody()->getContents();
        }
        catch (\Exception $th) {
            return $th->getMessage();
        }
    }

    public function deletePlayer($playerId)
    {
        try {
            $response = $this->client->request('DELETE', 'https://onesignal.com/api/v1/players/'.$playerId.'?app_id='.env("ONE_SIGNAL_APP_ID").'', [
                'headers' => $this->headers['headers'],
              ]);
              return $response->getBody()->getContents();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}