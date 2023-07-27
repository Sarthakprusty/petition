<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Petition</title>

    <!-- Fonts -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="/css/app.css" rel="stylesheet" />

    {{--<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}

    <script src="https://code.jquery.com/jquery-3.7.0.slim.min.js" integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="/js/tinymce_6.4.2/tinymce.min.js" referrerpolicy="origin"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
    <!-- Styles -->
    <style>
        /* ! tailwindcss v3.2.4 | MIT License | https://tailwindcss.com */*,::after,::before{box-sizing:border-box;border-width:0;border-style:solid;border-color:#e5e7eb}::after,::before{--tw-content:''}html{line-height:1.5;-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;font-family:Figtree, sans-serif;font-feature-settings:normal}body{margin:0;line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,pre,samp{font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}button,input,optgroup,select,textarea{font-family:inherit;font-size:100%;font-weight:inherit;line-height:inherit;color:inherit;margin:0;padding:0}button,select{text-transform:none}[type=button],[type=reset],[type=submit],button{-webkit-appearance:button;background-color:transparent;background-image:none}:-moz-focusring{outline:auto}:-moz-ui-invalid{box-shadow:none}progress{vertical-align:baseline}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}blockquote,dd,dl,figure,h1,h2,h3,h4,h5,h6,hr,p,pre{margin:0}fieldset{margin:0;padding:0}legend{padding:0}menu,ol,ul{list-style:none;margin:0;padding:0}textarea{resize:vertical}input::placeholder,textarea::placeholder{opacity:1;color:#9ca3af}[role=button],button{cursor:pointer}:disabled{cursor:default}audio,canvas,embed,iframe,img,object,svg,video{display:block;vertical-align:middle}img,video{max-width:100%;height:auto}[hidden]{display:none}*, ::before, ::after{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }::-webkit-backdrop{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }::backdrop{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }.relative{position:relative}.mx-auto{margin-left:auto;margin-right:auto}.mx-6{margin-left:1.5rem;margin-right:1.5rem}.ml-4{margin-left:1rem}.mt-16{margin-top:4rem}.mt-6{margin-top:1.5rem}.mt-4{margin-top:1rem}.-mt-px{margin-top:-1px}.mr-1{margin-right:0.25rem}.flex{display:flex}.inline-flex{display:inline-flex}.grid{display:grid}.h-16{height:4rem}.h-7{height:1.75rem}.h-6{height:1.5rem}.h-5{height:1.25rem}.min-h-screen{min-height:100vh}.w-auto{width:auto}.w-16{width:4rem}.w-7{width:1.75rem}.w-6{width:1.5rem}.w-5{width:1.25rem}.max-w-7xl{max-width:80rem}.shrink-0{flex-shrink:0}.scale-100{--tw-scale-x:1;--tw-scale-y:1;transform:translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}.grid-cols-1{grid-template-columns:repeat(1, minmax(0, 1fr))}.items-center{align-items:center}.justify-center{justify-content:center}.gap-6{gap:1.5rem}.gap-4{gap:1rem}.self-center{align-self:center}.rounded-lg{border-radius:0.5rem}.rounded-full{border-radius:9999px}.bg-gray-100{--tw-bg-opacity:1;background-color:rgb(243 244 246 / var(--tw-bg-opacity))}.bg-white{--tw-bg-opacity:1;background-color:rgb(255 255 255 / var(--tw-bg-opacity))}.bg-red-50{--tw-bg-opacity:1;background-color:rgb(254 242 242 / var(--tw-bg-opacity))}.bg-dots-darker{background-image:url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E")}.from-gray-700\/50{--tw-gradient-from:rgb(55 65 81 / 0.5);--tw-gradient-to:rgb(55 65 81 / 0);--tw-gradient-stops:var(--tw-gradient-from), var(--tw-gradient-to)}.via-transparent{--tw-gradient-to:rgb(0 0 0 / 0);--tw-gradient-stops:var(--tw-gradient-from), transparent, var(--tw-gradient-to)}.bg-center{background-position:center}.stroke-red-500{stroke:#ef4444}.stroke-gray-400{stroke:#9ca3af}.p-6{padding:1.5rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.text-center{text-align:center}.text-right{text-align:right}.text-xl{font-size:1.25rem;line-height:1.75rem}.text-sm{font-size:0.875rem;line-height:1.25rem}.font-semibold{font-weight:600}.leading-relaxed{line-height:1.625}.text-gray-600{--tw-text-opacity:1;color:rgb(75 85 99 / var(--tw-text-opacity))}.text-gray-900{--tw-text-opacity:1;color:rgb(17 24 39 / var(--tw-text-opacity))}.text-gray-500{--tw-text-opacity:1;color:rgb(107 114 128 / var(--tw-text-opacity))}.underline{-webkit-text-decoration-line:underline;text-decoration-line:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.shadow-2xl{--tw-shadow:0 25px 50px -12px rgb(0 0 0 / 0.25);--tw-shadow-colored:0 25px 50px -12px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.shadow-gray-500\/20{--tw-shadow-color:rgb(107 114 128 / 0.2);--tw-shadow:var(--tw-shadow-colored)}.transition-all{transition-property:all;transition-timing-function:cubic-bezier(0.4, 0, 0.2, 1);transition-duration:150ms}.selection\:bg-red-500 *::selection{--tw-bg-opacity:1;background-color:rgb(239 68 68 / var(--tw-bg-opacity))}.selection\:text-white *::selection{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.selection\:bg-red-500::selection{--tw-bg-opacity:1;background-color:rgb(239 68 68 / var(--tw-bg-opacity))}.selection\:text-white::selection{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.hover\:text-gray-900:hover{--tw-text-opacity:1;color:rgb(17 24 39 / var(--tw-text-opacity))}.hover\:text-gray-700:hover{--tw-text-opacity:1;color:rgb(55 65 81 / var(--tw-text-opacity))}.focus\:rounded-sm:focus{border-radius:0.125rem}.focus\:outline:focus{outline-style:solid}.focus\:outline-2:focus{outline-width:2px}.focus\:outline-red-500:focus{outline-color:#ef4444}.group:hover .group-hover\:stroke-gray-600{stroke:#4b5563}@media (prefers-reduced-motion: no-preference){.motion-safe\:hover\:scale-\[1\.01\]:hover{--tw-scale-x:1.01;--tw-scale-y:1.01;transform:translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}}@media (prefers-color-scheme: dark){.dark\:bg-gray-900{--tw-bg-opacity:1;background-color:rgb(17 24 39 / var(--tw-bg-opacity))}.dark\:bg-gray-800\/50{background-color:rgb(31 41 55 / 0.5)}.dark\:bg-red-800\/20{background-color:rgb(153 27 27 / 0.2)}.dark\:bg-dots-lighter{background-image:url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E")}.dark\:bg-gradient-to-bl{background-image:linear-gradient(to bottom left, var(--tw-gradient-stops))}.dark\:stroke-gray-600{stroke:#4b5563}.dark\:text-gray-400{--tw-text-opacity:1;color:rgb(156 163 175 / var(--tw-text-opacity))}.dark\:text-white{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.dark\:shadow-none{--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.dark\:ring-1{--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);box-shadow:var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000)}.dark\:ring-inset{--tw-ring-inset:inset}.dark\:ring-white\/5{--tw-ring-color:rgb(255 255 255 / 0.05)}.dark\:hover\:text-white:hover{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.group:hover .dark\:group-hover\:stroke-gray-400{stroke:#9ca3af}}@media (min-width: 640px){.sm\:fixed{position:fixed}.sm\:top-0{top:0px}.sm\:right-0{right:0px}.sm\:ml-0{margin-left:0px}.sm\:flex{display:flex}.sm\:items-center{align-items:center}.sm\:justify-center{justify-content:center}.sm\:justify-between{justify-content:space-between}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width: 768px){.md\:grid-cols-2{grid-template-columns:repeat(2, minmax(0, 1fr))}}@media (min-width: 1024px){.lg\:gap-8{gap:2rem}.lg\:p-8{padding:2rem}}
    </style>
</head>
<body>
@include('common.pre-header')
@include('common.header')
<div class="main-body" style="margin-top: 113px;">

    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-md-2" style="background: #9E633E;width: 22%; position: fixed;">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100" >
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                        <li>
                            <a href="{{route('applications.dashboard')}}" >
                                <i class="fs-4 bi-border-style"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Dashboard</span> </a>
                        </li>
                        <br>
                        <li>
                            <a href="{{route('applications.index')}}" >
                                <i class="fs-4 bi-postcard"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Applications</span> </a>
                        </li>
                        @if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1))
                        <br>
                        <li>
                            <a href="{{route('applications.create')}}" >
                                <i class="fs-4 bi-pencil-square"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Entry Form</span>
                            </a>
                        </li>
                        @endif
                        <br>
                        <li>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fs-4 bi-search"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Search</span>
                            </a>
                        </li>
                        @if (auth()->check() && auth()->user()->roles->pluck('id')->contains(3) && auth()->user()->sign_id != null)

                                <br>
                                <li>
                                    <a href="{{route('authority.index')}}" >
                                        <i class="fs-4 bi-file-earmark-person"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Authority</span>
                                    </a>
                                </li>
                            @endif
                        @if (auth()->check() && auth()->user()->roles->pluck('id')->contains(3) && auth()->user()->sign_id == null)
                                <br>
                                <li>
                                    <a href="{{route('authority.create')}}" >
                                        <i class="fs-4 bi-file-earmark-person"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Authority</span>
                                    </a>
                                </li>
                            @endif


                        @if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1))
                        <br>
                        <li>
                            <a href="#"data-bs-toggle="modal" data-bs-target="#Report">
                                <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Report</span>
                            </a>
                        </li>
                        <br>
                        <li>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#letter">
                                <i class="fs-4 bi-printer"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Print Letter</span>
                            </a>
                        </li>
                        @endif
                        <br>
                        <li>
                            <a href="{{ route('logout') }}">
                                <i class="fs-4 bi-box-arrow-in-left"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Log Out</span>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
            <div class="col-md-8 col-lg-9" style="margin-left: 23.5%; margin-top: 2%; overflow-y: auto;">
                @yield('content')
            </div>
        </div>
    </div>
