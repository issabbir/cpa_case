<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function (){
            $this->sendSMS();
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    public function sendSMS(){
        $curl = curl_init();

        $querys = "SELECT cm.case_id,
       lm.lawyer_id,
       NULL AS notification_subject,
       'Dear Sir,
Case No. ' || cm.case_no || ' has been assigned to you.
Please contact with IEC section of CPA soon.
With Thanks,
IEC Section
CPA'     AS MESSAGE,
       NULL AS notify_from,
       lm.contact_no notify_to,
       NULL AS cc_to,
       NULL AS bcc_to,
       ROUND (DBMS_RANDOM.VALUE (100000, 999999)) AS random_num
  FROM case_master_info cm, lawyer_case_mapping lc, lawyer_master_info lm
 WHERE     cm.case_id = lc.case_id
       AND lc.lawyer_id = lm.lawyer_id
       AND TRUNC (next_date) = TRUNC (SYSDATE + 3)" ;
        $sendDataSet = DB::select($querys);

        foreach ($sendDataSet as $data)
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://192.168.78.10:5123/api/v1/client/sms-request",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => array('msisdn' => $data->notify_to,'msg' => $data->message,'service' => '3','trace_code' =>$data->random_num), //$data->court_id),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer rZK0hN5unNzkowe4FrRcmOS7fhvWBrSnjqAm3OXnkjQ7aMpGLY51mpxJ7avnhCj9fyTbFK0AidkKitcq"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $json = json_decode($response, true);
            if($json['code'])
            {
                if($json['code']=="000")
                {
                    $notification_id = '';
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params = [
                        "p_notification_id" => $notification_id,
                        "p_case_id" => $data->case_id,
                        "p_lawyer_id" => $data->lawyer_id,
                        "p_notification_subject" => '',
                        "p_message" => $data->message,
                        "p_notify_from" => 'CPA',
                        "p_notify_to" => $data->notify_to,
                        "p_cc_to" => '',
                        "p_bcc_to" => '',
                        "p_auto_send_yn" => 'Y',
                        "p_notify_for" => '',
                        "p_insert_by" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message,
                    ];
                    DB::executeProcedure("CPACMS.case_lawyer_notification", $params);
                }
            }
        }
    }
}
