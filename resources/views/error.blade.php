@extends('layout')

@section('content')
    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">{{$error['message']}}</h1>
            <p class="col-md-8 fs-4">{{$error['description']}}</p>
            @if(isset($error['validationErrors']) && $error['validationErrors'] instanceof MessageBag && $error['validationErrors']->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($error['validationErrors']->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
{{--            <a href="{{$error['redirectUrl']}}" class="btn btn-outline-primary btn-lg" type="button">{{$error['redirectName']}}</a>--}}
        </div>
    </div>
@endsection