</div>
@yield('modal')
{{--modal section--}}

<div>
    <div class="modal fade" id="exampleModal" style="z-index: 1051" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <form method="get" action="{{route('application.search')}}">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class ='mb-3'>
                            @if (auth()->check() && auth()->user()->organizations()->where('user_organization.active', 1)->count()>1)
                                @php
                                    $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
                                    $organizations=\App\Models\Organization::all()
                                @endphp
                                <label for="org" class="form-label">Choose Organization</label>
                                <select class="form-control" id="organization" name="organization">
                                    <option value="">Select an Organization</option>
                                    @foreach($organizations as $organization)
                                        @if(in_array($organization->id, $org_id))
                                            <option value="{{ $organization->id }}">{{ $organization->org_desc }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Choose Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All</option>
                                <option value="0">Draft</option>
                                <option value="1">Pending at DH</option>
                                <option value="2">Pending at SO</option>
                                <option value="3">Pending at US</option>
                                <option value="4">Approved</option>
                                <option value="5">Submitted</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nametxt" class="form-label">Name</label>
                            <input type="text" class="form-control" id="nametxt" name="applicant_name" placeholder="Search By Name">
                        </div>
                        <div class="mb-3">
                            <label for="regnotxt" class="form-label">Reg. No.</label>
                            <input type="text" class="form-control" id="regnotxt" name="reg_no" placeholder="Search By Registration No.">
                        </div>
                            @php
                            $states=\App\Models\State::all();
                            @endphp
                        <div class="mb-3">
                            <label for="state_id" class="form-label">State</label>
                            <select class="form-control" name="state_id" id="state_id" >
                                <option value="">-Select State-</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" >{{ $state->state_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="letterno" class="form-label">Letter No.</label>
                            <input type="text" class="form-control" id="letterno" name="letter_no" placeholder="Search By Letter No.">
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="organizationType">Organization:</label>
                                <select class="form-control" name="orgTy">
                                    <option value="no">Select an Option</option>
                                    <option id="typeOrganizationType" value="type" >State GOV.</option>
                                    <option id="typeOrganizationName" value="name">Center GOV.</option>
                                </select>
                            </div>
                        </div>
                        @php
                            $organizationStates = \App\Models\Organization::where('org_type','S')->get();
                            $organizationM = \App\Models\Organization::where('org_type','M')->get();
                        @endphp
                        <div class="mb-3" id="organizationSt" style="display: none;">
                            <select class="form-control" id="orgS" name="orgDesc">
                                <option value="">Select an State</option>
                                @foreach($organizationStates as $state)
                                    <option value="{{ $state->id }}">{{ $state->org_desc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" id="organizationMi" style="display: none;">
                            <select class="form-control" id="orgM" name="orgDesc">
                                <option value="">Select an Organization</option>
                                @foreach($organizationM as $organization)
                                    <option value="{{ $organization->id }}">{{ $organization->org_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('select[name="orgTy"]').change(function() {
                                    var selectedType = $(this).val();
                                    if (selectedType === "type") {
                                        $('#organizationSt').show();
                                        $('#organizationMi').hide();
                                    } else if (selectedType === "name") {
                                        $('#organizationSt').hide();
                                        $('#organizationMi').show();
                                    }
                                    else  {
                                        $('#organizationSt').hide();
                                        $('#organizationMi').hide();
                                    }
                                });
                            });
                        </script>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appdttxtfrom" class="form-label">From date</label>
                                    <input type="date" class="form-control" id="appdttxtfrom" name="app_date_from" placeholder="Search By Application Date" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appdttxtto" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="appdttxtto" name="app_date_to" placeholder="Search By Application Date" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<li>
    <a href="#"data-bs-toggle="modal" data-bs-target="#Report">
        <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Report</span>
    </a>
</li>
<br>
<li>
    <a href="#" data-bs-toggle="modal" data-bs-target="#letter">
        <i class="fs-4 bi-printer"></i> <span class="ms-1 d-none d-sm-inline" style="color: #FFE6C3;">Print Letter</span>
    </a>
</li>
<div>
    <div class="modal fade" id="letter" style="z-index: 1051" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <form method="get" action="{{route('application.reportprint')}}">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appdttxtfrom" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="appdttxtfrom" name="app_date_from" placeholder="Search By Application Date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appdttxtto" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="appdttxtto" name="app_date_to" placeholder="Search By Application Date" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="regnotxt" class="form-label">Reg. No.</label>
                            <input type="text" class="form-control" id="regnotxt" name="reg_no" placeholder="Search By Registration No.">
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="mail">Mail:</label>
                                <select class="form-control" name="mail">
                                    <option id="mail" value="all" selected>All</option>
                                    <option id="mail" value="filtered">Not Mailed</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="organizationType">Organization:</label>
                                <select class="form-control" name="orgType">
                                    <option value="">Select an option</option>
                                    <option id="typeOrganizationType" value="type" >State GOV.</option>
                                    <option id="typeOrganizationName" value="name">Center GOV.</option>
                                </select>
                            </div>
                        </div>
                        @php
                            $organizationStates = \App\Models\Organization::where('org_type','S')->get();
                            $organizationM = \App\Models\Organization::where('org_type','M')->get();
                        @endphp
                        <div class="mb-3" id="organizationTypeDropdown" style="display: none;">
                            <select class="form-control" id="orgDesc" name="orgDesc">
                                <option value="">Select an State</option>
                                @foreach($organizationStates as $state)
                                    <option value="{{ $state->id }}">{{ $state->org_desc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" id="organizationNameDropdown" style="display: none;">
                            <select class="form-control" id="orgDesc" name="orgDesc">
                                <option value="">Select an Organization</option>
                                @foreach($organizationM as $organization)
                                    <option value="{{ $organization->id }}">{{ $organization->org_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('select[name="orgType"]').change(function() {
                                    var selectedType = $(this).val();
                                    if (selectedType === "type") {
                                        $('#organizationTypeDropdown').show();
                                        $('#organizationNameDropdown').hide();
                                    } else if (selectedType === "name") {
                                        $('#organizationTypeDropdown').hide();
                                        $('#organizationNameDropdown').show();
                                    }else{
                                        $('#organizationTypeDropdown').hide();
                                        $('#organizationNameDropdown').hide();
                                    }
                                });
                            });
                        </script>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary" name="submit" value="acknowledgement">Acknowledgement</button>
                        <button type="submit" class="btn btn-outline-primary" name="submit" value="Forward">Forward</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div>
    <div class="modal fade" id="Report" style="z-index: 1051" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <form method="get" action="{{route('application.reportprint')}}">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appdttxtfrom" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="appdttxtfrom" name="app_date_from" placeholder="Search By Application Date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appdttxtto" class="form-label">To date</label>
                                    <input type="date" class="form-control" id="appdttxtto" name="app_date_to" placeholder="Search By Application Date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="organizationType">Organization:</label>
                                <select class="form-control" name="orgT">
                                    <option value="">Select an option</option>
                                    <option id="typeOrganizationType" value="type" >State GOV.</option>
                                    <option id="typeOrganizationName" value="name">Center GOV.</option>
                                </select>
                            </div>
                        </div>
                        @php
                            $organizationStates = \App\Models\Organization::where('org_type','S')->get();
                            $organizationM = \App\Models\Organization::where('org_type','M')->get();
                        @endphp
                        <div class="mb-3" id="organizationS" style="display: none;">
                            <select class="form-control" id="orgS" name="orgDesc">
                                <option value="">Select an State</option>
                                @foreach($organizationStates as $state)
                                    <option value="{{ $state->id }}">{{ $state->org_desc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" id="organizationM" style="display: none;">
                            <select class="form-control" id="orgM" name="orgDesc">
                                <option value="">Select an Organization</option>
                                @foreach($organizationM as $organization)
                                    <option value="{{ $organization->id }}">{{ $organization->org_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('select[name="orgT"]').change(function() {
                                    var selectedType = $(this).val();
                                    if (selectedType === "type") {
                                        $('#organizationS').show();
                                        $('#organizationM').hide();
                                    } else if (selectedType === "name") {
                                        $('#organizationS').hide();
                                        $('#organizationM').show();
                                    }else{
                                        $('#organizationS').hide();
                                        $('#organizationM').hide();
                                    }
                                });
                            });
                        </script>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary" name="submit" value="forwardTable">Forward Cases</button>
                        <button type="submit" class="btn btn-outline-primary" name="submit" value="final_Reply">Final Reply</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //orgType






    tinymce.init({
        selector: 'textarea.editor',
        plugins: 'preview importcss searchreplace autolink directionality visualblocks visualchars link table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount quickbars',
        promotion: false,
        browser_spellcheck: true,
        contextmenu: false
    });
    // Prevent Bootstrap dialog from blocking focusin
    document.addEventListener('focusin', (e) => {
        if (e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
            e.stopImmediatePropagation();
        }
    });
</script>
@include('common.footer')
</body>
</html>






