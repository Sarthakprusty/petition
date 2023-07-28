<?php

namespace App\Listeners;

use App\Events\GeneratePdfEventAck;
use App\Events\SendEmailEventAck;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class GeneratePdfListenerAck implements ShouldQueue
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
     * @param  GeneratePdfEventAck  $event
     * @return void
     */
    public function handle(GeneratePdfEventAck $event)
    {
        $postParameter = $event->postParameter;
        $application = $event->application;

        // Perform the cURL request and generate the PDF
        //server
        $curlHandle = curl_init('http://10.197.148.102:8081/getMLPdf');
        //local
//        $curlHandle = curl_init('http://localhost:8081/getMLPdf');
        //sir
//        $curlHandle = curl_init('http://10.21.160.179:8081/getMLPdf');

        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        $curlResponse = curl_exec($curlHandle);
        curl_close($curlHandle);

        // Check if the response contains a valid PDF
        if ($curlResponse && substr($curlResponse, 0, 4) == '%PDF') {
            // Save the PDF to storage
            $fname = str_replace('/', '_', $application->reg_no);
            $fileName = $fname . '_acknowledgement.pdf';
            $path = 'applications/' . $application->id . '/' . $fileName;
            if (Storage::disk('upload')->put($path, $curlResponse)) {
                $application->acknowledgement_path = base64_encode($path);
                $application->save();
            }
        }
        event(new SendEmailEventAck($application));
    }
}
