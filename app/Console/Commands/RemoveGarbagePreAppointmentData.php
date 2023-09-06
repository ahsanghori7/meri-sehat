<?php   namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Appointment, PreAppointment};
use App\Http\Common\Constant;
class RemoveGarbagePreAppointmentData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:preappointment';

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
        $getAllPreAppointments = PreAppointment::all();
        foreach($getAllPreAppointments as $pre){
            $getAppointmentLastRecord = Appointment::where([
                ['type', 'instant-consultation'],
                ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
            ])->orderBy('id', 'DESC')->first();

            if($getAppointmentLastRecord){
                if($getAppointmentLastRecord->created_at > $pre->created_at){
                    $pre->delete();
                }
            }
        }

        return 'Cron run';
    }
}
