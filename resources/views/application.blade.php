@extends('layout')

@section('content')
    {{--    @php--}}
    {{--        print_r( session()->all());--}}
    {{--    @endphp--}}
    {{--@if ($errors->any())--}}
    {{--    <div class="alert alert-danger">--}}
    {{--        <strong>Whoops!</strong> There were some problems with your input.<br><br>--}}
    {{--        <ul>--}}
    {{--            @foreach ($errors->all() as $error)--}}
    {{--                <li>{{ $error }}</li>--}}
    {{--            @endforeach--}}
    {{--        </ul>--}}
    {{--    </div>--}}
    {{--@endif--}}
    {{--@if (session('error'))--}}
    {{--    <script>--}}
    {{--        alert("{{ session('error') }}");--}}
    {{--    </script>--}}
    {{--@endif--}}


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








    {{--    @if($appStatusRemark->isNotEmpty())--}}
    {{--        <div class="card text-white bg-info mb-3">--}}
    {{--            <div class="card-header">Note:</div>--}}
    {{--            <div class="card-body">--}}
    {{--                @foreach($appStatusRemark as $remark)--}}
    {{--                    <p class="card-text">Created by: {{ $remark->created_by }}</p>--}}
    {{--                    <p class="card-text">{{ $remark }}</p>--}}
    {{--                @endforeach--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    @else--}}
    {{--    @endif--}}

    <div class="row"></div>
    <div class="card shadow" xmlns="http://www.w3.org/1999/html">
        <div class="card-body">
            <form method="POST" action="{{route('applications.store')}}" enctype="multipart/form-data" >
                @csrf
                <div>
                    <div>
                        @if(isset($app->id))
                            <input type="hidden" value="{{$app->id}}" name="id" >
                        @endif
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" >Language of Letter:<span style="color: red;">*</span></label>
                            </div>
                            <div class="col">
                                <label class="form-check-label">
                                    <input type="radio" name="language_of_letter" value="E" id="language_of_letter_english" {{ $app->language_of_letter === 'E' || old('language_of_letter') === 'E' ? 'checked' : '' }} required>
                                    English
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" name="language_of_letter" value="H" id="language_of_letter_hindi" {{ $app->language_of_letter === 'H' || old('language_of_letter') === 'H' ? 'checked' : '' }}>
                                    Hindi
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" name="language_of_letter" value="O" id="language_of_letter_other" {{ $app->language_of_letter === 'O' || old('language_of_letter') === 'O' ? 'checked' : '' }}>
                                    Other
                                </label>
                            </div>
                        </div>

                        <hr class="row-divider">
                        <div class="row">
                            <div class="col-md-3" style="text-align: right"><label class="form-label">Name:<span style="color: red;" class="required">*</span></label></div>
                            <div class="col">
                                <div class="input-group">
                                    <select class="form-control col-md-1" name="applicant_title" id="applicant_title" >
                                        <option value="">-Title-</option>
                                        <option value="Shri" {{ (old('applicant_title') ?: $app->applicant_title) === "Shri" ? 'selected' : '' }}>Shri</option>
                                        <option value="Smt" {{ (old('applicant_title') ?: $app->applicant_title) === "Smt" ? 'selected' : '' }}>Shrimati</option>
                                        <option value="Sushree" {{ (old('applicant_title') ?: $app->applicant_title) === "Sushree" ? 'selected' : '' }}>Sushree</option>
                                        <option value="Mr" {{ (old('applicant_title') ?: $app->applicant_title) === "Mr" ? 'selected' : '' }}>Mr</option>
                                        <option value="Mrs" {{ (old('applicant_title') ?: $app->applicant_title) === "Mrs" ? 'selected' : '' }}>Mrs</option>
                                        <option value="Ms" {{ (old('applicant_title') ?: $app->applicant_title) === "Ms" ? 'selected' : '' }}>Ms</option>
                                        <option value="Km" {{ (old('applicant_title') ?: $app->applicant_title) === "Km" ? 'selected' : '' }}>Km</option>
                                        <option value="Prof" {{ (old('applicant_title') ?: $app->applicant_title) === "Prof" ? 'selected' : '' }}>Prof</option>
                                        <option value="Dr" {{ (old('applicant_title') ?: $app->applicant_title) === "Dr" ? 'selected' : '' }}>Dr</option>
                                        <option value="The" {{ (old('applicant_title') ?: $app->applicant_title) === "The" ? 'selected' : '' }}>The</option>
                                    </select>
                                    <input type="text" class="form-control col-md-3" id="applicant_name" aria-label="Applicant Name" placeholder="Applicant Name" name="applicant_name"  required value="{{ old('applicant_name') ?: $app->applicant_name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="org_from">Organization From:</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="org_from" id="org_from"  placeholder="Org/Dept from"  value="{{ old('org_from') ?: $app->org_from}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="text-align: right"><label class="form-label">Address:<span style="color: red;" class="required">*</span></label></div>
                            <div class="col">
                                <textarea class="form-control"  name="address" id="address"  required>{{ old('address') ?: $app->address }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" name="country" id="country"  required>
                                        <option value="">-Select a Country-*</option>
                                        <option value="I" {{ old('country') === 'I' || $app->country === 'I' ? 'selected' : '' }}>India</option>
                                        <option value="U" {{ old('country') === 'U' || $app->country === 'U' ? 'selected' : '' }}>USA</option>
                                        <option value="O" {{ old('country') === 'O' || $app->country === 'O' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" name="state_id" id="state_id"{{ old('country') !== 'I' && $app->country !== 'I' ? ' disabled' : '' }} >
                                        <option value="">-Select a State-</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id') == $state->id || $app->state_id == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="pincode" id="pincode" {{ old('country') !== 'I' && $app->country !== 'I' ? ' disabled' : '' }} pattern="[0-9]{6}" minlength="6" maxlength="6" placeholder="Pincode" value="{{ old('pincode') ?: $app->pincode}}">
                                </div>
                            </div>
                        </div>

                        <hr class="row-divider">

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label">Gender:</label>
                            </div>
                            <div class="col">
                                <label class="form-check-label">
                                    <input type="radio" name="gender" value="M" id="gender_male" {{ $app->gender === 'M' || old('gender') === 'M' ? 'checked' : '' }}>
                                    Male
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" name="gender" value="F" id="gender_female" {{ $app->gender === 'F' || old('gender') === 'F' ? 'checked' : '' }}>
                                    Female
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" name="gender" value="O" id="gender_other" {{ $app->gender === 'O' || old('gender') === 'O' ? 'checked' : '' }}>
                                    Other
                                </label>
{{--                                <span class="text-danger">@error('gender'){{$message}}@enderror</span>--}}
                            </div>
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="mobile_no" >Mobile number:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <input type="text" class="form-control" name="mobile_no" id="mobile_no" pattern="[0-9]{10}" minlength="10" maxlength="10" placeholder="mobile no" style="width: 89%; margin-left: 5%;" value="{{ old('mobile_no') ?: substr($app->mobile_no, 3)}}">
                                </div>
                            </div>
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="phone_no">Telephone Number:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <input type="text" class="form-control"  name="phone_no" id="phone_no" pattern="[0-9]{11}" minlength="11" maxlength="11" placeholder="phone no" style="width: 89%; margin-left: 5%;" value="{{ old('phone_no') ?: $app->phone_no}}">
                                </div>
                            </div>

                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="email_id">Email:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ">
                                    <input type="email" class="form-control" name="email_id" id="email_id" title="If acknowledgement is requested, this email will be used for sending mail to the applicant." placeholder="email" value="{{ old('email_id') ?: $app->email_id }}">
                                </div>
                            </div>
                        </div>

                        <hr class="row-divider">

                        <div class="row" id="alignment">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="letter_no">Letter No:<span style="color: red;" >*</span></label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <input type="text" class="form-control" name="letter_no" id="letter_no" style="width: 89%; margin-left: 5%;" placeholder="letter no"  value="{{ old('letter_no') ?: $app->letter_no}}" required>
                                </div>
                            </div>

                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="letter_date">Letter date:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <input type="date" class="form-control datepicker" name="letter_date" id="letter_date" style="width: 89%; margin-left: 5%;" value="{{ old('letter_date') ?: ($app->letter_date ? \Carbon\Carbon::parse($app->letter_date)->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="letter_subject">Letter Subject:<span style="color: red;" class="required">*</span></label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="letter_subject" id="letter_subject"  required  placeholder="letter subject"  value="{{ old('letter_subject') ?: $app->letter_subject}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="letter_body">Letter Body:</label>
                            </div>
                            <div class="col">
                                <textarea class="form-control" name="letter_body" id="letter_body"   rows="7" placeholder="letter body" >{{ old('letter_body') ?: $app->letter_body}}</textarea>
                            </div>
                        </div>
                        <hr class="row-divider">

                        <div class="row">
                            <div class="col-md-3" style="text-align: right" >
                                <label class="form-label">Acknowledgement:<span style="color: red;">*</span></label>
                            </div>
                            <div class="col">
                                <label class="form-check-label">
                                    <input type="radio" name="acknowledgement" value="Y" {{ (old('acknowledgement') === 'Y' || $app->acknowledgement === 'Y') ? 'checked' : '' }}>
                                    Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" name="acknowledgement" value="N" {{ (old('acknowledgement') === 'N' || $app->acknowledgement === 'N') ? 'checked' : '' }} required>
                                    No
                                </label>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-3" style="text-align: right" >
                                <label class="form-label" for="grievance_category_id">Grievance Subject:</label>
                            </div>
                            <div class="col-md-9" >
                                <select class="form-control " name="grievance_category_id" id="grievance_category_id">
                                    <option value="">-Select a Grievance Subject-</option>
                                    @foreach($grievances as $grievance)
                                        <option value="{{ $grievance->id }}" {{ old('grievance_category_id') == $grievance->id || $app->grievance_category_id == $grievance->id ? 'selected' : '' }}>{{ $grievance->grievances_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <hr class="row-divider">

                        <div class="row">

                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="min_dept_gov_code">Gov Code Search:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="min_dept_gov_code" id="min_dept_gov_code" placeholder="Search" value="{{ old('min_dept_gov_code') ?: $app->min_dept_gov_code }}">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="action_org">Action:<span style="color: red;">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control" name="action_org" id="action_org" required>
                                    <option value="">-Select an Action-</option>
                                    <option  id="option_no_action" value="N" {{ (old('action_org') == 'N' || $app->action_org == 'N') ? 'selected' : '' }}>No Action </option>
                                    <option id="option_forward_central" value="F" {{ (old('action_org') == 'F' || $app->action_org == 'F') ? 'selected' : '' }}>Forward to Central Govt. Ministry/Department</option>
                                    <option id="option_forward_state" value="S" {{ (old('action_org') == 'S' || $app->action_org == 'S') ? 'selected' : '' }}>Forward to State Govt. </option>
                                    <option id="option_miscellaneous" value="M" {{ (old('action_org') == 'M' || $app->action_org == 'M') ? 'selected' : '' }}>Miscellaneous</option>
                                </select>
                            </div>
                        </div>


                            <div class="row" id="orgS_dropdown_row" style="display: none">
                                <div class="col-md-3" style="text-align: right">
                                    <label class="form-label" for="department_org_idS">
                                        <span id="org_label">State Government</span><span style="color: red;">*</span>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control" name="department_org_id" id="department_org_idS" required>
                                        <option value="">-Select a State-</option>
                                        @foreach($organizationStates as $organizationS)
                                            <option value="{{ $organizationS->id }}" data-v-code="{{ $organizationS->v_code }}" {{ old('department_org_id') == $organizationS->id || $app->department_org_id == $organizationS->id ? 'selected' : '' }}>
                                                {{ $organizationS->org_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row" id="orgM_dropdown_row"style="display: none">
                                <div class="col-md-3" style="text-align: right">
                                    <label class="form-label" for="department_org_idM">
                                        <span id="org_label">Ministry/Department:</span><span style="color: red;">*</span>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control" name="department_org_id" id="department_org_idM" required>
                                        <option value="">-Select a Ministry/Department-</option>
                                        @foreach($organizationM as $organization)
                                            <option value="{{ $organization->id }}" data-v-code="{{ $organization->v_code }}" {{ old('department_org_id') == $organization->id || $app->department_org_id == $organization->id ? 'selected' : '' }}>
                                                {{ $organization->org_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row" id="reasonM_dropdown_row" style="display: none">
                                <div class="col-md-3" style="text-align: right">
                                    <label class="form-label" for="reasonM">Reason:<span style="color: red;">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control" name="reason_id" id="reasonM" required>
                                        <option value="">-Select a Reason-</option>
                                        @foreach($reasonM as $reason)
                                            <option value="{{ $reason->id }}" {{ old('reason_id') == $reason->id || $app->reason_id == $reason->id ? 'selected' : '' }}>
                                                {{ $reason->reason_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row" id="reasonN_dropdown_row" style="display: none">
                                <div class="col-md-3" style="text-align: right">
                                    <label class="form-label" for="reasonN">Reason:<span style="color: red;">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control" name="reason_id" id="reasonN" required>
                                        <option value="">-Select a Reason-</option>
                                        @foreach($reasonN as $reasons)
                                            <option value="{{ $reasons->id }}" {{ old('reason_id') == $reasons->id || $app->reason_id == $reasons->id ? 'selected' : '' }}>
                                                {{ $reasons->reason_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="remarks">Remarks:</label>
                            </div>
                            <div class="col">
                                <textarea class="form-control" id="remarks" name="remarks">{{ old('remarks') ?: $app->remarks }}</textarea>
                            </div>
                        </div>

                        <hr class="row-divider">

                        @if($app->file_path)
                            <div class="row">
                                <div class="col-6" style="text-align: right">
                                    <a href="{{url('/api/getFile/'.$app->file_path)}}" target="_blank">
                                        <button type="button" class="btn btn-outline-primary">View old File</button>
                                    </a>
                                </div>
                                <div class="col-6" >
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file_path" name="file_path" accept=".pdf">(.pdf)(max-20MB)<span style="color: red;">*</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-6" style="padding-left: 32.8%">
                                        <button type="button" class="btn btn-outline-primary" id="openFileButton" onclick="openSelectedFile()" style="display: none;">Open Selected File</button>
                                    </div>
                                    <div class="col-6" >
                                        <button type="button" class="btn btn-outline-danger" id="removeFileButton" onclick="removeSelectedFile()" style="display: none;">Remove selected File</button>
                                    </div>
                                </div>
                        @else
                            <div class="row">
                                <div class="col-md-6 offset-md-4" style="margin-left: 38%">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file_path" name="file_path" accept=".pdf" required> (.pdf)(max-20MB)<span style="color: red;">*</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-6" style="padding-left: 32.8%">
                                        <button type="button" class="btn btn-outline-primary" id="openFileButton" onclick="openSelectedFile()" style="display: none;">Open Selected File</button>
                                    </div>
                                    <div class="col-6" >
                                        <button type="button" class="btn btn-outline-danger" id="removeFileButton" onclick="removeSelectedFile()" style="display: none;">Remove selected File</button>
                                    </div>
                                </div>
                        @endif

                        <hr class="row-divider">



                        <div class="row">
                            <span id="file-status"></span>
                            @if($allowDraft)
                                <div class="col-6" style="text-align: right">
                                    <input type="submit" class="btn btn-outline-warning" name="submit" value="Draft"></div>
                                <div class="col-6" style="text-align: left" >
                                    <button type="submit" class="btn btn-outline-success" name="submit" value="Forward" onclick="return confirm('Are you sure,you want to forward?')">Forward</button>
                                </div>
                            @endif
                            @if($allowOnlyForward)
                                <div style="text-align: center" >
                                    <button type="submit" class="btn btn-outline-success" name="submit" value="Forward" onclick="return confirm('Are you sure,you want to forward?')">Forward</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <script>

            //open and remove file
                function openSelectedFile() {
                // Get the file input element
                var fileInput = document.getElementById('file_path');

                // Check if a file has been selected
                if (fileInput.files.length > 0) {
                var selectedFile = fileInput.files[0];

                // Check if the selected file is a PDF (you can add more file type checks)
                if (selectedFile.type === 'application/pdf') {
                // Construct the URL to open the file
                var fileURL = URL.createObjectURL(selectedFile);

                // Open the file in a new tab
                window.open(fileURL, '_blank');
            } else {
                alert('Please select a PDF file.');
            }
            } else {
                alert('Please select a file first.');
            }
            }
                function removeSelectedFile() {
                    // Get the file input element
                    var fileInput = document.getElementById('file_path');

                    // Clear the selected file by setting its value to an empty string
                    fileInput.value = '';

                    // Hide both the "Open Selected File" and "Remove File" buttons
                    var openFileButton = document.getElementById('openFileButton');
                    var removeFileButton = document.getElementById('removeFileButton');
                    openFileButton.style.display = 'none';
                    removeFileButton.style.display = 'none';
                }
                // Add an event listener to show the button when a file is selected
                var fileInput = document.getElementById('file_path');
                fileInput.addEventListener('change', function () {
                    var openFileButton = document.getElementById('openFileButton');
                    var removeFileButton = document.getElementById('removeFileButton');
                    if (fileInput.files.length > 0) {
                        openFileButton.style.display = 'block';
                        removeFileButton.style.display = 'block';
                    } else {
                        openFileButton.style.display = 'none';
                        removeFileButton.style.display = 'none';
                    }
                });


        // calender
            $(function() {
                var today = new Date().toISOString().split('T')[0];
                $(".datepicker").attr('max', today);

                $(".datepicker").on('change', function() {
                    var selectedDate = $(this).val();
                    if (selectedDate === '') {
                        $(this).val(null);
                    } else if (selectedDate > today) {
                        $(this).val(today);
                    }
                });
            });
            //pin restrited to number
            const pincodeInput = document.getElementById('pincode');
            pincodeInput.addEventListener('input', function(event) {
                const numericValue = event.target.value.replace(/\D/g, '');
                event.target.value = numericValue;
            });

            //phone number and mobile number restricted to number also min and max value is 11 and 10 respectively
            document.addEventListener('DOMContentLoaded', function() {
                var mobileNoInput = document.getElementById('mobile_no');
                var phoneNoInput = document.getElementById('phone_no');

                mobileNoInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 10) {
                        this.value = this.value.slice(0, 10);
                    }
                });

                phoneNoInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 11) {
                        this.value = this.value.slice(0, 11);
                    }
                });
            });

            //if india is selected then state will appear
            $(document).ready(function() {
                // Initially disable the State dropdown if the selected country is not India
                if ($('#country').val() !== 'I') {
                    $('#state_id').prop('disabled', true);
                    $('#pincode').prop('disabled', true);
                    $('#state_id, #pincode').hide();
                }

                // Handle change event of the Country dropdown
                $('#country').on('change', function() {
                    if ($(this).val() === 'I') {
                        $('#state_id').prop('disabled', false);
                        $('#pincode').prop('disabled', false);
                        $('#state_id, #pincode').show();
                    } else {
                        $('#state_id').prop('disabled', true);
                        $('#pincode').prop('disabled', true);
                        $('#state_id, #pincode').hide();
                    }
                });
            });

            //draft button clicked required field will be removed
            $(document).ready(function() {
                $('input[name="submit"]').click(function() {
                    var buttonValue = $(this).val();

                    if (buttonValue === 'Draft') {
                        disableRequiredFields();
                    } else {
                        enableRequiredFields();
                    }
                });

                function disableRequiredFields() {
                    $('input[required]').removeAttr('required');
                    $('select[required]').removeAttr('required');
                    $('textarea[required]').removeAttr('required');
                    $('file[required]').removeAttr('required');
                }

                function enableRequiredFields() {
                    $('input[data-required]').each(function() {
                        if ($(this).data('required')) {
                            $(this).attr('required', 'required');
                        }
                    });

                    $('select[data-required]').each(function() {
                        if ($(this).data('required')) {
                            $(this).attr('required', 'required');
                        }
                    });

                    $('textarea[data-required]').each(function() {
                        if ($(this).data('required')) {
                            $(this).attr('required', 'required');
                        }
                    });

                    $('file[data-required]').each(function() {
                        if ($(this).data('required')) {
                            $(this).attr('required', 'required');
                        }
                    });
                }
            });

            //action toggle, gov code, gov name variations
            $(document).ready(function() {
                function showHideDropdownRows() {
                    const selectedAction = $("#action_org").val();
                    $("#orgS_dropdown_row").toggle(selectedAction === "S");
                    $("#orgM_dropdown_row").toggle(selectedAction === "F");
                    $("#reasonM_dropdown_row").toggle(selectedAction === "M");
                    $("#reasonN_dropdown_row").toggle(selectedAction === "N");
                    $("#department_org_idS").prop("disabled", selectedAction !== "S");
                    $("#department_org_idM").prop("disabled", selectedAction !== "F");
                    $("#reasonM").prop("disabled", selectedAction !== "M");
                    $("#reasonN").prop("disabled", selectedAction !== "N");
                }

                function populateOrganizationDropdown(vCode) {
                    const organizationS = {!! json_encode($organizationStates) !!};
                    const organizationM = {!! json_encode($organizationM) !!};

                    let foundInS = false;
                    let foundInM = false;

                    organizationS.forEach((org) => {
                        if (org.v_code === vCode) {
                            foundInS = true;
                            return false; // Exit the loop early if found in S
                        }
                    });

                    organizationM.forEach((org) => {
                        if (org.v_code === vCode) {
                            foundInM = true;
                            return false; // Exit the loop early if found in M
                        }
                    });

                    // Show/hide the corresponding dropdown rows and set action_org value
                    if (foundInS) {
                        $("#orgS_dropdown_row").show();
                        $("#orgM_dropdown_row").hide();
                        $("#action_org").val("S").trigger("change");
                        // Populate the organization dropdown
                        $("#department_org_idS").empty();
                        organizationS.forEach((org) => {
                            const option = $("<option>", {
                                value: org.id,
                                text: org.org_desc,
                                selected: org.v_code === vCode,
                            });
                            $("#department_org_idS").append(option);
                        });
                    } else if (foundInM) {
                        $("#orgS_dropdown_row").hide();
                        $("#orgM_dropdown_row").show();
                        $("#action_org").val("F").trigger("change");
                        // Populate the organization dropdown
                        $("#department_org_idM").empty();
                        organizationM.forEach((org) => {
                            const option = $("<option>", {
                                value: org.id,
                                text: org.org_desc,
                                selected: org.v_code === vCode,
                            });
                            $("#department_org_idM").append(option);
                        });
                    }
                }

                function checkGovCodeAndOrganization() {
                    const govCode = $("#min_dept_gov_code").val();
                    if (!govCode) {
                        const selectedOrgS = $("#department_org_idS").val();
                        const selectedOrgM = $("#department_org_idM").val();

                        if (selectedOrgS) {
                            const selectedOrgSData = $("#department_org_idS option:selected").data("v-code");
                            if (selectedOrgSData) {
                                $("#min_dept_gov_code").val(selectedOrgSData);
                            }
                        } else if (selectedOrgM) {
                            const selectedOrgMData = $("#department_org_idM option:selected").data("v-code");
                            if (selectedOrgMData) {
                                $("#min_dept_gov_code").val(selectedOrgMData);
                            }
                        }
                    }
                }

                // Attach the event handlers
                $("#action_org").on("change", showHideDropdownRows);
                $("#min_dept_gov_code").on("input", function() {
                    const vCode = $(this).val();
                    if (vCode) {
                        populateOrganizationDropdown(vCode);
                    } else {
                        $("#orgS_dropdown_row").hide();
                        $("#orgM_dropdown_row").hide();
                        $("#action_org").val("").trigger("change");
                    }
                });

                $("#department_org_idS").on("change", function() {
                    const vCode = $(this).find("option:selected").data("v-code");
                    if (vCode) {
                        $("#min_dept_gov_code").val(vCode);
                    } else {
                        $("#min_dept_gov_code").val(null);
                    }
                });

                $("#department_org_idM").on("change", function() {
                    const vCode = $(this).find("option:selected").data("v-code");
                    if (vCode) {
                        $("#min_dept_gov_code").val(vCode);
                    } else {
                        $("#min_dept_gov_code").val(null);
                    }
                });

                $("#action_org").on("blur", function() {
                    $("#min_dept_gov_code").val(null);
                });

                // Call the functions on page load
                showHideDropdownRows();
                checkGovCodeAndOrganization();
            });




        //if language selected to other all required field will be removed
            // Attach change event listener to the radio buttons
            $('input[name="language_of_letter"]').on('change', toggleFields);

            // Trigger the change event for the initially selected radio button
            $(document).ready(function() {
                var selectedLanguage = $('input[name="language_of_letter"]:checked').val();
                if (selectedLanguage === 'O') {
                    // If the 'Other' option is selected initially, trigger the change event
                    $('input[name="language_of_letter"]:checked').trigger('change');
                }
            });

            // Function to toggle fields based on the selected language
            function toggleFields() {
                if ($('input[name="language_of_letter"]:checked').val() === 'O') {
                    // $('#applicant_title').removeAttr('required');
                    $('#applicant_name').removeAttr('required');
                    $('#address').removeAttr('required');
                    $('#country').removeAttr('required');
                    $('#letter_subject').removeAttr('required');
                    $('.required').hide();
                } else {
                    // $('#applicant_title').attr('required', 'required');
                    $('#applicant_name').attr('required', 'required');
                    $('#address').attr('required', 'required');
                    $('#country').attr('required', 'required');
                    $('#letter_subject').attr('required', 'required');
                    $('.required').show();
                }
            }
            // $(document).ready(function () {
            //     // When the "Forward" button is clicked
            //     $('#forwardButton').click(function () {
            //         // Show a SweetAlert confirmation dialog
            //         Swal.fire({
            //             title: 'Are you sure?',
            //             text: 'You are about to forward this form.',
            //             icon: 'warning',
            //             showCancelButton: true,
            //             confirmButtonColor: '#3085d6',
            //             cancelButtonColor: '#d33',
            //             confirmButtonText: 'Yes, forward it!'
            //         }).then((result) => {
            //             if (result.isConfirmed) {
            //                 $('#forwardButtonactual').click();
            //             }
            //         });
            //     });
            // });

            // //confirmation for submit
            // $(document).ready(function () {
            //     // When the "Forward" button is clicked
            //     $('#forwardButton').click(function () {
            //         // Show a confirmation dialog
            //         if (confirm('Are you sure you want to Forward?')) {
            //             // User confirmed, submit the form
            //             $('#forwardForm').submit();
            //         }
            //     });
            // });
        </script>
    </div>
@endsection
