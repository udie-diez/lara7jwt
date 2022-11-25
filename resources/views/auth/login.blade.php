@extends('layouts.app')

@section('content')
    <div class="content d-flex justify-content-center align-items-center">

        {{-- Login card --}}
        <form class="login-form form-validate" action="{{ route('login') }}">
            @csrf

            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="mb-0">{{ __('Login to your account') }}</h5>
                        <span class="d-block text-muted">{{ __('Your credentials') }}</span>
                    </div>

                    <div class="error"></div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" id="email" name="email" placeholder="{{ __('Email address') }}" required>
                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('Password') }}" required>
                        <div class="form-control-feedback toggle-password hide-password">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group d-flex align-items-center">
                        <a href="{{ route('forgot') }}" class="ml-auto">{{ __('Forgot password?') }}</a>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Sign in') }} <i class="icon-circle-right2 ml-2"></i></button>
                    </div>

                    {{-- <div class="form-group text-center text-muted content-divider">
                        <span class="px-2">{{ __("Don't have an account?") }}</span>
                    </div>

                    <div class="form-group">
                        <a href="{{ route('register') }}" class="btn btn-light btn-block">{{ __('Sign up') }}</a>
                    </div>

                    <span class="form-text text-center text-muted">By continuing, you're confirming that you've read our <a href="#">Terms &amp; Conditions</a> and <a href="#">Cookie Policy</a></span> --}}
                </div>
            </div>
        </form>
        {{-- /Login card --}}

    </div>
@endsection

@section('styles')
    <style>.toggle-password{cursor: pointer;}</style>
@endsection

@section('scripts')
    <script src="{{ asset('themes/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/forms/validation/localization/messages_id.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/forms/validation/additional_methods.min.js') }}"></script>
    <script>
        const notiLoaderRedirect = function(target) {
            var cur_value = 1,
            progress;
            // Make a loader.
            var loader = new PNotify({
                title: "Please wait redirecting...",
                text: '<div class="progress" style="margin:0">\
                <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">\
                <span class="sr-only">0%</span>\
                </div>\
                </div>',
                addclass: 'bg-primary border-primary',
                icon: 'icon-spinner4 spinner',
                hide: false,
                buttons: {
                    closer: false,
                    sticker: false
                },
                before_open: function(PNotify) {
                    progress = PNotify.get().find("div.progress-bar");
                    progress.width(cur_value + "%").attr("aria-valuenow", cur_value).find("span").html(cur_value + "%");

                    // Pretend to do something.
                    var timer = setInterval(function() {
                        if (cur_value >= 100) {

                            // Remove the interval.
                            window.clearInterval(timer);
                            loader.remove();
                            window.location.href = target;
                            return;
                        }
                        cur_value += 1;
                        progress.width(cur_value + "%").attr("aria-valuenow", cur_value).find("span").html(cur_value + "%");
                    }, 65);
                }
            });
        }
        const login = async function () {
            event.preventDefault();
            $('.error').html('');
            const spinner = `Loading... <i class="icon-spinner2 spinner ml-2"></i>`;
            const btnLoginEl = $('.login-form button').html();
            $('.login-form button').prop('disabled', true).html(spinner);

            try {
                const form = $(event.target);
                const json = convertFormToJSON(form);
                const resp = await axios.post('/api/auth/login', json);
                $('.login-form button').prop('disabled', false).html(btnLoginEl);
                window.localStorage.setItem("acct", resp.data.data.access_token);
                window.localStorage.setItem("reft", resp.data.data.refresh_token);
                notiLoaderRedirect('/');
            } catch (err) {
                $('.login-form button').prop('disabled', false).html(btnLoginEl);
                // get response with a status code not in range 2xx
                if (err.response) {
                    console.log(err.response.data);
                    console.log(err.response.status);
                    console.log(err.response.headers);
                    const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">${err.response.data.message}</span>`;
                    $('.error').html(message);
                }
                // no response
                else if (err.request) {
                    console.log(err.request);
                    const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">${err.request}</span>`;
                    $('.error').html(message);
                }
                // Something wrong in setting up the request
                else {
                    console.log('Error', err.message);
                    const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">${err.message}</span>`;
                    $('.error').html(message);
                }
                console.log(err.config);
            }
        }

        $(function () {
            // disable submit button until form is valid
            $('.login-form input').on('blur keyup', function () {
                if ($('.login-form').valid()) {
                    $('.login-form button').prop('disabled', false);
                } else {
                    $('.login-form button').prop('disabled', true);
                }
            });
            // toggle password input
            $('.toggle-password').on('click', function () {
                if (!$(this).hasClass('show-password')) {
                    $(this).removeClass('hide-password').addClass('show-password');
                    $(this).siblings('input').attr('type', 'text');
                    $(this).find('i').removeClass('icon-lock2').addClass('icon-unlocked2');
                } else {
                    $(this).removeClass('show-password').addClass('hide-password');
                    $(this).siblings('input').attr('type', 'password');
                    $(this).find('i').removeClass('icon-unlocked2').addClass('icon-lock2');
                }
            });
            // // add custom validation methods
            // $.validator.addMethod("strongpassword", function (value, element) {
            //     return this.optional(element) || /^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])(?=.*[~`!@#$%^&*()--+={}\[\]|\\:;"'<>,.?/_₹]).{8,}$/.test(value);
            // }, "Passwords must be at least 8 characters long, 1 lowercase letter, 1 capital letter, 1 number, 1 special characters");
            // validation
            $('.login-form').validate({
                ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
                errorClass: 'validation-invalid-label',
                successClass: 'validation-valid-label',
                validClass: 'validation-valid-label',
                highlight: function(element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                unhighlight: function(element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                // Different components require proper error label placement
                errorPlacement: function(error, element) {
                    // Input with icons and Select2
                    if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                        error.appendTo( element.parent() );
                    }
                    // Other elements
                    else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    email: {
                        email: true
                    },
                    password: {
                        minlength: 6
                    }
                },
                submitHandler: async function () {
                    login();
                }
            });
        });
    </script>
@endsection
