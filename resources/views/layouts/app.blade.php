<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ config('app.name', 'Laravel') }}</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Styles --}}
    @include('layouts.commom.style')
    {{-- Scripts --}}
    @include('layouts.commom.script')
</head>
<body class="navbar-top">
    @include('layouts.partials.navbar.main')

    {{-- Page content --}}
    <div class="page-content">

        @if(Session::has('users'))
            @include('layouts.partials.sidebar.main')

            {{-- Main content --}}
            <div class="content-wrapper">

                {{-- Page header --}}
                @include('layouts.partials.page_header')
                {{-- /Page header --}}

                {{-- Content area --}}
                @yield('content')
                {{-- Content area --}}

                @include('layouts.partials.footer.main')

            </div>
            {{-- /Main content --}}

        @else

            {{-- Main content --}}
            <div class="content-wrapper">

                {{-- Content area --}}
                @yield('content')
                {{-- Content area --}}

                @include('layouts.partials.footer.main')

            </div>
            {{-- /Main content --}}

        @endif

    </div>
    {{-- /Page content --}}

</body>
</html>
