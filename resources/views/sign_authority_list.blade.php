@extends('layout')

@section('content')
        <div class="row">
            <div class="col-md-3">
                <h3 class="display-3">
                    Authority
                </h3>
            </div>
            <div class="col-md-4" style="margin-top: 4%">
            <form action="{{ route('authority.create') }}" method="GET">
                <button type="submit" class="btn btn-outline-primary" name="submit" value="New Authority"><i class="bi bi-pencil-square"> New Authority?</i></button>
            </form>
            </div>
        </div>
        <hr class="row-divider">

        <div class="row" style="padding-right: 68%;" >
        <div class="container" style="width: 90%; margin-bottom: 10%" >
            <style>
                body {
                    background-color: #f5f7fa;
                }

                .testimonial-card .card-up {
                    height: 120px;
                    overflow: hidden;
                    border-top-left-radius: .25rem;
                    border-top-right-radius: .25rem;
                }

                .aqua-gradient {
                    background: linear-gradient(40deg, #2096ff, #05ffa3) !important;
                }

                .testimonial-card .avatar {
                    width: 120px;
                    margin-top: -60px;
                    overflow: hidden;
                    border: 5px solid #fff;
                    border-radius: 50%;
                }
                .my-5 {
                    margin-top: 2rem !important;
                }
            </style>


            <section class="mx-auto my-5" style="max-width: 23rem;">

                <div class="card testimonial-card mt-2 mb-3">
                    <div class="card-up aqua-gradient"></div>
                    <div class="avatar mx-auto white">
                        <img src="https://wvnpa.org/content/uploads/blank-profile-picture-973460_1280-768x768.png" class="rounded-circle img-fluid">
                    </div>
                    <div class="card-body text-center">
                        <h4 class="card-title font-weight-bold"> @if($authority->name)
                                {{ $authority->name }}
                            @else

                                N/A

                            @endif <br>
                            @if($authority->name_hin)
                                {{ $authority->name_hin }}
                            @else

                                N/A

                            @endif
                        </h4>

                        @if($authority->from_date)
                            {{ \Carbon\Carbon::parse($authority->from_date)->format("d/m/Y") }}
                        @else
                            N/A
                        @endif
                        <div class="row"></div>
                        <hr>
                        <div class="row"></div>
                        @if ($allowSeeSign )
                            <div class ="row">
                                <div class="col-6">
                                    @if($authority->Sign_path)
                                    <a href="{{url('/api/signFile/'.$authority->Sign_path)}}" target="_blank">
                                        <button type="button" class="btn btn-outline-primary">View sign</button>
                                    </a>
                                    @endif
                                </div>
                                <div class="col-6">
                                    {{--                            <form action="{{ route('authority.edit', ['authority' => $authority]) }}" method="GET">--}}
                                    <form action="{{ route('authority.remove') }}" method="post">@csrf
                                        <button type="submit" class="btn btn-outline-danger" style="font-size: 97%;">Delete sign</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection
