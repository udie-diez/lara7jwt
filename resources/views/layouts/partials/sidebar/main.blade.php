{{-- Main sidebar --}}
<div class="sidebar sidebar-dark sidebar-main sidebar-fixed sidebar-expand-md">

    {{-- Sidebar mobile toggler --}}
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    {{-- /Sidebar mobile toggler --}}

    {{-- Sidebar content --}}
    <div class="sidebar-content">

        {{-- User menu --}}
        @include('layouts.partials.sidebar.user_profile')
        {{-- /User menu --}}

        {{-- Main navigation --}}
        @include('layouts.partials.sidebar.main_nav')
        {{-- /Main navigation --}}

    </div>
    {{-- /Sidebar content --}}

</div>
{{-- /Main sidebar --}}
