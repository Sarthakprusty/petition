@extends('layout')

@section('content')
{{--    @php--}}
{{--        print_r( session()->all());--}}
{{--    @endphp--}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-8">
            <h3 class="display-3">
                Applications
            </h3>
        </div>

        <div class="col-md-4" style="text-align: right;">
            <input class="form-control" type="text" placeholder="Search" id="searchInput" data-bs-toggle="modal" data-bs-target="#exampleModal"/>
        </div>
    </div>
<hr class="row-divider">

    <div class="row"></div>
    <div class="row" id="pageContent" >
        @foreach($applications as $application)
            <div class="col col-md-4" style="padding: 2%; ">
                <div class="card shadow">
                    <div class="card-header">
                        {{$application->reg_no}}&nbsp
                    </div>

                    <div class="card-body">
                        @if($application->applicant_name)
                            <h5 class="card-title">{{ $application->applicant_title }} {{ $application->applicant_name }}</h5>
                        @else
                            <div class="card-title">
                                Name:
                                <span class="float-end">N/A</span>
                            </div>
                        @endif

                        <div class="card-title">
                            Created dt.
                            <span class="float-end">{{ $application->created_at->format("d/m/Y") }}</span>
                        </div>

{{--                        @if($application->letter_subject)--}}
{{--                            <div class="card-subject">--}}
{{--                                @php--}}
{{--                                    $subject = $application->letter_subject;--}}
{{--                                    $trimmedSubject = strlen($subject) > 30 ? substr($subject, 0, 27) . '...' : $subject;--}}
{{--                                @endphp--}}
{{--                                {{ $trimmedSubject }}--}}
{{--                            </div>--}}
{{--                        @else--}}
{{--                            <div class="card-subject">--}}
{{--                                Letter Sub:--}}
{{--                                <span class="float-end">N/A</span>--}}
{{--                            </div>--}}
{{--                        @endif--}}


                        <div class="card-title"></div>

                        @if($application->department_org)
                            <div class="card-subject">

                                Fwd: {{  $application->trimmedremark }}
                            </div>
                        @elseif($application->reason)
                            <div class="card-subject">
                                Reason: {{  $application->trimmedremark }}
                            </div>
                        @else
                            <div class="card-subject">
                               N/A: <span class="float-end"> N/A</span>
                            </div>
                        @endif


                        <div class="card-title"></div>

                        <div>
                            Status.
                            <span class="float-end">{{ $application->statuses()->where('application_status.active', 1)->first()?->status_desc ?? '' }}</span>
                        </div>

                        <div class="card-title"></div>

{{--                        @if($application->statuses->first()->pivot->remarks)--}}
{{--                            <div class="card-subject">--}}
{{--                                @php--}}
{{--                                    $remark = $application->statuses->first()->pivot->remarks;--}}
{{--                                    $trimmedremark = strlen($remark) > 30 ? substr($remark, 0, 27) . '...' : $remark;--}}
{{--                                @endphp--}}
{{--                                {{ $trimmedremark }}--}}
{{--                            </div>--}}
{{--                        @else--}}
{{--                            <div class="card-subject">--}}
{{--                                remark:<span class="float-end"> N/A</span>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    </div>
                    <div class="card-footer text-body-secondary">
{{--                            @if ($application->statuses->first()->pivot->status_id != 0 && $application->statuses->first()->pivot->status_id != 4)--}}
                        <div class="float-start">
                            <form action="{{ route('applications.show', ['application' => $application]) }}" method="GET">
                                <button type="submit" class="btn btn-outline-primary" name="submit" value="Details">Details</button>
                            </form>
                        </div>
{{--                            @endif--}}
                    @if($application->allowPullBack == true)
                        <div class="float-end">
                            <button type="button" data-id="{{$application->id}}" class="btn btn-outline-danger pullbck" data-toggle="modal" data-target="#pullback">Pull Back </button>
                        </div>
                    @endif
                        @if($application->allowEdit == true)
                            <div class="float-end">
                                <form action="{{ route('applications.edit', ['application' => $application]) }}" method="GET">
                                    <button type="submit" class="btn btn-outline-warning">EDIT</button>
                                </form>
                            </div>
                        @endif
                        @if($application->allowFinalReply == true)
                            <div class="float-end">
                                <form action="{{ route('applications.show', ['application' => $application]) }}" method="GET">
                                    <button type="submit" class="btn btn-outline-warning" name="submit" value="final reply">Final Reply</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        {{--            {{ $applications->links() }}--}}

            @if (!isset($notpaginate))
        <div class="row">
            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between" style="margin-left: 1.5%">
                <span class="relative z-0 inline-flex shadow-sm rounded-md" style="margin-left: 43%">
                    @if ($applications->previousPageUrl())
                        <a href="{{ $applications->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:bg-gray-200" aria-label="Previous">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <a class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 opacity-50 cursor-not-allowed" aria-disabled="true" aria-label="Previous">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                    <span aria-current="page">
                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5">{{ $applications->currentPage() }}</span>
                    </span>
                    @if ($applications->nextPageUrl())
                        <a href="{{ $applications->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:bg-gray-200" aria-label="Next">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <a class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 opacity-50 cursor-not-allowed" aria-disabled="true" aria-label="Next">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </span>
            </nav>
        </div>
            @endif
    </div>
    @endsection
    @section('modal')
    <div class="modal fade" id="pullback"   tabindex="-1" aria-labelledby="pullbackLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top ">
            <form method="GET" action="{{ route('applications.pullback') }}">
                <div class="modal-content"style="" >
                    <div class="modal-header">
                         <h5 class="modal-title" id="pullbackLabel">Pull Back Application</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="app_no" name="app_no">
                        <div class="row">
                            <div>
                                <label class="form-check-label" id="pullback" >Remarks</label><span class="text-danger">*</span>
                                    <input type="text" name="remark" id="pullback" class="form-control" required>
                                  
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary" >Pull Back</button>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $(document).on("click", ".pullbck", function () {
                            var application_no = $(this).data('id'); 
                            $(".modal-body #app_no").val(application_no);
                        });
                    });
                </script>

            </form>
        </div>
    </div>
@endsection

