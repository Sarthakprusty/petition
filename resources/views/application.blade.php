@extends('layout')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger" xmlns="http://www.w3.org/1999/html">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow" xmlns="http://www.w3.org/1999/html">
        <div class="card-body">
            <form method="POST" action="{{route('applications.store')}}" enctype="multipart/form-data">
                @csrf
                <div>
                    <div>
                        <div class="row">
                            <div class="col-md-3" style="text-align: right"><label class="form-label">Name:<span style="color: red;">*</span></label></div>
                            <div class="col">
                                @if(isset($app->id))
                                    <input type="hidden" value="{{$app->id}}" name="id" />
                                @endif
                                <div class="input-group">
                                    <select class="form-control col-md-2" name="applicant_title" data-required="true" required>
                                        <option value="-1">Title</option>
                                        <option value="Shri" {{ (old('applicant_title') ?: $app->applicant_title) === "Shri" ? 'selected' : '' }}>Shri</option>
                                        <option value="Shrimati" {{ (old('applicant_title') ?: $app->applicant_title) === "Shrimati" ? 'selected' : '' }}>Shrimati</option>
                                        <option value="Sushree" {{ (old('applicant_title') ?: $app->applicant_title) === "Sushree" ? 'selected' : '' }}>Sushree</option>
                                    </select>
                                    <input type="text" class="form-control" aria-label="Applicant Name" placeholder="Applicant Name" name="applicant_name" data-required="true" required value="{{ old('applicant_name') ?: $app->applicant_name}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3" style="text-align: right"><label class="form-label">Address:<span style="color: red;">*</span></label></div>
                            <div class="col">
                                <textarea class="form-control"  name="address" data-required="true" required>{{ old('address') ?: $app->address }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" name="country" id="country" data-required="true" required>
                                        <option value="">Select Country*</option>
                                        <option value="I" {{ old('country') === 'I' || $app->state_id === 'I' ? 'selected' : '' }}>India</option>
                                        <option value="U" {{ old('country') === 'U' || $app->state_id === 'U' ? 'selected' : '' }}>USA</option>
                                        <option value="O" {{ old('country') === 'O' || $app->state_id === 'O' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" name="state_id" id="state_id"{{ old('country') !== 'I' && $app->state_id !== 'I' ? ' disabled' : '' }} >
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id') == $state->id || $app->state_id == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="pincode" id="pincode" pattern="[0-9]{6}" minlength="6" maxlength="6" placeholder="Pincode" value="{{ old('pincode') ?: $app->pincode}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="org_from">Organization From</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="org_from" id="org_from"  placeholder="Org/Dept from"  value="{{ old('org_from') ?: $app->org_from}}">
                            </div>
                        </div>

                        <hr class="row-divider">

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="mobile_no">Mobile number:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <input type="text" class="form-control"  name="mobile_no" id="mobile_no" placeholder="mobile no" style="width: 89%; margin-left: 5%;" value="{{ old('mobile_no') ?: $app->mobile_no}}">
                                </div>
                            </div>
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="phone_no">Phone Number:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <input type="text" class="form-control"  name="phone_no" id="phone_no" placeholder="phone no" style="width: 89%; margin-left: 5%;" value="{{ old('phone_no') ?: $app->phone_no}}">
                                </div>
                            </div>

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
                            </div>

                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="email_id">Email:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ">
                                    <input type="email" class="form-control" name="email_id" id="email_id" placeholder="email" value="{{ old('email_id') ?: $app->email_id }}">
                                </div>
                            </div>
                        </div>

                        <hr class="row-divider">


                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label">Language of Letter:</label>
                            </div>
                            <div class="col">
                                <label class="form-check-label">
                                    <input type="radio" name="language_of_letter" value="E" id="language_of_letter_english" {{ $app->language_of_letter === 'E' || old('language_of_letter') === 'E' ? 'checked' : '' }}>
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



                        <div class="row" id="alignment">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="letter_date">Letter date:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <input type="date" class="form-control datepicker" name="letter_date" id="letter_date" style="width: 89%; margin-left: 5%;" value="{{ old('letter_date') ?: \Carbon\Carbon::parse($app->letter_date)->format('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="letter_no">Letter No:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <input type="text" class="form-control" name="letter_no" id="letter_no" style="width: 89%; margin-left: 5%;" placeholder="letter no"  value="{{ old('letter_no') ?: $app->letter_no}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="letter_subject">Letter Subject:<span style="color: red;">*</span></label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="letter_subject" id="letter_subject" data-required="true" required  placeholder="letter subject"  value="{{ old('letter_subject') ?: $app->letter_subject}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="letter_body">Letter Body:<span style="color: red;">*</span></label>
                            </div>
                            <div class="col">
                                <textarea class="form-control" name="letter_body" id="letter_body" data-required="true" required rows="7" placeholder="letter body" >{{ old('letter_body') ?: $app->letter_body}}</textarea>
                            </div>
                        </div>
                        <hr class="row-divider">

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label">Acknowledgement:</label>
                            </div>
                            <div class="col">
                                <label class="form-check-label">
                                    <input type="radio" name="acknowledgement" value="Y" {{ (old('acknowledgement') == 'Y' || $app->acknowledgement == 'Y') ? 'checked' : '' }}>
                                    Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" name="acknowledgement" value="N" {{ (old('acknowledgement') == 'N' || $app->acknowledgement == 'N') ? 'checked' : '' }}>
                                    No
                                </label>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-3" style="text-align: right" >
                                <label class="form-label" for="grievance_category_id">Grievance Subject:</label>
                            </div>
                            <div class="col-md-9" style="margin-top:1%;">
                                <select class="form-control " name="grievance_category_id" id="grievance_category_id">
                                    <option value="">Select a Grievance Subject</option>
                                        @foreach($grievances as $grievance)
                                            <option value="{{ $grievance->id }}" {{ old('grievance_category_id') == $grievance->id || $app->grievance_category_id == $grievance->id ? 'selected' : '' }}>{{ $grievance->grievances_desc }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="action_org">Action:</label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control" name="action_org" id="action_org">
                                    <option value="">Select an Action</option>
                                    <option value="N" {{ (old('action_org') == 'N' || $app->action_org == 'N') ? 'selected' : '' }}>No Action </option>
                                    <option value="F" {{ (old('action_org') == 'F' || $app->action_org == 'F') ? 'selected' : '' }}>Forward to Central Govt. Ministry/Department</option>
                                    <option value="M" {{ (old('action_org') == 'M' || $app->action_org == 'M') ? 'selected' : '' }}>Miscellaneous</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="min_dept_gov_code">Min/Dept/Gov Code:</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="min_dept_gov_code" id="min_dept_gov_code" placeholder="Code"  value="{{ old('min_dept_gov_code') ?: $app->min_dept_gov_code}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3" style="text-align: right">
                                <label class="form-label" for="department_org_id">Ministry/Department:</label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control" name="department_org_id" id="department_org_id" >
                                    <option value="">Select an Ministry/Department</option>
                                    @foreach($organizations as $organization)
                                        <option value="{{ $organization->id }}"{{ old('department_org_id') == $organization->id ||$app->department_org_id == $organization->id ? 'selected' : '' }}>{{ $organization->org_desc }}</option>
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

                        <div class="row">
                            <div class="col-md-6 offset-md-4" style="margin-left: 38%">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="file_path" name="file_path" accept=".pdf">
                                    </div>
                                    @if($app->file_path!='')
                                        <a href="/api/getFile/{{$app->file_path}}" target="_blank">View File</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="row-divider">

                        <div class="row">
                            <div class="col-6 d-flex align-items-center justify-content-end">
                                <input type="submit" class="btn btn-outline-success" name="submit" value="Save">
                                <span id="file-status"></span>
                                <input type="submit" class="btn btn-outline-success" name="submit" value="Draft">
                            </div>
                        </div>
                    </div>
                </div>
            </form>


            <script>
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
                }

                    // Handle change event of the Country dropdown
                    $('#country').on('change', function() {
                    if ($(this).val() === 'I') {
                    $('#state_id').prop('disabled', false);
                } else {
                    $('#state_id').prop('disabled', true);
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
                }
                });

                //



            </script>
        </div>
    </div>
@endsection
