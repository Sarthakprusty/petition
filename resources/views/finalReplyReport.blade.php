@extends('layoutTwo')

@section('content')
    <nav aria-label="breadcrumb" style="background: #f8f5ef">
        <ol class="breadcrumb" style="margin-left: 4%">
            <img src="https://cdn-icons-png.flaticon.com/512/2815/2815154.png" alt="Home" style="width: 3.5%; cursor: pointer;" onclick="window.location.href='{{ route('applications.index') }}';">
            <li class="breadcrumb-item" style="font-size: 160%;color: #1d00ff"><a href={{route('applications.index')}}>Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"style="font-size: 160%;">Final Reply Report</li>
        </ol>
    </nav>

    <div class="row">
        <div style="text-align: center">
            <button class="btn btn-outline-danger" style="margin-left: 3%" onclick="printLetter()">Print</button>
        </div>
    </div>
    <div class="row"></div>

    <div id="myDiv">
        <style>

            .centered {
                text-align: center;
            }
            @media print {
                .letter {
                    page-break-after:always;

                }
            }
        </style>
        <meta charset="UTF-8">
        <div class="centered"> <!-- Wrap the centered part in a div with the "centered" class -->
            <table align='center'>
                <tr><td nowrap=nowrap align='center' >
                        <img src="{{ asset('storage/logo.png') }}" alt='National Symbol' style=' width: 30%' />
                    </td></tr>
            </table>
            <div>राष्ट्रपति सचिवालय</div>
            <div>President's Secretariat</div>
            <div>(PUBLIC-I SECTION)</div>
            <div>Rashtrapati Bhavan</div>
            <div>New Delhi - 110004</div>
            <br>
            <div>Following Requests/Grievances addressed to the President of India are being forwarded to <strong>{{$name}}</strong> during the peroid from <strong>{{$date_from}}</strong> To <strong>{{$date_to}}</strong> for appropriate attention. Kindly expedite disposal/status report to the petitioner:
            </div>
        </div>
<br>
        <div class="centered">
        <table class="table table-bordered" >
            <thead>
            <tr>
                <th >SrNo</th>
                <th>Registration No.</th>
                <th>Letter Date</th>
                <th>Approving Date</th>
                <th>Petitioner Name</th>
                <th>Petitioner Address</th>
                <th>Min/Dept/Gov</th>
                <th>Final Reply</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 1;
            @endphp
            @foreach ($applications as $application)
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $application->reg_no }}</td>
                    <td>@if($application->letter_date)
                            {{ $application->letter_date->format('d/m/Y') }}
                        @endif</td>

                    <td>@php
                            $statusId = 4;
                            $pivot = $application->statuses->first(function ($status) use ($statusId) {
                                return $status->pivot->status_id === $statusId;
                            });
                            $createdAt = $pivot ? $pivot->pivot->created_at : null;
                        @endphp
                        {{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d/m/Y') : '' }}</td>
                    <td>{{ $application->applicant_name }}</td>
                    <td>{{$application->address}}<br/>
                        @if(($application->country)||($application->state)||($application->pincode))
                            @if ($application->country === 'I')
                                INDIA
                            @elseif ($application->country === 'O')
                                Others
                            @elseif ($application->country === 'U')
                                USA
                            @endif
                            @if ($application->state)
                                {{ ($application->country === 'I' || $application->country === 'O') ? ', ' : '' }}{{$application->state->state_name}}
                            @endif
                            {{$application->state && $application->pincode ? ', ' : ''}}{{$application->pincode}}
                        @else
                            <br>
                        @endif</td>
                    <td>
                        {{$application->department_org ? $application->department_org->org_desc : ''}}
                    </td>
                    <td>{{ $application->reply }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        <br><br><br>
        <div class="row" >
            <div class="col-2" style="text-align: right">
                <label class="form-label" for="letter_no">Date-{{now()->format('d/m/Y')}}</label>
            </div>
            <div class="col-9" style="text-align: right">
                <label class="form-label" for="letter_date">{{$us->name}}<br>(US)</label>
            </div>
        </div>


    </div>

    <script>
        function printLetter() {
            //alert('hii');
            var printContents = document.getElementById('myDiv').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
