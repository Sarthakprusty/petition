@extends('layout')

@section('content')
    <button class="btn btn-outline-primary" onclick="printLetter()">Print</button>
    <div id="myDiv">
        <style>
            .letter {
                font-family: Arial, sans-serif;
                size: A4;
                width: 210mm;
                height: 277mm;
                margin-left: 10%;
                padding: 15mm;
                border: 1px solid #ccc;
                background-color: #FBD485;
                font-size: 70%;
            }
            .centered {
                text-align: center;
            }
            @media print {
                .letter {
                    page-break-after:always;
                    margin: 0 auto;
                }
            }
        </style>

        @php
            $loopData = isset($applications) && !empty($applications) ? $applications : [$application];
        @endphp

        @foreach($loopData as $application)
            <div class="letter">
                <meta charset="UTF-8">
                <div class="centered"> <!-- Wrap the centered part in a div with the "centered" class -->
                    <table align='center'>
                        <tr><td nowrap=nowrap align='center' >
                                <img src="{{ asset('storage/logo.png') }}" alt='National Symbol' style=' width: 30%' />
                            </td></tr>
                    </table>
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
                            @endphp
                            {{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d/m/Y') : '' }}
                        </div>
                    </div>
                </div>

                <br>

                <div>विषय/Subject:</div>
                <p>कृपया, उपर्युक्त विषय पर, भारत के राष्ट्रपति जी को सम्बोधित दिनांक: {{$application->letter_date->format("d/m/Y")}} की स्वतः स्पष्ट याचिका उपयुक्त ध्यानाकर्षण के लिए संलग्न है /</p>
                <p>Enclosed please find for appropriate attention a petition dated: {{$application->letter_date->format("d/m/Y")}} addressed to the President of India on the above subject matter, which is self explanatory.</p>

                <br>

                <p>याचिका पर की गई कार्रवाई की सूचना सीधे याचिकाकर्ता को दे दी जाये /</p>
                <p>Action taken on the petition may please be communicated to the petitioner directly.</p>


                <br><br><br>


                <div class="row">
                    <div class="col-sm-6">
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
                    <div class="col-sm-6">
                        <p>आपसे अनुरोध है की मामले में आगे जानकारी के लिए, उपर्युक्त्त प्रेषिती से सीधे संपर्क करेंं /</p>
                        <p>You are further requested to liaise with the aforementioned addressee directly for further information in the matter.</p>
                    </div>
                </div>
                <br>
                <div class="row">
{{--                    <div class="col-9"></div>--}}
{{--                    <div class="col-3 d-flex justify-content-center align-items-center">--}}
{{--                        <img src="{{ asset('storage/logo.png') }}" alt='National Symbol' style=' width: 26%' />--}}
{{--                    </div>--}}
                </div>

                <strong>
                    <p style="text-align: right">(चिराब्राता सरकार {{Auth::user()->username}})</p>
                    <p style="text-align: right">अवर सचिव Under Secretary</p>
                </strong>

                <br>
                <div class="centered">
                    <div class="header">
                        बुक पोस्ट<br>
                        <div style="text-decoration: underline;">भारत सरकार सेवार्थ / ON INDIA GOVERNMENT SERVICE</div>

                    </div></div><br>
                <div >Sl.No:</div>
                <div>{{$application->reg_no}}</div>
                <div class="row">
                    <div class="col">
                        <label>द्वारा / From:</label> राष्ट्रपति सचिवालय<br>
                        President's Secretariat<br>
                        राष्ट्रपति भवन<br>
                        Rashtrapati Bhavan<br>
                        नई दिल्ली 110004<br>NEW DELHI-110004
                    </div>
                    <div class="col">
                        <label>To:</label>
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
                </div> <br>
                <div class="centered">
                    <div class="note">
                        Note:- You may use <a href="https://helpline.rb.nic.in" style="text-decoration: underline;">https://helpline.rb.nic.in</a> for submitting your petition/grievance online.
                    </div>
                    <div class="note">
                        नोट: आप अपनी याचिका दर्ज करने के लिए <a href="https://helpline.rb.nic.in" style="text-decoration: underline;">https://helpline.rb.nic.in</a> का उपयोग कर सकते हैं.
                    </div>
                </div></div>
        @endforeach

        <script>
            function printLetter() {
                var printContents = document.getElementById('myDiv').innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
@endsection
