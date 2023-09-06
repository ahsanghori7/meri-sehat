<?php   namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{User, UserSubscription, Settings};
use App\Http\Common\Helper;
use stdClass;
use Illuminate\Support\Carbon;

class EveryNightNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:every-night-notification';

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
        $users = User::where([
            ['is_sehat_scan_message', 0],
            ['created_at', '>=',Carbon::parse('-48 hours')],
            ['role_id', 2]
        ])->pluck('id');
        if($users && $users->count()){
            $sms = new stdClass;
            $sms->to = $users;
            $sms->send_via = 'sms';
            $link = env('WEB_URL').'page/sehat-scan';
            $sms->message = "MERI SEHAT - Click here to perform a SehatScan and know your 6 important vitals: ". $link;
            Helper::sendToUser([$sms]);
            foreach($users as $user){
                $getUser = User::find($user);
                $getUser->update([ 'is_sehat_scan_message' => 1]);
            }
        }

        $sehat_scan_push = User::where([
            ['is_sehat_scan_push', 0],
            ['created_at', '>=',Carbon::parse('-48 hours')],
            ['role_id', 2]
        ])->get();
        if($users && $users->count()){
            foreach($users as $user){
                $name = $user->name ?? 'User';
                $notification = new stdClass;
                $notification->send_via = 'notification';
                $notification->to = [$user->id ?? 0];
                $notification->ref_id = null;
                $notification->title = "Try SehatScan NOW! 📱🙌";
                $notification->sub_title = '';
                $notification->key = null;
                $notification->type = 'health_scans_try_now';
                $notification->type_data = $user->id ?? null;
                $notification->text = "Tap here to perform a SehatScan and know your 6 important vitals 😀😀";
                $notification->module = 'health_scans';
                $notification->message = "Tap here to perform a SehatScan and know your 6 important vitals 😀😀";
                $notification->payload = json_encode($notification);
                Helper::sendToUser([$notification]);
                $getUser->update([ 'is_sehat_scan_push' => 1]);
            }
        }

        $three_day_expire = UserSubscription::where([
            ['end_date', '<=', Carbon::parse('72 hours')],
            ['is_three_day_expire_message', 0],
            ['is_three_day_expire_email', 0],
            ['is_paid', 1],
            ['status', 1],
            ['is_expired', 0]
        ])->get();
        if($three_day_expire && $three_day_expire->count()){
            foreach($three_day_expire as $user_subscription){
                $getUser = User::find($user_subscription->user_id);
                $sms = new stdClass;
                $sms->to = [$getUser->user_id];
                $sms->send_via = 'sms';
                $link = env('WEB_URL').'pricing';
                $customerName = $getUser->name ?? 'customer';
                $settings = Settings::getValues(['uan_number']);
                $sms->message = "MERI SEHAT - Dear ".$customerName.". Your Meri Sehat subscription will expire in 3 days on ".$user_subscription->end_date.". Click here to renew your subscription: ".$link." For more info, call us at ". $settings['uan_number'];
                Helper::sendToUser([$sms]);
                $user_subscription->update([ 'is_three_day_expire_message' => 1]);
                $email = new stdClass;
                $email->to = [$getUser->id];
                $email->send_via = 'email';
                $email->templatePath = 'email_templates.release_1.subscription_expiry_soon';
                $email->templateData = ['name' => $customerName, 'link' => $link, 'end_date' => $user_subscription->end_date, 'uan' => $settings['uan_number']];
                $email->subject = 'Your Meri Sehat subscription is expiring soon! Hurry and renew today!';
                Helper::sendToUser([$email]);
                $user_subscription->update([ 'is_three_day_expire_email' => 1]);

                $name = $getUser->name ?? 'User';
                $notification = new stdClass;
                $notification->send_via = 'notification';
                $notification->to = [$getUser->id];
                $notification->ref_id = null;
                $notification->title = "Expiring Soon! 😓😓";
                $notification->sub_title = '';
                $notification->key = 'subscription';
                $notification->type = 'user_subscriptions_expiring_in_3_days';
                $notification->type_data = $getUser->id;
                $notification->text = "Your Meri Sehat subscription will expire in 3 days on ".$user_subscription->end_date.". Tap here to renew your subscription 🔁";
                $notification->module = 'user_subscriptions';
                $notification->message = "Your Meri Sehat subscription will expire in 3 days on ".$user_subscription->end_date.". Tap here to renew your subscription 🔁";
                $notification->payload = json_encode($notification);
                Helper::sendToUser([$notification]);
            }
        }

        $seven_day_not_logged = User::where([
            ['updated_at', '<=',Carbon::parse('-168 hours')],
            ['role_id', 2]
        ])->get();
        if($seven_day_not_logged && $seven_day_not_logged->count()) {
            foreach ($seven_day_not_logged as $user) {

                $name = $user->name ?? 'User';
                $notification = new stdClass;
                $notification->send_via = 'notification';
                $notification->to = [$user->id ?? 0];
                $notification->ref_id = null;
                $notification->title = "We miss you 😞😞";
                $notification->sub_title = '';
                $notification->key = null;
                $notification->type = 'user_not_logged';
                $notification->type_data = $user->id ?? null;
                $notification->text = "Dear " . $name . " we hope you are well. Help us take better care of you. Tap to check your vitals now.";
                $notification->module = 'user';
                $notification->message = "Dear " . $name . " we hope you are well. Help us take better care of you. Tap to check your vitals now.";
                $notification->payload = json_encode($notification);
                Helper::sendToUser([$notification]);

            }
        }
    }
}
