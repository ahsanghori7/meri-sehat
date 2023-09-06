<?php   namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{DoctorDetail, Shift};
use Carbon\Carbon;

class ForcefullySwitchOffFromInstant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instant:off';

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
        $now = date("H");
        $getActiveShifts = Shift::whereTime('start', '<', Carbon::now())->whereTime('end', '>', Carbon::now())->get()->pluck('id');
        if($getActiveShifts){
            if ($now > "22") {
                DoctorDetail::where('doctor_id', '!=', 29951)->whereNotIn('shift_id', $getActiveShifts)->update(['is_instant_consultation' => 0, 'assigned_calls' =>  0]);
            }else{
                if($now < "7"){
                    DoctorDetail::where('doctor_id', '!=', 29951)->whereNotIn('shift_id', $getActiveShifts)->update(['is_instant_consultation' => 0, 'assigned_calls' =>  0]);
                }else{
                    DoctorDetail::whereNotIn('shift_id', $getActiveShifts)->update(['is_instant_consultation' => 0, 'assigned_calls' =>  0]);
                }
            }
        }
    }
}
