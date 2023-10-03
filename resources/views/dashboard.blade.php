@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h3 class="display-3">
                {{$org}}
            </h3>
        </div>
        <div class="col-md-4" style="text-align: right;">
            @if ($allowfilter)
                <div class="row">
                    <input class="form-control" type="text" placeholder="Search" id="searchInput" data-bs-toggle="modal" data-bs-target="#org_status"/>
                </div>
            @else
                <div class="row">&nbsp</div>
            @endif
            <div class="row">
                <div class="col">
                    <label class="form-check-label">
                        <input type="radio" name="details" value="E" id="Petition_details" checked>
                        Petition dtl.
                    </label>
                    <label class="form-check-label">
                        <input type="radio" name="details" value="H" id="Dispatch_details" >
                        Dispatch dtl.
                    </label>
                </div>
            </div>
        </div>
    </div>
    <hr class="row-divider">

    <div class="row"></div>
    <div class="row" id="pageContent" >
        <form method="get" action="{{route('application.search')}}">
            @if(isset($org_idclick))
                <input type="hidden" value="{{ implode(',', $org_idclick) }}" name="organization" >
            @endif

            <div class="row" id="formDetails" >
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
                        </div>
                    </a>
                </div>

                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitForm(2)">
                        <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                            <div class="card-header">Pending at SO</div>
                            <div class="card-body">
                                <h5 class="card-title"style="font-size: xx-large">{{$pending_with_so}}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitForm(3)">
                        <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                            <div class="card-header">Pending at US</div>
                            <div class="card-body">
                                <h5 class="card-title"style="font-size: xx-large">{{$pending_with_us}}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitForm(4)">
                        <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                            <div class="card-header">Approved</div>
                            <div class="card-body">
                                <h5 class="card-title"style="font-size: xx-large">{{$approved}}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitForm(5)">
                        <div class="card text-white bg-info  mb-3" style="max-width: 18rem;">
                            <div class="card-header">Response by Ministry/State</div>
                            <div class="card-body">
                                <h5 class="card-title"style="font-size: xx-large">{{$submitted}}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <input type="hidden" name="status" id="statusInput">
            <button type="submit" id="submitBtn" style="display: none;"></button>
        </form>

        <div class="row" id="dispatch" style="display: none">
            <form method="get" action="{{route('application.reportprint')}}">
                <div class="row">
                @if(isset($org_idclick))
                    <input type="hidden" value="{{ implode(',', $org_idclick) }}" name="organization" >
                @endif
                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitAck('mailed')">
                        <div class="card bg-light mb-3" style="max-width: 18rem;">
                            <div class="card-header">Ack. mailed</div>
                            <div class="card-body">
                                <h5 class="card-title" style="font-size: xx-large">{{$ackMailSent}}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitAck('Pending')">
                        <div class="card bg-warning mb-3" style="max-width: 18rem;">
                            <div class="card-header">Ack. Pending</div>
                            <div class="card-body">
                                <h5 class="card-title"style="font-size: xx-large">{{$ackPending}}</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitAck('Offline')">
                        <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                            <div class="card-header">Ack. dispatched</div>
                            <div class="card-body">
                                <h5 class="card-title"style="font-size: xx-large">{{$ackDispatched}}</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <input type="hidden" name="mail" id="printInputAck">
                <button type="submit" id="submitAck" value="acknowledgement" name="submit" style="display: none;"></button>
                </div>
            </form>

            <form method="get" action="{{route('application.reportprint')}}">
                <div class="row">
                @if(isset($org_idclick))
                    <input type="hidden" value="{{ implode(',', $org_idclick) }}" name="organization" >
                @endif

                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitFwd('mailed')">
                        <div class="card bg-light mb-3" style="max-width: 18rem;">
                            <div class="card-header">Fwd. mailed</div>
                            <div class="card-body">
                                <h5 class="card-title"style="font-size: xx-large">{{$fwdMailSent}}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitFwd('Pending')">
                        <div class="card bg-warning mb-3" style="max-width: 18rem;">
                            <div class="card-header">Fwd. pending</div>
                            <div class="card-body">
                                <h5 class="card-title"style="font-size: xx-large">{{$fwdPending}}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col col-md-4" style="padding: 2%; ">
                    <a href="#" onclick="submitFwd('Offline')">
                        <div class="card text-white bg-success  mb-3" style="max-width: 18rem;">
                            <div class="card-header">Fwd. dispatched</div>
                            <div class="card-body">
                                <h5 class="card-title"style="font-size: xx-large">{{$fwdDispatched}}</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <input type="hidden" name="mail" id="printInputFwd">
                    <button type="submit" id="submitFwd" value="Forward" name="submit" style="display: none;"></button></div>
            </form>
        </div>
    </div>
    <script>

        $(document).ready(function () {
            $('#Petition_details').click(function () {
                $('#formDetails').show();
                $('#dispatch').hide();
            });

            $('#Dispatch_details').click(function () {
                $('#formDetails').hide();
                $('#dispatch').show();
            });
        });



        // Function to submit the form with the given status value
        function submitForm(statusValue) {
            document.getElementById('statusInput').value = statusValue;
            document.getElementById('submitBtn').click();
        }

        function submitAck(printstatusAck) {
            document.getElementById('printInputAck').value = printstatusAck;
            document.getElementById('submitAck').click();
        }

        function submitFwd(printstatusFwd) {
            document.getElementById('printInputFwd').value = printstatusFwd;
            document.getElementById('submitFwd').click();
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

