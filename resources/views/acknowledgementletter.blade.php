

{{--    <button class="btn btn-outline-primary" style="margin-left: 47.5%" onclick="printLetter()">Print</button>--}}
{{--    <div class="row"></div>--}}
    <div id="myDiv">
        <style>
            @font-face {
                font-family: 'DejaVu Sans';
                src: url('/public/fonts/DejaVuSans.ttf') format('truetype');
            }
            * {
                margin: 0;
                padding: 0;
                border: none;
            }
            .letter {
                font-family: Arial, "DejaVu Sans", sans-serif;
                size: A4;
                    /*width: 210mm;*/
                height: 265mm;
                padding: 15mm;
                background-color: #FBD485;
                font-size: 4mm;
            }
            .centered {
                text-align: center;
            }
            /*@media print {*/
            /*    .letter {*/
            /*        page-break-after:always;*/
            /*        margin: 0 auto;*/
            /*    }*/
            /*}*/
            /*body {*/
            /*    font-family: 'NotoSans', sans-serif;*/
            /*}*/
        </style>
        @php
            $loopData = isset($applications) && !empty($applications) ? $applications : [$application];
        @endphp

        @foreach($loopData as $application)


        <div class="letter">
        <meta charset="UTF-8">
        <div class="centered">
            <div class="row">
            <img src="{{ asset('storage/logo.png') }}" alt='National Symbol' style=' width: 35px;padding-left: 1%' />
            </div>
            <div>राष्ट्रपति सचिवालय</div>
            <div>President's Secretariat</div>
            <div>(जनता-I अनुभाग)</div>
            <div>(PUBLIC-I SECTION)</div>
            <div>Rashtrapati Bhavan</div>
            <div>New Delhi - 110004</div>
        </div>

        <br>

        <div>सेवा में/To,</div>
        @if($application->department_org && $application->department_org->org_head_hi)
            <div>{{$application->department_org->org_head_hi}}</div>
        @endif
        @if($application->department_org && $application->department_org->org_head)
            <div>{{$application->department_org->org_head}}</div>
        @endif
        @if($application->department_org && $application->department_org->org_desc)
            <div>{{$application->department_org->org_desc}} </div>
        @endif
        @if($application->department_org && $application->department_org->org_address)
            <div>{{$application->department_org->org_address}}</div>
        @endif
            <div>New Delhi - 110001</div>

        <br>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: bold; white-space: nowrap;">Sl.No:{{$application->reg_no}}</div>
            <div>
                <div style="white-space: nowrap;">दिनांक/Dated:
                    @php
                        $statusId = 4;
                        $pivot = $application->statuses->first(function ($status) use ($statusId) {
                            return $status->pivot->status_id === $statusId;
                        });
                        $createdAt = $pivot ? $pivot->pivot->created_at : null;
//                    @endphp
                    {{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d/m/Y') : '' }}
                </div>
            </div>
        </div>

            <br>

        <label>विषय/Subject: REQUEST FOR ATTENTION ON HIS/HER PETITION</label>
        <p>कृपया, उपर्युक्त विषय पर, भारत के राष्ट्रपति जी को सम्बोधित दिनांक: {{$application->letter_date->format("d/m/Y")}} की स्वतः स्पष्ट याचिका उपयुक्त ध्यानाकर्षण के लिए संलग्न है /</p>
        <p>Enclosed please find for appropriate attention a petition dated: {{$application->letter_date->format("d/m/Y")}} addressed to the President of India on the above subject matter, which is self explanatory.</p>

        <br>

        <p>याचिका पर की गई कार्रवाई की सूचना सीधे याचिकाकर्ता को दे दी जाये /</p>
        <p>Action taken on the petition may please be communicated to the petitioner directly.</p>


        <br>
            <div class="row">

                <img src="data:image/png;base64,{{ $imageBase64 }}" style="width: 100px; padding-left: 81%">
            </div>

        <strong>
        <p style="text-align: right">(चिराब्राता सरकार {{Auth::user()->authority->name}})</p>
        <p style="text-align: right">अवर सचिव Under Secretary</p>
        </strong>

        <br><br>

        <div class="row">
            <div class="col-6">
                <strong>
                    <div>प्रतिलिपि/ Copy to:</div>
                </strong>
                <div>{{$application->applicant_title}} {{$application->applicant_name}}</div>
                <p>{{$application->address}}</p>
                @if ($application->state)
                    <p>{{$application->state->state_name}} {{$application->state && $application->pincode ? '-' : ''}}{{$application->pincode}}</p>
                @endif
                <p>
                    @if ($application->country === 'I')
                        INDIA
                    @elseif ($application->country === 'O')
                        Others
                    @elseif ($application->country === 'U')
                        USA
                    @endif
                </p>
            </div>
            <div class="col-6">
                <p>आपसे अनुरोध है की मामले में आगे जानकारी के लिए, उपर्युक्त्त प्रेषिती से सीधे संपर्क करेंं /</p>
                <p>You are further requested to liaise with the aforementioned addressee directly for further information in the matter.</p>
            </div>
        </div>
    </div>
    @endforeach

    </div>
{{--    <script>--}}
{{--        function printLetter() {--}}
{{--            var printContents = document.getElementById('myDiv').innerHTML;--}}
{{--            var originalContents = document.body.innerHTML;--}}
{{--            document.body.innerHTML = printContents;--}}
{{--            window.print();--}}
{{--            document.body.innerHTML = originalContents;--}}
{{--        }--}}
{{--    </script>--}}

