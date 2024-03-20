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


    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{route('organizations.store')}}" enctype="multipart/form-data">
                @csrf
                @if(isset($organizations->id))
                    <input type="hidden" value="{{$organizations->id}}" name="id" >
                @endif
                <div class="row"></div>
                <div class="row"></div>

                <div class="row">
                    <div class="col-md-3" style="text-align: right" for="org_desc"><label class="form-label">Organization Name:<span style="color: red;" class="required">*</span></label></div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="org_desc" aria-label="org_desc" placeholder="Name" name="org_desc"  required value="{{ old('org_desc') ?: $organizations->org_desc}}">
                        </div>
                    </div>
                </div>

                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="org_desc_hin">संगठन का नाम:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="org_desc_hin" aria-label="org_desc_hin" placeholder="हिंदी में नाम" name="org_desc_hin"  required value="{{ old('org_desc_hin') ?: $organizations->org_desc_hin}}" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="org_head">Head:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="org_head" aria-label="org_head" placeholder="org head" name="org_head"  required value="{{ old('org_head') ?: $organizations->org_head}}" >
                        </div>
                    </div>
                </div>


                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="org_head_hi">प्रमुख:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="org_head_hi" aria-label="org_head_hi" placeholder="प्रमुख हिंदी में" name="org_head_hi"  required value="{{ old('org_head_hi') ?: $organizations->org_head_hi}}" >
                        </div>
                    </div>
                </div>

                <hr class="row-divider">

                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="org_type">Type:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col-md-3">
                        <label class="form-check-label">
                            <input type="radio" name="org_type" value="S" {{ $organizations->org_type == 'S' ? 'checked' : '' }}> State Gov
                        </label>
                        <label class="form-check-label">
                            <input type="radio" name="org_type" value="M" {{ $organizations->org_type == 'M' ? 'checked' : '' }}> Central Ministry
                        </label>
                    </div>

                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="v_code">Code:</label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="v_code" aria-label="v_code" placeholder="code"  minlength="1" maxlength="10" name="v_code" value="{{ old('v_code') ?: $organizations->v_code}}" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="mail">E mail:</label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="email" class="form-control" id="mail" aria-label="mail" placeholder="E mail" name="mail" value="{{ old('mail') ?: $organizations->mail}}" >
                        </div>
                    </div>

                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="phone_no">Number:</label>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="phone_no" aria-label="phone_no" pattern="[0-9]{3}" minlength="3" maxlength="11" placeholder="phone number" name="phone_no" value="{{ old('phone_no') ?: $organizations->phone_no}}" >
                        </div>
                    </div>
                </div>


                <hr class="row-divider">


                <div class="row">
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="org_address">Address:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <textarea class="form-control" id="org_address" aria-label="org_address" placeholder="Address" name="org_address"  required > {{ old('org_address') ?: $organizations->org_address}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="org_address_hin">पता:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <textarea class="form-control" id="org_address_hin" aria-label="org_address_hin" placeholder="पता" name="org_address_hin"  required  >{{ old('v_code') ?: $organizations->org_address_hin}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row" >
{{--                    <div class="col-md-3" style="text-align: right">--}}
{{--                        <label class="form-label" for="state_id">State:</label>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-3">--}}
{{--                        <div class="input-group">--}}
{{--                            <select class="form-control" name="state_id" id="state_id" >--}}
{{--                                <option value="">-Select State-</option>--}}
{{--                                @foreach($states as $state)--}}
{{--                                    @if($state->id == $organizations->state_id)--}}
{{--                                        <option value="{{ $state->id }}" selected>{{ $state->state_name }}</option>--}}
{{--                                    @else--}}
{{--                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="pincode">Pin code:</label>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" pattern="[0-9]{6}" minlength="6" maxlength="6" id="pincode" aria-label="pincode" placeholder="pincode" name="pincode"  value="{{ old('pincode') ?: $organizations->pincode}}" >
                        </div>
                    </div>
                </div>

                <hr class="row-divider">

                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-3" style=padding-left:20%>
                        <span id="file-status"></span>
                        <div  style="text-align: left">
                            <input type="submit" class="btn btn-outline-success" name="submit" value="Save" onclick="return confirm('Are you sure,you want to change Organization details')">
                        </div>
                    </div>
                </div>
            </form>
        </div>


        <script>
            const pincodeInput = document.getElementById('pincode');
            pincodeInput.addEventListener('input', function(event) {
                const numericValue = event.target.value.replace(/\D/g, '');
                event.target.value = numericValue;
            });


            document.addEventListener('DOMContentLoaded', function() {
                var phoneNoInput = document.getElementById('phone_no');

                phoneNoInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 11) {
                        this.value = this.value.slice(0, 11);
                    }
                });
            });

        </script>

    </div>













@endsection
