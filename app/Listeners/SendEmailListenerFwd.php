<?php

namespace App\Listeners;

use App\Events\SendEmailEventFwd;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendEmailListenerFwd implements ShouldQueue
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
     * @param  SendEmailEventFwd  $event
     * @return void
     */
    public function handle(SendEmailEventFwd $event)
    {
        $application = $event->application;
        $name = $event->name;
        $name_hin = $event->name_hin;

        if (($application->department_org->mail !== null)&&($application->mail_sent == 0 || $application->mail_sent == '' ) && ($application->forwarded_path !== null)) {
//                    $email = $application->department_org->mail;
            $fname = str_replace('/', '_', $application->reg_no);
            $email = 'sayantan.saha@gov.in';
            $cc = [];
            $cc[] = 'prustysarthak123@gmail.com';
            $cc[] = 'shantanubaliyan935@gmail.com';
//                    $email = 'us.petitions@rb.nic.in';
//                    $cc = [];
//                    $cc[] = 'sayantan.saha@gov.in';
//                    $cc[] = 'so-public1@rb.nic.in';
//                    $cc[] = 'so-public2@rb.nic.in';
//                    $cc[] = 'prustysarthak123@gmail.com';
            $subject = $application->reg_no;
            $details = "महोदय / महोदया,<br>
                                    Sir / Madam,<br><br>
                                    कृपया उपरोक्त विषय पर भारत के राष्ट्रपति जी को संबोधित स्वतः स्पष्ट याचिका उपयुक्त ध्यानाकर्षण के लिए संलग्न है। याचिका पर की गई कार्रवाई की सूचना सीधे याचिकाकर्ता को दे दी जाये।<br>
                                    Attached please find for appropriate attention a petition addressed to the President of India which is self-explanatory. Action taken on the petition may please be communicated to the petitioner directly.<br>
                                    सादर,<br>
                                    regards,<br><br>
                                    ($name)<br>
                                    ($name_hin)<br>
                                    अवर सचिव<br>
                                    Under Secretary<br>
                                    राष्ट्रपति सचिवालय<br>
                                    President's Secretariat<br>
                                    राष्ट्रपति भवन, नई दिल्ली<br>
                                    Rashtrapati Bhavan, New Delhi";

            $content = storage::disk('upload')->get(base64_decode($application->forwarded_path));
            $file = storage::disk('upload')->get(base64_decode($application->file_path));
            try {
                $callback = function ($message) use ($email, $subject, $content, $cc, $file, $fname, $details) {
                    $message->to($email)->cc($cc[0])
                        ->cc($cc[1])
//                                    ->cc($cc[2])
//                                    ->cc($cc[3])
                        ->subject($subject)
                        ->html($details)
                        ->attachData($content, $fname . '_forward letter.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    if (!empty($file)) {
                        $message->attachData($file, $fname . '_file.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    }
                };
                Mail::send([], [], $callback);
                $application->mail_sent = 1;
                $application->save();
            } catch (\Exception $e) {
                $application->mail_sent = 0;
                $application->save();
                Log::error('Failed to send fwd email: ' . $e->getMessage());
            }
        }
        if ($application->department_org->mail == null){
            $application->mail_sent = 0;
            $application->save();
        }
    }
}
