@extends('layout')

@section('content')
    <div class="details">
        <div class="row" id="person details">
            <label class="form-label" style="margin-left: 2%;">Applicant info:</label>
            <div class="col">
                <div class="card shadow">
                    <div class="list-group">
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Registration number</span>
                            <span class="float-end">{{$app->reg_no}}</span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Applicant Name</span>
                            <span class="float-end">{{$app->applicant_title}} {{$app->applicant_name}}</span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Address</span>
                            <span class="float-end" style="text-align: right">{{$app->address}}<br/>
                                @if ($app->country === 'I')
                                    INDIA
                                @elseif ($app->country === 'O')
                                    Others
                                @else
                                    USA
                                @endif ,
                                {{$app->state->state_name}},{{$app->pincode}}
                            </span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Designation/organization</span>
                            <span class="float-end" style="text-align: right">{{$app->org_from}}</span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Applicant Contact info</span>
                            <span class="float-end" style="text-align: right">{{$app->phone_no}}, +91{{$app->mobile_no}}<br>{{$app->email_id}}</span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Applicant Gender</span>
                            <span class="float-end" style="text-align: right">
                                @if ($app->gender === 'M')
                                    Male
                                @elseif ($app->gender === 'F')
                                    Female
                                @else
                                    Others
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="spacing" style="margin-top: 3%;"></div>
        <div class="row" id="application details">
            <label class="form-label" style="margin-left: 2%;">Application Details:</label>
            <div class="col">
                <div class="card shadow">
                    <div class="list-group">
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Application Date</span>
                            <span class="float-end" style="text-align: right">{{$app->letter_date->format("d/m/Y")}}</span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Application Lang.</span>
                            <span class="float-end" style="text-align: right">
                                @if ($app->language_of_letter === 'E')
                                    English
                                @elseif ($app->language_of_letter === 'O')
                                    Others
                                @else
                                    Hindi
                                @endif
                            </span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">eOffice Diary No</span>
                            <span class="float-end" style="text-align: right">{{$app->letter_no}}</span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Letter Subject</span>
                            <span class="float-end" style="text-align: right">{{$app->letter_subject}}</span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Letter Body</span>
                            <span class="float-end" style="text-align: right">{{$app->letter_body}}</span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Acknowledgement</span>
                            <span class="float-end" style="text-align: right">
                                @if ($app->acknowledgement === 'Y')
                                    YES
                                @else
                                    NO
                                @endif
                            </span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Grievances Subject</span>
                            <span class="float-end" style="text-align: right">{{$app->grievance_category->grievances_desc}}</span>
                        </div>
                        <div class="list-group-item">
                            <span class="float-start" style="font-weight: bold">Action</span>
                            <span class="float-end" style="text-align: right">
                            @if ($app->fee_received_place === 'N')
                                   No Action
                                @elseif ($app->fee_received_place === 'F')
                                    Forward to Central Govt. Ministry/Department
                                @else
                                    Miscellaneous
                                @endif
                        </span>
                        </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Min/Dept/Gov Code</span>
                                <span class="float-end" style="text-align: right">{{$app->min_dept_gov_code}}</span>
                            </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Ministry/Department</span>
                                <span class="float-end" style="text-align: right">{{$app->department_org->org_desc}}</span>
                            </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Remark</span>
                                <span class="float-end" style="text-align: right">{{$app->remarks}}</span>
                            </div>
                    </div>
                </div>

                <div class="spacing" style="margin-top: 3%;"></div>
                <form method="post" action="{{ route('reply', [$app->id]) }}">
                    <div class="row">
                        <button type="submit" class="btn btn-primary">Reply</button>
                    </div>
                </form>
        </div>
    <script>
        $(document).ready(function() {
            $('.list-group-item').each(function() {
                var fieldValue = $(this).find('.float-end').text().trim(); // Get the value of the field inside each list-group-item

                if (fieldValue === '') {
                    $(this).hide(); // Hide the list-group-item if the field value is empty
                }
            });
        });
    </script>
    </div>
@endsection
