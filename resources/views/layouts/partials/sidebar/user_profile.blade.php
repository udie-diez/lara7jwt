{{-- User menu --}}
<div class="sidebar-user">
    <div class="card-body bg-blue text-center card-img-top" style="background-image: url({{ asset('themes/images/backgrounds/panel_bg.png') }}); background-size: contain;">
        <div class="card-img-actions d-inline-block mb-3">
            <img class="img-fluid rounded-circle" src="{{ asset('themes/images/placeholders/placeholder.jpg') }}" width="170" height="170" alt="">
            <div class="card-img-actions-overlay card-img rounded-circle">
                <a href="#" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
                    <i class="icon-plus3"></i>
                </a>
                <a href="#" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
                    <i class="icon-link"></i>
                </a>
            </div>
        </div>

        <h6 class="font-weight-semibold mb-0">{{ session('users') ? session('users')['name'] : 'Guest' }}</h6>
        <span class="d-block opacity-75">Jakarta</span>
    </div>
</div>
{{-- /User menu --}}
