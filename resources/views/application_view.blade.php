@extends('layoutTwo')

@section('contentView')
    <nav aria-label="breadcrumb" style="background: #edeeee">
        <ol class="breadcrumb" style="margin-left: 4%">
            <img src="https://cdn-icons-png.flaticon.com/512/2815/2815154.png" alt="Home" style="width: 3.5%; cursor: pointer;" onclick="window.location.href='{{ route('applications.index') }}';">
            <li class="breadcrumb-item" style="font-size: 160%;color: #1d00ff"><a href={{route('applications.index')}}>Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"style="font-size: 160%;">Applications / {{$app->reg_no}}</li>
        </ol>
    </nav>

    <style>
        body{margin-top:20px;
            background:#eee;
        }


        .project-people img {
            width: 32px;
            height: 32px;
        }
        .project-title a {
            font-size: 14px;
            color: #676a6c;
            font-weight: 600;
        }
        .project-list table tr td {
            border-top: none;
            border-bottom: 1px solid #e7eaec;
            padding: 15px 10px;
            vertical-align: middle;
        }
        .project-manager .tag-list li a {
            font-size: 10px;
            background-color: white;
            padding: 5px 12px;
            color: inherit;
            border-radius: 2px;
            border: 1px solid #e7eaec;
            margin-right: 5px;
            margin-top: 5px;
            display: block;
        }
        .project-files li a {
            font-size: 11px;
            color: #676a6c;
            margin-left: 10px;
            line-height: 22px;
        }

        .profile-image img {
            width: 96px;
            height: 96px;
        }

        .feed-activity-list .feed-element {
            border-bottom: 1px solid #e7eaec;
        }
        .feed-element:first-child {
            margin-top: 0;
        }
        .feed-element {
            padding-bottom: 15px;
        }
        .feed-element,
        .feed-element .media {
            margin-top: 15px;
        }
        .feed-element,
        .media-body {
            overflow: hidden;
        }
        .feed-element > .pull-left {
            margin-right: 10px;
        }
        .feed-element img.img-circle,
        .dropdown-messages-box img.img-circle {
            width: 38px;
            height: 38px;
        }
        .feed-element .well {
            border: 1px solid #e7eaec;
            box-shadow: none;
            margin-top: 10px;
            margin-bottom: 5px;
            padding: 10px 20px;
            font-size: 11px;
            line-height: 16px;
        }
        .feed-element .actions {
            margin-top: 10px;
        }
        .feed-element .photos {
            margin: 10px 0;
        }
        .feed-photo {
            max-height: 180px;
            border-radius: 4px;
            overflow: hidden;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .file-list li {
            padding: 5px 10px;
            font-size: 11px;
            border-radius: 2px;
            border: 1px solid #e7eaec;
            margin-bottom: 5px;
        }
        .file-list li a {
            color: inherit;
        }
        .file-list li a:hover {
            color: #1ab394;
        }
        .user-friends img {
            width: 42px;
            height: 42px;
            margin-bottom: 5px;
            margin-right: 5px;
        }

        .ibox {
            clear: both;
            margin-bottom: 25px;
            margin-top: 0;
            padding: 0;
        }
        .ibox.collapsed .ibox-content {
            display: none;
        }
        .ibox.collapsed .fa.fa-chevron-up:before {
            content: "\f078";
        }
        .ibox.collapsed .fa.fa-chevron-down:before {
            content: "\f077";
        }
        .ibox:after,
        .ibox:before {
            display: table;
        }
        .ibox-title {
            -moz-border-bottom-colors: none;
            -moz-border-left-colors: none;
            -moz-border-right-colors: none;
            -moz-border-top-colors: none;
            background-color: #ffffff;
            border-color: #e7eaec;
            border-image: none;
            border-style: solid solid none;
            border-width: 3px 0 0;
            color: inherit;
            margin-bottom: 0;
            padding: 14px 15px 7px;
            min-height: 48px;
        }
        .ibox-content {
            background-color: #ffffff;
            color: inherit;
            padding: 15px 20px 20px 20px;
            border-color: #e7eaec;
            border-image: none;
            border-style: solid solid none;
            border-width: 1px 0;
        }
        .ibox-footer {
            color: inherit;
            border-top: 1px solid #e7eaec;
            font-size: 90%;
            background: #ffffff;
            padding: 10px 15px;
        }
        ul.notes li,
        ul.tag-list li {
            list-style: none;
        }
        .button {
            background-color: #90D70DFF;
            border: none;
            color: black;
            padding: 12% 21%;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 100%;
        }
        .buttonRed {
            background-color: #C20606FF;
            border: none;
            color: white;
            padding: 12% 25%;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 100%;
        }
    </style>

    <div class="container" style="width: 90%">
        <div class="row">
            @if ((isset($_GET['submit']) && $_GET['submit'] === 'Details' && $noteblock) || (isset($_GET['submit']) && $_GET['submit'] === 'final reply' && $finalreplyblock) || (isset($_GET['submit']) && $_GET['submit'] === 'Details' && $signbutton))
                <div class="col-md-9">
                    @endif

                @if($notecheck)
                   @if (isset($statuses) && $statuses->isNotEmpty())
                        <div class="card text-white bg-info mb-3">
                            <div class="card-header">Note:</div>
                            <div class="card-body">
                                @foreach ($statuses as $status)
                                    <p class="card-text">
                                        {{ $status->user ? $status->user->username : 'N/A' }} - {{ $status->pivot->remarks }}
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
                    @if($hasActiveStatusFive && $app->reply)
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-header">Final Reply:</div>
                            <div class="card-body">
                                {{$app->reply}}
                            </div>
                        </div>
                    @endif
                    <div class="card shadow">
                    <div class="ibox-content">
                        <div class="col-lg-12">
                            <div class="m-b-md">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h1 style="font-size: 250%;margin-left: 1%; color: #005fff;">Applicant info</h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="spacing" style="margin-top: 2%;"></div>
                    <div class="row">
                        <div class="col-lg-5">
                            <dl class="dl-horizontal">
                                <dt style="text-decoration: underline; color: black;">Registration number</dt> <dd>
                                    @if($app->reg_no)
                                        <span class="label label-primary">{{$app->reg_no}}</span></dd>
                                @else
                                    <br>
                                @endif
                            </dl>
                        </div>
                            <div class="col-lg-5">
                            <dl class="dl-horizontal">
                                <dt style="text-decoration: underline; color: black;">Status</dt><dd>
                                    <span class="label label-primary">{{ $app->statuses()->where('application_status.active', 1)->first()?->status_desc ?? '' }}</span></dd>
                                    <br>
                            </dl>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-5">
                            <dl class="dl-horizontal">
                                <dt>Applicant Name</dt>
                                <dd>@if(($app->applicant_title)||($app->applicant_name))
                                        @if ($app->applicant_title)
                                            {{$app->applicant_title}}
                                        @endif
                                        {{$app->applicant_name}}
                                    @else
                                        <br>
                                    @endif
                                </dd>
                                <div class="spacing" style="margin-top: 3px;"></div>

                                <dt>Address:</dt> <dd> {{$app->address}}<br/>
                                    @if(($app->country)||($app->state)||($app->pincode))
                                        @if ($app->country === 'I')
                                            INDIA
                                        @elseif ($app->country === 'O')
                                            Others
                                        @elseif ($app->country === 'U')
                                            USA
                                        @endif
                                        @if ($app->state)
                                            {{ ($app->country === 'I' || $app->country === 'O') ? ', ' : '' }}{{$app->state->state_name}}
                                        @endif
                                        {{$app->state && $app->pincode ? ', ' : ''}}{{$app->pincode}}
                                    @else
                                        <br>
                                    @endif
                                </dd>
                                <div class="spacing" style="margin-top: 3px;"></div>

                                <dt>Applicant Contact info</dt> <dd>
                                    @if ($app->phone_no)
                                        {{$app->phone_no}},
                                    @endif
                                    @if ($app->mobile_no)
                                         {{$app->mobile_no}}
                                    @endif
                                    <br>{{$app->email_id}}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-lg-7" id="cluster_info">
                            <dl class="dl-horizontal">
                                <dt>Gender</dt> <dd>@if ($app->gender === 'M')
                                        Male
                                    @elseif ($app->gender === 'F')
                                        Female
                                    @elseif ($app->gender === 'O')
                                        Others
                                    @else
                                        <br>
                                    @endif</dd>
                                <div class="spacing" style="margin-top: 3px;"></div>
                                <dt>Designation/organization:</dt> <dd>
                                    @if($app->org_from)
                                        {{$app->org_from}}
                                    @else
                                        <br>
                                    @endif
                                </dd>
                                <br>
                                <div class="spacing" style="margin-top: 3px;"></div>
                                <dt>Application Lang.</dt> <dd>  @if ($app->language_of_letter === 'E')
                                        English
                                    @elseif ($app->language_of_letter === 'O')
                                        Others
                                    @elseif ($app->language_of_letter === 'H')
                                        Hindi
                                    @else
                                        <br>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="spacing" style="margin-top: 2%;"></div>
                    <hr class="row-divider">
                    <div class="spacing" style="margin-top: 2%;"></div>
                    <div class="row">
                        <div class="col-md-8">
                            <h1 style="font-size: 250%;margin-left: 1%; color: #005fff;">Application Details</h1>
                        </div>
                        <div class="col-md-4" style="display: flex; flex-direction: row; justify-content: flex-end; align-items: center;">
                            <dd style="font-size: 190%;">{{ $app->created_at->format("d/m/Y")}}</dd>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="spacing" style="margin-top: 1%;"></div>
                        <div class="list-group">
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">eOffice Diary No</span>
                                <span class="float-end" style="text-align: right">{{$app->letter_no}}</span>
                            </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Letter Date</span>
                                <span class="float-end" style="text-align: right">{{ $app->letter_date ? $app->letter_date->format("d/m/Y") : 'N/A' }}</span>
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
                                <span class="float-end" style="text-align: right">
                                    {{$app->grievance_category ? $app->grievance_category->grievances_desc : ''}}
                                </span>
                            </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Action</span>
                                <span class="float-end" style="text-align: right">
                                    @if ($app->action_org === 'N')
                                        No Action
                                    @elseif ($app->action_org === 'F')
                                        Forward to Central Govt. Ministry/Department
                                    @elseif ($app->action_org === 'S')
                                        Forward to State Govt.
                                    @elseif ($app->action_org === 'M')
                                        Miscellaneous
                                    @else
                                    @endif
                                </span>
                            </div>
                            @if($app->reason)
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Reason</span>
                                <span class="float-end" style="text-align: right">
                                    {{$app->reason ? $app->reason->reason_desc : ''}}</span>
                            </div>
                            @elseif($app->department_org)
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Ministry/Department</span>
                                <span class="float-end" style="text-align: right">
                                    {{$app->department_org ? $app->department_org->org_desc : ''}}</span>
                            </div>
                            @endif
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Remark</span>
                                <span class="float-end" style="text-align: right">{{$app->remarks}}</span>
                            </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold"> PetitionFile</span>
                                @if($app->file_path)
                                    <span class="float-end" style="text-align: right;text-decoration: underline;color: #1d00ff">
                                        <a href="{{url('/api/getFile/'.$app->file_path)}}" target="_blank">
                                          View File
                                        </a>
                                    </span>
                                @endif
                            </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Acknowledgement File</span>
                                @if($app->acknowledgement_path)
                                    <span class="float-end" style="text-align: right;text-decoration: underline;color: #1d00ff">
                                        <a href="{{url('/api/getFile/'.$app->acknowledgement_path)}}" target="_blank">
                                          View File
                                        </a>
                                    </span>
                                @endif
                            </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Forwarded File</span>
                                @if($app->forwarded_path)
                                    <span class="float-end" style="text-align: right;text-decoration: underline;color: #1d00ff">
                                        <a href="{{url('/api/getFile/'.$app->forwarded_path)}}" target="_blank">
                                          View File
                                        </a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
            @if (isset($_GET['submit']) && $_GET['submit'] === 'Details' && $noteblock)
                </div>
                <div class="col-md-3">
                    <form method="post" action="{{ route('applications.updateStatus', ['application_id' => $app->id]) }}" id="submitstatusform">
                        @csrf
                        <div class="wrapper wrapper-content project-manager">
                            <div class="spacing" style="margin-top: 3%;"></div>
                            <strong><label style="font-size: 130%" for="remarks">Note:</label></strong>

                            <div class="spacing" style="margin-top: 2%;"></div>
                            <div class="row" style="margin-left: 0%; margin-right: 1.5%;">
                                <textarea class="form-control" id="remarks" name="remarks" style="height: 200px;" placeholder="abc....">{{ old('remarks') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-6" style="text-align: right">
                                    <button type="submit" class="button" name="submit" value="Approve" onclick="return confirm('Are you sure, You want to Approve this petition?')">Approve</button>
                                </div>
                                <div class="col-6" style="text-align: left">
                                    <button type="submit" class="buttonRed" name="submit" value="Return" onclick="return confirm('Are you sure, You want to Reject this petition?')">Return</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @elseif (isset($_GET['submit']) && $_GET['submit'] === 'Details' && $signbutton)
                    </div>
                        <div class="col-md-3">
                            <div class="wrapper wrapper-content project-manager">
                                <div class="spacing" style="margin-top: 3%;"></div>
                                <strong><label style="font-size: 130%" for="remarks">Note:</label></strong>

                                <div class="spacing" style="margin-top: 2%;"></div>
                                <div class="row" style="margin-left: 0%; margin-right: 1.5%;">
                                    You haven't inserted any signature yet Or System doesn't have your signature, in order to Approve or Reject kindly insert Your Signature and Basic details by clicking this button down below.
                                </div>
                                <div class="row">
                                    <div class="col-2"></div>
                                    <div class="col-6" style="text-align: right" >
                                        <a href="{{ route('authority.create') }}" class="button">Sign</a>
                                    </div>
                                </div>
                            </div>
                        </div>

            @elseif (isset($_GET['submit']) && $_GET['submit'] === 'final reply' && $finalreplyblock)
                </div>
                    <div class="col-md-3">
                        <form method="POST" action="{{route('applications.store')}}" id="submitForm">
                            @csrf
                            @if(isset($app->id))
                                <input type="hidden" value="{{$app->id}}" name="id" >
                            @endif
                            <div class="wrapper wrapper-content project-manager">
                                <div class="spacing" style="margin-top: 3%;"></div>
                                <strong><label style="font-size: 130%" for="reply">Final Reply:</label></strong>
                                <div class="spacing" style="margin-top: 2%;"></div>
                                <div class="spacing" style="margin-top: 2%;"></div>
                                <div class="row" style="margin-left: 0%; margin-right: 1.5%;">
                                    <textarea class="form-control" id="reply" name="reply" style="height: 200px;" placeholder="abc....">{{ old('reply') }}</textarea>
                                </div>

                                <div class="row">
                                    <div style="text-align: center">
                                        <button type="submit" class="button" style="padding: 5% 10%" name="submit" value="Submit" onclick="return confirm('Are you sure,you want to Submit Final Reply? it cannot be changed again.')">Submit Reply</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
            @endif



{{--                    <script>--}}
{{--                        $(document).ready(function() {--}}
{{--                            $('.list-group-item').each(function() {--}}
{{--                                var fieldValue = $(this).find('.float-end').text().trim(); // Get the value of the field inside each list-group-item--}}

{{--                                if (fieldValue === '') {--}}
{{--                                    $(this).hide(); // Hide the list-group-item if the field value is empty--}}
{{--                                }--}}
{{--                            });--}}
{{--                        });--}}
{{--                    </script>--}}
        </div>
    </div>


@endsection

