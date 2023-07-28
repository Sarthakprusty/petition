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
            <form method="POST" action="{{route('authority.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="row"></div>
                <div class="row"></div>

                <div class="row">
                    <div class="col-md-3" style="text-align: right"><label class="form-label">Name:<span style="color: red;" class="required">*</span></label></div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="name" aria-label="Name" placeholder="Name" name="name"  required value="{{ old('name') ?: $signAuthority->name}}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label">Name hindi:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="name_hin" aria-label="name_hin" placeholder="Name" name="name_hin"  required value="{{ old('name_hin') ?: $signAuthority->name}}">
                        </div>
                    </div>
                </div>

                <div class="row"></div>

                <div class="row" >
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="letter_no">From date:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <input type="date" class="form-control datepicker" name="from_date" id="from_date" style="width: 89%; margin-left: 5%;" value="{{ old('from_date') ?: \Carbon\Carbon::parse($signAuthority->from_date)->format('Y-m-d') }}"required>
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

                <div class="row" id="alignment">

                    @if($signAuthority->Sign_path!='')
                        <div class="row">
                            <div class="col-6" style="text-align: right">
                                <a href="/api/signFile/{{$signAuthority->Sign_path}}" target="_blank">
                                    <button type="button" class="btn btn-outline-primary">View sign</button>
                                </a>
                            </div>
                            <div class="col-6" >
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="Sign_path" name="Sign_path" accept=".png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-3"style="text-align: right"  >
                                <label class="form-label" for="Sign_path">Signature(50kb):<span style="color: red;" class="required">*</span></label>
                            </div>
                            <div class="col-md-3" style=padding-left:2%>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="Sign_path" name="Sign_path" accept=".png" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>


                <hr class="row-divider">

                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-3" style=padding-left:20%>
                        <span id="file-status"></span>
                        <div  style="text-align: left">
                            <input type="submit" class="btn btn-outline-success" name="submit" value="Save">
                        </div>
                    </div>
                </div>
            </form>
            <script>
                $(function() {
                    var today = new Date().toISOString().split('T')[0];
                    $(".datepicker").attr('min', today);

                    $(".datepicker").on('change', function() {
                        var selectedDate = $(this).val();
                        if (selectedDate === '') {
                            $(this).val(null);
                        } else if (selectedDate < today) {
                            $(this).val(today);
                        }
                    });
                });

            </script>
        </div>
    </div>
@endsection
