@extends('layoutTwo')

@section('contentView')
<nav aria-label="breadcrumb" style="background: #edeeee">
    <ol class="breadcrumb" style="margin-left: 4%">
        <img src="https://cdn-icons-png.flaticon.com/512/2815/2815154.png" alt="Home"
            style="width: 3.5%; cursor: pointer;" onclick="window.location.href='{{ route('applications.index') }}';">
        <li class="breadcrumb-item" style="font-size: 160%;color: #1d00ff"><a
                href={{route('applications.index')}}>Home</a></li>
        <li class="breadcrumb-item active" aria-current="page" style="font-size: 160%;">Applications / {{$app->reg_no}}
        </li>
    </ol>
</nav>

<style>
    body {
        margin-top: 20px;
        background: #eee;
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

    .feed-element>.pull-left {
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

    @if($notecheck)
        @if (isset($statuses) && $statuses->isNotEmpty())
            @for ($i = 0; $i < count($statuses); $i++)
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3">
                                Note: {{ $i + 1 }}
                            </div>
                            <div class="col-9" style="text-align:right">
                                @if($statuses[$i]->pivot->status_id === 0)
                                    <b>
                                        <div style="font-style: italic">Draft</div>
                                    </b>
                                @elseif($i == 0)
                                    Forwarded
                                @elseif($i > 0)
                                    @if($statuses[$i]->pivot->status_id == 4)
                                        <div style="color:lawngreen;">Approved</div>
                                    @elseif($statuses[$i]->pivot->status_id == 5)
                                        <b>
                                            <div style="color:#fddd00;"> Final Reply</div>
                                        </b>
                                    @elseif($statuses[$i]->pivot->status_id > $statuses[$i - 1]->pivot->status_id)
                                        Forwarded
                                    @elseif(($statuses[$i]->pivot->status_id < $statuses[$i - 1]->pivot->status_id) && ($statuses[$i]->pivot->created_by == $statuses[$i - 1]->pivot->created_by))
                                        <div style="color:red;">Pulled Back</div>
                                    @elseif($statuses[$i]->pivot->status_id < $statuses[$i - 1]->pivot->status_id)
                                        <div style="color:red;">Returned</div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>
                            {{$statuses[$i]->pivot->remarks ? $statuses[$i]->pivot->remarks : 'N/A' }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-3">
                                {{ $statuses[$i]->user ? $statuses[$i]->user->employee_name : 'N/A' }}
                            </div>
                            <div class="col-9" style="text-align: right;">
                                {{ $statuses[$i]->user ? $statuses[$i]->user->username : 'N/A' }} <br>
                                {{ $statuses[$i]->pivot->created_at->format("d/m/Y h:i:s") }}

                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        @endif
    @endif
    <div class="row">
        @if ($transferOrAccept || ($noteblock) || (isset($_GET['submit']) && $_GET['submit'] === 'final reply' && $finalreplyblock) || (isset($_GET['submit']) && $_GET['submit'] === 'Details' && $signbutton))
            <div class="col-md-9">
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
                                    <h1 style="font-size: 250%;margin-left: 1%; color: #005fff;">Applicant info </h1>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="spacing" style="margin-top: 2%;"></div>
                    <div class="row">
                        <div class="col-lg-5">
                            <dl class="dl-horizontal">
                                <dt style="text-decoration: underline; color: black;">Registration number</dt>
                                <dd>
                                    @if($app->reg_no)
                                            <span class="label label-primary">{{$app->reg_no}}</span>
                                        </dd>
                                    @else
                                        <br>
                                    @endif
                            </dl>
                        </div>
                        <div class="col-lg-5">
                            <dl class="dl-horizontal">
                                <dt style="text-decoration: underline; color: black;">Status</dt>
                                <dd>
                                    <span
                                        class="label label-primary">{{ $app->statuses()->where('application_status.active', 1)->first()?->status_desc ?? '' }}</span>
                                </dd>
                                <br>
                            </dl>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-5">
                            <dl class="dl-horizontal">
                                <dt>Applicant Name</dt>
                                <dd>@if(($app->applicant_title) || ($app->applicant_name))
                                    @if ($app->applicant_title)
                                        {{$app->applicant_title}}
                                    @endif
                                    {{$app->applicant_name}}
                                @else
                                    <br>
                                @endif
                                </dd>
                                <div class="spacing" style="margin-top: 3px;"></div>

                                <dt>Address:</dt>
                                <dd> {{$app->address}}<br />
                                    @if(($app->country) || ($app->state) || ($app->pincode))
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

                                <dt>Applicant Contact info</dt>
                                <dd>
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
                                <dt>Gender</dt>
                                <dd>@if ($app->gender === 'M')
                                    Male
                                @elseif ($app->gender === 'F')
                                    Female
                                @elseif ($app->gender === 'O')
                                    Others
                                @else
                                    <br>
                                @endif
                                </dd>
                                <div class="spacing" style="margin-top: 3px;"></div>
                                <dt>Designation/organization:</dt>
                                <dd>
                                    @if($app->org_from)
                                        {{$app->org_from}}
                                    @else
                                        <br>
                                    @endif
                                </dd>
                                <br>
                                <div class="spacing" style="margin-top: 3px;"></div>
                                <dt>Application Lang.</dt>
                                <dd> @if ($app->language_of_letter === 'E')
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
                        <div class="col-md-4"
                            style="display: flex; flex-direction: row; justify-content: flex-end; align-items: center;">
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
                                <span class="float-end"
                                    style="text-align: right">{{ $app->letter_date ? $app->letter_date->format("d/m/Y") : 'N/A' }}</span>
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
                                <span class="float-start" style="font-weight: bold"> Petition File</span>
                                @if($app->file_path)
                                    <span class="float-end"
                                        style="text-align: right;text-decoration: underline;color: #1d00ff">
                                        <a href="{{url('/api/getFile/' . $app->file_path)}}" target="_blank">
                                            View File
                                        </a>
                                    </span>
                                @endif
                            </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Acknowledgement File</span>
                                @if($app->acknowledgement_path)
                                    <span class="float-end"
                                        style="text-align: right;text-decoration: underline;color: #1d00ff">
                                        <a href="{{url('/api/getFile/' . $app->acknowledgement_path)}}" target="_blank">
                                            View File
                                        </a>
                                    </span>
                                @endif
                            </div>
                            <div class="list-group-item">
                                <span class="float-start" style="font-weight: bold">Forwarded File</span>
                                @if($app->forwarded_path)
                                    <span class="float-end"
                                        style="text-align: right;text-decoration: underline;color: #1d00ff">
                                        <a href="{{url('/api/getFile/' . $app->forwarded_path)}}" target="_blank">
                                            View File
                                        </a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>


            @if ($noteblock)
                </div>
                <div class="col-md-3">
                    <form method="post" action="{{ route('applications.updateStatus', ['application_id' => $app->id]) }}"
                        id="submitstatusform">
                        @csrf
                        <div class="wrapper wrapper-content project-manager">
                            <div class="spacing" style="margin-top: 3%;"></div>
                            <strong><label style="font-size: 130%" for="remarks">Note:</label></strong>

                            <div class="spacing" style="margin-top: 2%;"></div>
                            <div class="row" style="margin-left: 0%; margin-right: 1.5%;">
                                <textarea class="form-control" id="remarks" name="remarks" style="height: 200px;"
                                    placeholder="abc....">{{ old('remarks') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-6" style="text-align: right">
                                    <button type="submit" class="button" name="submit" value="Approve"
                                        id="approve_button">Approve</button>
                                </div>
                                <div class="col-6" style="text-align: left">
                                    <button type="submit" class="buttonRed" name="submit" value="Return"
                                        id="return_button">Return</button>
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
                            You haven't inserted any signature yet Or System doesn't have your signature, in order to Approve or
                            Reject kindly insert Your Signature and Basic details by clicking this button down below.
                        </div>
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-6" style="text-align: right">
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
                            <input type="hidden" value="{{$app->id}}" name="id">
                        @endif
                        <div class="wrapper wrapper-content project-manager">
                            <div class="spacing" style="margin-top: 3%;"></div>
                            <strong><label style="font-size: 130%" for="reply">Final Reply:</label></strong>
                            <div class="spacing" style="margin-top: 2%;"></div>
                            <div class="spacing" style="margin-top: 2%;"></div>
                            <div class="row" style="margin-left: 0%; margin-right: 1.5%;">
                                <textarea class="form-control" id="reply" name="reply" style="height: 200px;"
                                    placeholder="abc....">{{ old('reply') }}</textarea>
                            </div>

                            <div class="row">
                                <div style="text-align: center">
                                    <button type="submit" class="button" style="padding: 5% 10%" name="submit" value="Submit"
                                        onclick="return confirm('Are you sure,you want to Submit Final Reply? it cannot be changed again.')">Submit
                                        Reply</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
@if ($transferOrAccept)
    </div>
    <div class="col-md-3">
        <!-- Forward To Form -->
        @if ($canForward)
            <!-- Show Forward To Form -->
            <form method="POST" action="{{ route('applications.forwardTo', ['application_id' => $app->id]) }}" id="forwardForm"
                data-redirect-url="{{ route('applications.index') }}">
                @csrf
                <div class="mb-3">
                    <label for="event_id" class="form-label">Forward To</label>
                    <select name="event_id" id="event_id" class="form-select" required>
                        <option value="" disabled selected>Select</option>
                        @if(!in_array(174, $org_id))
                            <option value="174">P 1</option>
                        @endif
                        @if(!in_array(175, $org_id))
                            <option value="175">P 2</option>
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label for="remarks" class="form-label">Note:</label>
                    <textarea class="form-control" id="remarks" name="remarks" required
                        placeholder="Add your notes here...">{{ old('remarks') }}</textarea>
                </div>
                <button type="button" class="btn w-100" name="submit" value="Forward" onclick="submitForwardedForm()"
                    style="background-color: #007bff; color: #fff; border: none;">
                    Forward
                </button>
            </form>
            <div class="text-center my-3">
                <hr class="d-inline-block w-25">
                <span class="mx-2">OR</span>
                <hr class="d-inline-block w-25">
            </div>
        @else
            <!-- Show Message When Cannot Forward -->
            <p class="alert alert-danger">You cannot forward this application at the moment.</p>
        @endif


        <!-- Accept Form -->
        <form method="post" action="{{ route('applications.acceptFromCR', ['application_id' => $app->id]) }}"
            id="acceptForm" data-redirect-url="{{ route('applications.edit', ['application' => $app->id]) }}">
            @csrf
            <button type=" button" class="btn w-100" name="submit" value="Approve"
                onclick="event.preventDefault();submitAcceptForm()"
                style="background-color: #28a745; color: #fff; border: none;">
                Accept
            </button>
        </form>

    </div>
    <script>
        function submitAcceptForm() {
            if (confirm('Are you sure you want to approve this petition?')) {
                const form = document.getElementById('acceptForm');
                const formData = new FormData(form);
                const redirectUrl = form.dataset.redirectUrl; // Use redirect URL from the form attribute

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.message) {
                            //  alert(data.message); // Show success message
                            window.location.href = redirectUrl; // Redirect based on form attribute
                        } else if (data.error) {
                            alert('Error: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing your request.');
                    });
            }
        }




        function submitForwardedForm() {
            // Confirmation before forwarding
            if (!confirm('Are you sure you want to forward this petition?')) {
                return; // Exit if the user cancels
            }

            const form = document.getElementById('forwardForm');
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.message) {
                        alert(data.message); // Show success message
                        if (form.dataset.redirectUrl) {
                            window.location.href = form.dataset.redirectUrl; // Redirect if URL is set
                        } else {
                            location.reload(); // Reload page if no redirect URL
                        }
                    } else if (data.error) {
                        alert('Error: ' + data.error); // Display error
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request. Please try again.');
                });
        }

    </script>
@endif


<script>
    $(document).ready(function () {
        let submitAction = '';

        // Detect which button is clicked
        $('#approve_button').on('click', function (e) {
            submitAction = 'Approve';
        });

        $('#return_button').on('click', function (e) {
            submitAction = 'Return';
        });

        // Form submission validation
        $('#submitstatusform').on('submit', function (e) {
            if (submitAction === 'Return') {
                const remarks = $('#remarks').val().trim();
                if (!remarks) {
                    alert('Remarks are required when returning the application.');
                    e.preventDefault(); // Prevent form submission
                    return false;
                }
            }
            return true; // Allow form submission for other cases
        });
    });
</script>

<script>

    {
        { --$(document).ready(function () { --}}
        {
            { --$('.list-group-item').each(function () { --}}
            {
                {
                    --                                var fieldValue = $(this).find('.float-end').text().trim(); // Get the value of the field inside each list-group-item--}}

                    {
                        { --                                if (fieldValue === '') { --} }
                        {
                            {
                                --$(this).hide(); // Hide the list-group-item if the field value is empty--}}
                                { { --                                } --}
                            }
                            { { --                            }); --}
                        }
                        { { --                        }); --}
                    }

                    document.getElementById('submit_return').addEventListener('click', function (event) {
                        var remarks = document.getElementById('remarks').value;
                        if (!remarks.trim()) { // Checking if remarks field is empty or contains only whitespace dev@1234
                            alert('Remarks are mandatory.'); // Alert if remarks field is empty
                            event.preventDefault(); // Prevent form submission
                        } else {
                            if (!confirm('Are you sure you want to return this petition?')) {
                                event.preventDefault(); // Prevent form submission if user cancels
                            }
                        }
                    });


</script>
</div>
</div>



@endsection