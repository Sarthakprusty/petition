<?php

namespace App\Listeners;

use App\Events\SendEmailEventAck;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
class SendEmailListenerAck implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendEmailEventAck $event
     * @return void
     */
    public function handle(SendEmailEventAck $event)
    {
        $application = $event->application;

        if (($application->email_id != null)&&($application->ack_mail_sent == 0 || $application->ack_mail_sent == '' )&&($application->acknowledgement_path !==null)){
//                $email = $application->email_id;
//                    $email = 'us.petitions@rb.nic.in';
//                    $cc = [];
//                    $cc[] = 'sayantan.saha@gov.in';
//                    $cc[] = 'so-public1@rb.nic.in';
//                    $cc[] = 'so-public2@rb.nic.in';
//                    $cc[] = 'prustysarthak123@gmail.com';
            $fname = str_replace('/', '_', $application->reg_no);
            $email = 'sayantan.saha@gov.in';
            $cc = [];
            $cc[] = 'prustysarthak123@gmail.com';
            $cc[] = 'shantanubaliyan935@gmail.com';
            $subject = 'Reply From Rashtrapati Bhavan';
            $details = $application->applicant_title . " " . $application->applicant_name . ",<br><br>
                                 Your Petition has been received in Rashtrapati Bhavan with ref no " . $application->reg_no . " and forwarded to " . $application->department_org->org_desc . " for further necessary action.<br><br>
                                    Regards, <br>
                             President's Secretariat<br>";
            $content = storage::disk('upload')->get(base64_decode($application->acknowledgement_path));
            try {
                Mail::send([], [], function ($message) use ($email, $subject, $details, $content, $cc,$fname) {
                    $message->to($email)->cc($cc[0])
                        ->cc($cc[1])
//                            ->cc($cc[2])
//                            ->cc($cc[3])
                        ->subject($subject)
                        ->html($details)
                        ->attachData($content, $fname . '_acknowledgement.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                });
                $application->ack_mail_sent = 1;
                $application->save();
            } catch (\Exception $e) {
                $application->ack_mail_sent = 0;
                $application->save();
                Log::error('Failed to send ack email: ' . $e->getMessage());
            }
        }
        if ($application->email_id == null){
            $application->ack_mail_sent = 0;
            $application->save();
        }
    }
}
