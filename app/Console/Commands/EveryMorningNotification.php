<?php   namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{User, UserSubscription, Settings};
use App\Http\Common\Helper;
use stdClass;
use Illuminate\Support\Carbon;

class EveryMorningNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:every-morning-notification';

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
        $today_expire = UserSubscription::whereDate('end_date', Carbon::today())->where([
            ['is_day_expire_message', 0],
            ['is_paid', 1],
            ['status', 1],
            ['is_expired', 0]
        ])->get();
        if($today_expire && $today_expire->count()){
            foreach($today_expire as $user_subscription){
                $getUser = User::find($user_subscription->user_id);
                $sms = new stdClass;
                $sms->to = [$user_subscription->user_id];
                $sms->send_via = 'sms';
                $link = env('WEB_URL').'pricing';
                $settings = Settings::getValues(['uan_number']);
                $customerName = $getUser->name ?? 'customer';
                $sms->message = "MERI SEHAT - Dear ".$customerName.". Today is the last day of your subscription. To renew your package, click here: ".$link." for more info, call us at ". $settings['uan_number'];
                Helper::sendToUser([$sms]);
                $user_subscription->update([ 'is_day_expire_message' => 1]);

                $name = $getUser->name ?? 'User';
                $notification = new stdClass;
                $notification->send_via = 'notification';
                $notification->to = [$getUser->id];
                $notification->ref_id = null;
                $notification->title = "Expiring Today! 😓";
                $notification->sub_title = '';
                $notification->key = 'subscription';
                $notification->type = 'user_subscriptions_last_day';
                $notification->type_data = $getUser->id;
                $notification->text = "Today is the last day of your subscription 😓. To renew your package, Tap here 🔁";
                $notification->module = 'user_subscriptions';
                $notification->message = "Today is the last day of your subscription 😓. To renew your package, Tap here 🔁";
                $notification->payload = json_encode($notification);
                Helper::sendToUser([$notification]);
            }
        }
    }
}
