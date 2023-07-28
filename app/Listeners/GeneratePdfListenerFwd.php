<?php

namespace App\Listeners;

use App\Events\GeneratePdfEventFwd;
use App\Events\SendEmailEventFwd;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeneratePdfListenerFwd implements ShouldQueue
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
     * @param  GeneratePdfEventFwd  $event
     * @return void
     */
    public function handle(GeneratePdfEventFwd $event)
    {
        $postParameter = $event->postParameter;
        $application = $event->application;
        $name = $event->name;
        $name_hin = $event->name_hin;

        $curlHandle = curl_init('http://10.197.148.102:8081/getMLPdf');
//        $curlHandle = curl_init('http://localhost:8081/getMLPdf');

        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        $curlResponse = curl_exec($curlHandle);
        if(!$curlResponse){
            Log::error('curl error'.curl_error($curlHandle));
        }
        curl_close($curlHandle);
        if ($curlResponse && substr($curlResponse, 0, 4) == '%PDF') {
            $fname = str_replace('/', '_', $application->reg_no);
            $fileName = $fname.'_forward.pdf';
            $path = 'applications/' . $application->id . '/' . $fileName;
            if (Storage::disk('upload')->put($path, $curlResponse)) {
                $application->forwarded_path = base64_encode($path);
                $application->save();
            }
        }
        event(new SendEmailEventFwd($application,$name,$name_hin));
    }
}
