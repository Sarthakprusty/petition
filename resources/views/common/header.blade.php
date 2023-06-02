<div class="fixed-top">
    <div class="pre-header">
        <div class="container-fluid">
            <div class="col-md-6">

                <a href="http://presidentofindia.nic.in/" class="nav-link px-2 link-light" target="_blank" title="http://presidentofindia.nic.in/, The President of India : External website that opens in a new window" style="margin-left: 1%;">The President of India Website<img src="https://rb.nic.in/rbvisit/images/ext-link-icon.gif" alt="External website that opens in a new window" title="External website that opens in a new window" width="9" height="9" class="extIcon"  /></a>

            </div>
        </div>
    </div>

    <nav class="p-3 mb-3 border-bottom navbar navbar-fixed-top">
        <div class="container-fluid">

            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                <img class="bi me-2" width="213" height="51" role="img" aria-label="Bootstrap" src="https://rb.nic.in/rbvisit/images/logonew.png"  />
            </a>

{{--            <ul class="nav col-1 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">--}}

{{--                <li><div class="bd-navbar-toggle">--}}
{{--                        <button class="navbar-toggler p-2" type="button" aria-label="Toggle menu" (click)="expandContract()">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="bi" fill="currentColor" viewBox="0 0 16 16">--}}
{{--                                <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"></path>--}}
{{--                            </svg>--}}

{{--                            <span class="d-none fs-6 pe-1">Menu</span>--}}
{{--                        </button>--}}
{{--                    </div></li>--}}

{{--            </ul>--}}

            <div class="d-flex float-end">
                <!--<form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3 " role="search">
                  <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
                </form>-->
                <div></div>
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        {{--<img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">&nbsp;--}}
                        @if(\Illuminate\Support\Facades\Auth::check())
                            {{\Illuminate\Support\Facades\Auth::user()->username}}
                        @else
                            N/A
                        @endif

                    </a>
                    @php
                     $user = Auth::user()->username;
                    @endphp
                    <ul class="dropdown-menu text-small">
{{--                        <li><a class="dropdown-item" href="#">New project...</a></li>--}}
{{--                        <li><a class="dropdown-item" href="#">Settings</a></li>--}}
{{--                        <li><a class="dropdown-item" href="/profile/edit">Profile</a></li>--}}
{{--                        <li><hr class="dropdown-divider"></li>--}}
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Change Password</a></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>



    <div class="menue-div elevation-2" id="menu">
        <div class="container px-4 py-5" id="featured-3">
            <div class="row">
                <div class="col">
                    <span class="pb-2 border-bottom link-grp">Garden</span>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-lg-3">
                <div class="col" style="padding: 10px;">
                    <a href="#">Admin</a>
                </div>
                <div class="col">
                    <a href="#">Dashboard</a>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <span class="pb-2 border-bottom link-grp">Attendance</span>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-lg-3 menue-row">
                <div class="col-1" style="padding: 10px;">
                    <a href="#">Admin</a>
                </div>
                <div class="col-1">
                    <a href="#">Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
