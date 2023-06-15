@extends('layout')

@section('content')
    <style>
        .letter {
            font-family: Arial, sans-serif;
            size: A4;
            width: 210mm;
            height: 297mm;
            margin-left: 10%;
            padding: 15mm;
            border: 1px solid #ccc;
            background-color: #FBD485;
        }
        .centered {
            text-align: center;
        }
        @media print {
            .letter {
                page-break-after:always;
            }
        }
    </style>
    <div class="letter">
        <meta charset="UTF-8">
        <div class="centered"> <!-- Wrap the centered part in a div with the "centered" class -->
            <table align='center'>
                <tr><td nowrap=nowrap align='center' >
                        <img src='https://us.123rf.com/450wm/queenann5/queenann51708/queenann5170800470/84731362-emblem-of-india-lion-capital-of-ashoka.jpg?ver=6' alt='National Symbol' style='height: 60px; width: 40px' />
                    </td></tr>
            </table>
            <h2>राष्ट्रपति सचिवालय</h2>
            <h2>President's Secretariat</h2>
            <h4>(जनता-I अनुभाग)</h4>
            <h4>(PUBLIC-I SECTION)</h4>
            <h3>Rashtrapati Bhavan</h3>
            <h3>New Delhi - 110004</h3>
        </div>

        <br><br>

        <h3>सेवा में/To,</h3>
        <h3>सचिव, भारत सरकार DONT KNOW</h3>
        <h3>SECRETARY TO THE GOVERNMENT OF INDIA DONT KNOW</h3>
        <h3>{{$application->department_org->org_desc}} if?</h3>
        <h3>Shastri Bhavan, C Wing New Delhi, DONT KNOW</h3>
        <h3>New Delhi - 110001</h3>

        <br><br>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4 style="font-weight: bold; white-space: nowrap;">Sl.No.:{{$application->reg_no}}</h4>
            <h4 style="white-space: nowrap;">दिनांक/Dated: 14 Mar 2012 dont know</h4>
        </div>

        <br><br>

        <h4>विषय/Subject:</h4>
        <p>कृपया, उपर्युक्त विषय पर, भारत के राष्ट्रपति जी को सम्बोधित दिनांक: {{$application->letter_date->format("d/m/Y")}} की स्वतः स्पष्ट याचिका उपयुक्त ध्यानाकर्षण के लिए संलग्न है /</p>
        <p>Enclosed please find for appropriate attention a petition dated: {{$application->letter_date->format("d/m/Y")}} addressed to the President of India on the above subject matter, which is self explanatory.</p>

        <br>

        <p>याचिका पर की गई कार्रवाई की सूचना सीधे याचिकाकर्ता को दे दी जाये /</p>
        <p>Action taken on the petition may please be communicated to the petitioner directly.</p>


        <br><br>


        <strong>
        <p style="text-align: right">(चिराब्राता सरकार {{Auth::user()->username}})</p>
        <p style="text-align: right">अवर सचिव Under Secretary</p>
        </strong>

        <br><br>

        <h4>प्रतिलिपि/ Copy to:</h4>
        <h4>{{$application->applicant_title}} {{$application->applicant_name}}</h4>
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

        <br><br>

        <p>आपसे अनुरोध है की मामले में आगे जानकारी के लिए, उपर्युक्त्त प्रेषिती से सीधे संपर्क करेंं /</p>
        <p>You are further requested to liaise with the aforementioned addressee directly for further information in the matter.</p>
    </div>
@endsection
