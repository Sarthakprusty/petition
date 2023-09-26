@extends('layout')

@section('content')
    <button class="btn btn-outline-primary" style="margin-left: 47.5%" onclick="printLetter()">Print</button>
    <div id="myDiv">
        <style>
            .letter {
                font-family: Arial, sans-serif;
                size: A4;
                width: 210mm;
                height: 297mm;
                margin-left: 10%;
                padding: 15mm;
                border: 1px solid #ccc;
                /*background-color: #FBD485;*/
                background-color: white;

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
                    <div>
                        @if (substr($application->reg_no, 0, 2) === 'p1')
                            (जनता-I अनुभाग)
                        @elseif (substr($application->reg_no, 0, 2) === 'p2')
                            (जनता-II अनुभाग)
                        @endif</div>
                    <div>@if (substr($application->reg_no, 0, 2) === 'P1')
                            (PUBLIC-I SECTION)
                        @elseif (substr($application->reg_no, 0, 2) === 'P2')
                            (PUBLIC-II SECTION)
                        @endif</div>
                    <div>Rashtrapati Bhavan</div>
                    <div>नई दिल्ली - 110004</div>
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
                @if($application->department_org && $application->department_org->org_desc_hin)
                    <div>{{$application->department_org->org_desc_hin}} </div>
                @endif
                @if($application->department_org && $application->department_org->org_desc)
                    <div>{{$application->department_org->org_desc}} </div>
                @endif
                @if($application->department_org && $application->department_org->org_address_hin)
                    <div>{{$application->department_org->org_address_hin}} </div>
                @endif
                @if($application->department_org && $application->department_org->org_address)
                    <div>{{$application->department_org->org_address}}</div>
                @endif
                @if($application->department_org && $application->department_org->pincode)
                    <div>{{$application->department_org->pincode}}</div>
                @endif
                <br>
                <div>
                    <span style="font-weight: bold;">Sl.No: {{$application->reg_no}}</span>
                    <span style="float: right">दिनांक/Dated: {{$application->created_at->format('d/m/Y')}}</span>
                </div>

                <br>

                <div>विषय/Subject: REQUEST FOR ATTENTION ON HIS/HER PETITION</div><br>
                <p>कृपया, उपर्युक्त विषय पर, भारत के राष्ट्रपति जी को सम्बोधित दिनांक: {{$application->letter_date?$application->letter_date->format("d/m/Y"): 'रहित'}} की स्वतः स्पष्ट याचिका उपयुक्त ध्यानाकर्षण के लिए संलग्न है |</p>
                <p> Enclosed please find for appropriate attention a petition dated: {{$application->letter_date?$application->letter_date->format("d/m/Y"): null}} addressed to the President of India on the above subject matter, which is self explanatory.</p>

                <br>

                <p>याचिका पर की गई कार्रवाई की सूचना सीधे याचिकाकर्ता को दे दी जाये |</p>
                <p>Action taken on the petition may please be communicated to the petitioner directly.</p>

                <br><br><br>

                <div class="row" style="padding-left: 84%">
                    <img src="{{url('/api/signFile/'.$application->authority->Sign_path)}}" style='width: 100px;' />
                </div>


                <strong>
                    <p style="text-align: right">(चिराब्राता सरकार {{$application->authority->name}})</p>
                    <p style="text-align: right">अवर सचिव Under Secretary</p>
                </strong>
                <br><br>
                <table>
                    <tr>
                        <td>
                            <strong>प्रतिलिपि/ Copy to:</strong><br>
                            {{$application->applicant_title}} {{$application->applicant_name}}
                            <br>
                            {{$application->address}}
                            <br>
                            @if ($application->state)
                                {{$application->state->state_name}} {{$application->state && $application->pincode ? '-' : ''}}{{$application->pincode}}
                                <br>
                            @endif
                            @if ($application->country === 'I')
                                INDIA
                            @elseif ($application->country === 'O')
                                Others
                            @elseif ($application->country === 'U')
                                USA
                            @endif
                        </td>
                        <td style="padding-left: 25mm;">
                            <p>
                                आपसे अनुरोध है की मामले में आगे जानकारी के लिए, उपर्युक्त्त प्रेषिती से सीधे संपर्क करेंं |
                                <br>
                                You are further requested to liaise with the aforementioned addressee directly for further information in the matter.
                            </p>
                        </td>
                    </tr>
                </table>

            </div>
        @endforeach
    </div>
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
