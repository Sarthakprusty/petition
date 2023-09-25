@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h3 class="display-3">
                {{$org}}
            </h3>
        </div>
        @if ($allowfilter)
        <div class="col-md-4" style="text-align: right;">
            <input class="form-control" type="text" placeholder="Search" id="searchInput" data-bs-toggle="modal" data-bs-target="#org_status"/>
        </div>
        @endif
    </div>
    <hr class="row-divider">

    <div class="row"></div>
    <form method="get" action="{{route('application.search')}}">
        @if(isset($org_idclick))
            <input type="hidden" value="{{ implode(',', $org_idclick) }}" name="organization" >
        @endif
    <div class="row" id="pageContent" >
        <div class="col col-md-4" style="padding: 2%; ">
            <a href="#" onclick="submitForm(0)">
                <div class="card bg-light mb-3" style="max-width: 18rem;">
                <div class="card-header">Draft</div>
            <div class="card-body">
                <h5 class="card-title" style="font-size: xx-large">{{$in_draft}}</h5>
            </div>
        </div>
            </a>
        </div>
        <div class="col col-md-4" style="padding: 2%; ">
            <a href="#" onclick="submitForm(1)">
                <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
            <div class="card-header">Pending at DH</div>
            <div class="card-body">
                <h5 class="card-title"style="font-size: xx-large">{{$pending_with_dh}}</h5>
            </div>
        </div></a></div>

        <div class="col col-md-4" style="padding: 2%; ">
            <a href="#" onclick="submitForm(2)">
        <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
            <div class="card-header">Pending at SO</div>
            <div class="card-body">
                <h5 class="card-title"style="font-size: xx-large">{{$pending_with_so}}</h5>
            </div>
        </div></a></div>
        <div class="col col-md-4" style="padding: 2%; ">
            <a href="#" onclick="submitForm(3)">
        <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
            <div class="card-header">Pending at US</div>
            <div class="card-body">
                <h5 class="card-title"style="font-size: xx-large">{{$pending_with_us}}</h5>
            </div>
        </div></a></div>
        <div class="col col-md-4" style="padding: 2%; ">
            <a href="#" onclick="submitForm(4)">
        <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Approved</div>
            <div class="card-body">
                <h5 class="card-title"style="font-size: xx-large">{{$approved}}</h5>
            </div>
        </div></a></div>
        <div class="col col-md-4" style="padding: 2%; ">
            <a href="#" onclick="submitForm(5)">
                <div class="card text-white bg-info  mb-3" style="max-width: 18rem;">
            <div class="card-header">Submitted</div>
            <div class="card-body">
                <h5 class="card-title"style="font-size: xx-large">{{$submitted}}</h5>
            </div>
        </div></a>
        </div>
        <input type="hidden" name="status" id="statusInput">
        <button type="submit" id="submitBtn" style="display: none;"></button>
    </div>
    </form>
    <script>
        // Function to submit the form with the given status value
        function submitForm(statusValue) {
            document.getElementById('statusInput').value = statusValue;
            document.getElementById('submitBtn').click();
        }
    </script>

@endsection
@section('modal')
    @if ($allowfilter)
        <div class="modal fade" id="org_status" style="z-index: 1051" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <form method="get" action="{{route('applications.dashboard')}}">
                <div class="modal-content"style="margin-left: 60%" >
                    <div class="modal-body">
                        <div class="row">
                            <div class ='mb-3'>
                                <label for="org" class="form-label">Choose Organization</label>
                                    <select class="form-control" id="organization" name="organization">
                                        <option value="">-ALL-</option>
                                        @foreach($organizations as $organization)
                                            @if(in_array($organization->id, $org_id))
                                                <option value="{{ $organization->id }}">{{ $organization->org_desc }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary" name="submit" value="Search">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    @endif


@endsection

