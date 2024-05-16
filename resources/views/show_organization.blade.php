@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h3 class="display-3">
            Organization
        </h3>
    </div>
    <div class="col-md-4" style="text-align: right;">
        <div class="row">
            <div class="col">
                <label class="form-check-label">
                    <input type="radio" name="details" value="S" id="state_gov" checked>
                    State GOV.
                </label>
                <label class="form-check-label">
                    <input type="radio" name="details" value="C" id="center_gov">
                    Center GOV.
                </label>
            </div>
        </div>
    </div>
</div>
<hr class="row-divider">
<div class="row"></div>

<div class="row" id="pageContent">
    <div class="card shadow" id="state_details">
        <div class="card-body">
            <div class="row">
            <form method="post" action="{{route('organizations.change') }}"  method="post">@csrf
                    <div class="mb-3" id="organizationS">
                        <table class="table">
                            <tr>
                                <th>S.No.</th>
                                <th>Organizations</th>
                                <th>Action</th>
                            </tr>
                            @foreach($organizations_state as $organizationSS)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $organizationSS->org_desc }}</td>
                                <td><button class="btn btn-outline-primary" name="org_id" value="{{ $organizationSS->id }}">Update</button></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow" id="center_details" style="display:none;">
        <div class="card-body">
            <div class="row">
            <form method="post" action="{{route('organizations.change') }}"  method="post">@csrf
                    <div class="mb-3" id="organizationM">
                        <table class="table">
                            <tr>
                                <th>S.No.</th>
                                <th>Organizations</th>
                                <th>Action</th>
                            </tr>
                            @foreach($organization_ministry as $organizationMM)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $organizationMM->org_desc }}</td>
                                <td><button class="btn btn-outline-primary" name="org_id" value="{{ $organizationMM->id }}">Update</button></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script>
    $(document).ready(function () {
        $('#state_gov').click(function () {
            $('#state_details').show();
            $('#center_details').hide();
        });

        $('#center_gov').click(function () {
            $('#state_details').hide();
            $('#center_details').show();
        });
    });
</script>
@endsection
