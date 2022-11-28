{{-- Main navbar --}}
<div class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="navbar-brand">
        <a href="/" class="d-inline-block">
            <img src="{{ asset('themes/images/logo_light.png') }}" alt="">
        </a>
    </div>

    @auth('api')
    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>
    @endauth

    <div class="collapse navbar-collapse" id="navbar-mobile">
        @auth('api')
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>
        @endauth

        @guest
        <span class="ml-md-3 mr-md-auto">{{ __('Welcome, Guest') }}</span>
        @endguest

        @auth('api')
        <span class="ml-md-3 mr-md-auto">{{ __('Welcome') }}, {{ Auth::user()->name }}</span>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-pulse2"></i>
                    <span>Activity</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item">Some action</a>
                    <a href="#" class="dropdown-item">Another action</a>
                    <a href="#" class="dropdown-item">One more action</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">Separate action</a>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="navbar-nav-link" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="icon-exit"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
        @endauth

    </div>
</div>
{{-- /Main navbar --}}
