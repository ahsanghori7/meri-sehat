<?php   namespace App\Console\Commands;

use App\Http\Common\Helper;
use Illuminate\Console\Command;
use stdClass;
use App\Models\{Subscription, UserSubscription};
use Illuminate\Support\Carbon;

class SubscriptionsExpireAndRefillSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usersubscription:expire_refill';

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
        $getExpireSubscriptions = UserSubscription::where([
            ['status', 1],
            ['end_date', '<', Carbon::now()],
            ['is_expired', 0]
        ])->get();

        foreach($getExpireSubscriptions as $userSubscription){
            $userSubscription->update(['is_expired' => 1]);

            $name = $userSubscription->user->name ?? 'User';
            $notification = new stdClass;
            $notification->send_via = 'notification';
            $notification->to = [$userSubscription->user->id];
            $notification->ref_id = null;
            $notification->title = "We miss you 😞😞";
            $notification->sub_title = '';
            $notification->key = 'subscription';
            $notification->type = 'user_subscriptions_expired';
            $notification->type_data = $userSubscription->id;
            $notification->text = "Dear $name, Your subscription has expired 😓. To renew your subscription, Tap here 🔁";
            $notification->module = 'user_subscriptions';
            $notification->message = "Dear $name, Your subscription has expired 😓. To renew your subscription, Tap here 🔁";
            $notification->payload = json_encode($notification);
            Helper::sendToUser([$notification]);
        }
        $this->info($getExpireSubscriptions->count() . 'appointment cancle. id: ' . json_encode($getExpireSubscriptions));

        $userRefillSubscription = UserSubscription::where([
            ['status', 1],
            ['end_date', '>', Carbon::now()],
            ['is_yearly', 1],
            ['is_expired', 0]
        ])->get();

        foreach($userRefillSubscription as $urs){
            if($urs->refill_date){
                $difference = Carbon::now()->diffInDays(Carbon::parse($urs->refill_date));
                $getSubscription = Subscription::find($urs->subscription_id);
                if($difference > 30){
                    $urs->update(['receipt_data' => $getSubscription->rules, 'refill_date' => Carbon::now()]);
                }
            }
        }

        $this->info('updated');
    }
}
