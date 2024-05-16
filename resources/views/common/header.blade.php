<div class="fixed-top">
    <div class="pre-header">
        <div class="container-fluid">
            <div class="col-md-6">

                <a href="http://presidentofindia.nic.in/" class="nav-link px-2 link-light" target="_blank" title="http://presidentofindia.nic.in/, The President of India : External website that opens in a new window" style="margin-left: 1%;">The President of India Website</a>

            </div>
        </div>
    </div>
    <nav class="p-3 mb-3 border-bottom navbar navbar-fixed-top">

        <div class="container-fluid">
            <div class="d-flex float-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                <img class="bi me-2" width="213" height="51" role="img" aria-label="Bootstrap" src="https://rb.nic.in/rbvisit/images/logonew.png"  />
            </a>
            </div>
            <div class="d-flex float-end" style="margin-right: 4%; color: #9E633E;text-decoration: underline;">

                @if(Auth::check())
                    {{Auth::user()->username}} / {{Auth::user()->employee_name}}
                @else
                    N/A
                @endif




{{--                <div class="dropdown text-end">--}}
{{--                    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                        @if(Auth::check())--}}
{{--                            {{Auth::user()->username}}--}}
{{--                        @else--}}
{{--                            N/A--}}
{{--                        @endif--}}

{{--                    </a>--}}
{{--                    @php--}}
{{--                        $user = Auth::user()->username;--}}
{{--                    @endphp--}}
{{--                    <ul class="dropdown-menu text-small dropdown-menu-center" >--}}
{{--                        <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>--}}
{{--                    </ul>--}}
{{--                </div>--}}

            </div>
        </div>
    </nav>
</div>
