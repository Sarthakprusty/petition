@extends('layout')

@section('content')

    <h3 class="display-3">
        Applications
        @php
            $userOrganizationId = Auth::user()->org_id;
            $userRoleId = Auth::user()->roles()->where('user_id', Auth::user()->id)->pluck('role_id')->first();
        @endphp
        @if ($userOrganizationId == 3 && $userRoleId == 1)
            <a href="{{ route('applications.create') }}" class="btn btn-primary">
                <i class="bi-plus-circle"></i>
                New
            </a>
        @endif
    </h3>

<div class="row">
    @foreach ($applications as $application)
        <div class="col col-md-4" style="padding: 20px;">
            <div class="card shadow">
                <div class="card-header">
                    {{$application->reg_no}}&nbsp
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{$application->applicant_title}} {{$application->applicant_name}}</h5>
                    <div>
                        Letter Dt.
                        <span class="float-end">{{$application->letter_date->format("d/m/Y")}}</span>
                    </div>
                    <div>
                        Letter Sub.
                        <span class="float-end">{{$application->letter_subject}}</span>
                    </div>

                </div>
                <div class="card-footer text-body-secondary">
                    @if (isset($for_reply) && $for_reply)
                        <div class="float-end">
                            <a href="{{ route('reply.create', ['id' => $application->id]) }}" class="btn btn-success">Reply</a>
                        </div>
                    @else
                        <div class="float-start">
                            <a href="{{ route('applications.show', ['application' => $application]) }}" class="btn btn-primary">VIEW</a>
                        </div>

                        @if ($userOrganizationId == 3 && $userRoleId == 1)
                            <div class="float-end">
                                <a href="{{ route('applications.edit', ['application' => $application]) }}" class="btn btn-warning">EDIT</a>
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
