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
                <div class="row"></div>
                <div class="row"></div>

                <div class="row">
                    <div class="col-md-3" style="text-align: right"><label class="form-label">Organization Name:<span style="color: red;" class="required">*</span></label></div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="org_desc" aria-label="org_desc" placeholder="Name" name="org_desc"  required value="{{ old('org_desc') ?: $organizations->org_desc}}">
                        </div>
                    </div>
                </div>

                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="letter_no">हिंदी में नाम:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <input type="text" class="form-control" id="org_desc_hin" aria-label="org_desc_hin" placeholder="हिंदी में नाम" name="org_desc_hin"  required value="{{ old('org_desc_hin') ?: $organizations->org_desc_hin}}" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label">Head:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="org_head" aria-label="org_head" placeholder="org head" name="org_head"  required value="{{ old('org_head') ?: $organizations->org_head}}" >
                        </div>
                    </div>
                </div>


                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="letter_no">प्रमुख हिंदी में:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <input type="text" class="form-control" id="org_head_hi" aria-label="org_head_hi" placeholder="प्रमुख हिंदी में" name="org_head_hi"  required value="{{ old('org_head_hi') ?: $organizations->org_head_hi}}" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3" style="text-align: right"><label class="form-label">Address:<span style="color: red;" class="required">*</span></label></div>
                    <div class="col">
                        <div class="input-group">
                            <textarea class="form-control" id="org_address" aria-label="org_address" placeholder="Address" name="org_address"  required value="{{ old('org_address') ?: $organizations->org_address}}"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label">E mail:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="email" class="form-control" id="mail" aria-label="mail" placeholder="E mail" name="mail"  required value="{{ old('mail') ?: $organizations->mail}}" >
                        </div>
                    </div>
                </div>

                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="letter_no">From date:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <input type="text" class="form-control" id="name_hin" aria-label="name_hin" placeholder="हिंदी में नाम" name="name_hin"  required value="{{ old('v_code') ?: $organizations->v_code}}" >
                        </div>
                    </div>
                </div>



                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="letter_no">From date:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <input type="text" class="form-control" id="name_hin" aria-label="name_hin" placeholder="हिंदी में नाम" name="name_hin"  required value="{{ old('v_code') ?: $organizations->org_address_hin}}" >
                        </div>
                    </div>
                </div>

                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="letter_no">From date:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <input type="text" class="form-control" id="name_hin" aria-label="name_hin" placeholder="हिंदी में नाम" name="name_hin"  required value="{{ old('org_head') ?: $organizations->pincode}}" >
                        </div>
                    </div>
                </div>

                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="letter_no">From date:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <input type="text" class="form-control" id="name_hin" aria-label="name_hin" placeholder="हिंदी में नाम" name="name_hin"  required value="{{ old('v_code') ?: $organizations->phone_no}}" >
                        </div>
                    </div>
                </div>

                {{--                @php--}}
                {{--                    $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();--}}
                {{--                @endphp--}}
                {{--                <div class="row">--}}
                {{--                    <div class="col-md-3" style="text-align: right">--}}
                {{--                        <label class="form-label">Departments:</label>--}}
                {{--                    </div>--}}
                {{--                    <div class="col-md-9">--}}
                {{--                        @foreach($organizations as $organization)--}}
                {{--                            @if(in_array($organization->id, $org_id))--}}
                {{--                                <div class="form-check">--}}
                {{--                                    <input class="form-check-input" type="checkbox" name="org[]" id="org_{{ $organization->id }}" value="{{ $organization->id }}">--}}
                {{--                                    <label class="form-check-label" for="org_{{ $organization->id }}">{{ $organization->org_desc }}</label>--}}
                {{--                                </div>--}}
                {{--                            @endif--}}
                {{--                        @endforeach--}}
                {{--                    </div>--}}
                {{--                </div>--}}







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
    </div>













@endsection
