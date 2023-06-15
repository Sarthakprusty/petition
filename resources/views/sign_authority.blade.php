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
            <form method="POST" action="{{ route('authority.store') }}" enctype="multipart/form-data">
                @csrf
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
                        <label class="form-label" for="desg">Designation:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="desg" id="desg"  placeholder="Dept"  value="{{ old('desg') ?: $signAuthority->desg}}"required>
                    </div>
                </div>

                <div class="row" id="alignment">
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="letter_no">From date:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <input type="date" class="form-control datepicker" name="from_date" id="from_date" style="width: 89%; margin-left: 5%;" value="{{ old('from_date') ?: \Carbon\Carbon::parse($signAuthority->from_date)->format('Y-m-d') }}"required>
                        </div>
                    </div>
                </div>
                <div class="row" id="alignment">
                    <div class="col-md-3" style="text-align: right">
                        <label class="form-label" for="to_date">To date:<span style="color: red;" class="required">*</span></label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <input type="date" class="form-control datepicker" name="to_date" id="to_date" style="width: 89%; margin-left: 5%;" value="{{ old('to_date') ?: \Carbon\Carbon::parse($signAuthority->to_date)->format('Y-m-d') }}">
                        </div>
                    </div>

                    <hr class="row-divider">

                    <div class="row">
                        <div class="col-md-3"style="text-align: right"  >
                            <label class="form-label" for="Sign_path">Signature:<span style="color: red;" class="required">*</span></label>
                        </div>
                        <div class="col-md-3" style=padding-left:2%>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="Sign_path" name="Sign_path" accept=".pdf" required>
                                </div>
                                @if($signAuthority->Sign_path!='')
                                    <a href="/api/getFile/{{$signAuthority->Sign_path}}" target="_blank">View File</a>
                                @endif
                            </div>
                        </div>
                    </div>

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
