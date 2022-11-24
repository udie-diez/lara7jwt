@extends('layouts.app')

@section('content')
    <div class="content d-flex justify-content-center align-items-center">

        {{-- Registration card --}}
        <form class="login-form form-validate" action="{{ route('register') }}">
            @csrf

            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="mb-0">{{ __('Create account') }}</h5>
                        <span class="d-block text-muted">{{ __('All fields are required') }}</span>
                    </div>

                    <div class="form-group text-center text-muted content-divider">
                        <span class="px-2">{{ __('Your credentials') }}</span>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" id="email" name="email" placeholder="{{ __('Email address') }}" required>
                        <div class="form-control-feedback">
                            <i class="icon-envelop5 text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('Password') }}" required>
                        <div class="form-control-feedback toggle-password hide-password">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{ __('Retype password') }}" required>
                        <div class="form-control-feedback toggle-password hide-password">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group text-center text-muted content-divider">
                        <span class="px-2">{{ __('Your contacts') }}</span>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Your name') }}" required>
                        <div class="form-control-feedback">
                            <i class="icon-mention text-muted"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-teal-400 btn-block">{{ __('Register') }} <i class="icon-circle-right2 ml-2"></i></button>
                </div>
            </div>
        </form>
        {{-- /Registration card --}}

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
        const register = async function () {
            event.preventDefault();
            const spinner = `Loading... <i class="icon-spinner2 spinner ml-2"></i>`;
            const btnRegisterEl = $('.login-form button').html();
            $('.login-form button').prop('disabled', true).html(spinner);

            try {
                const form = $(event.target);
                const json = convertFormToJSON(form);
                const resp = await axios.post('/api/auth/register', json);
                console.log(resp)
                $('.login-form button').prop('disabled', false).html(btnRegisterEl);
                setTimeout(window.location.href = '/auth/login', 3000);
            } catch (err) {
                $('.login-form button').prop('disabled', false).html(btnRegisterEl);
                // get response with a status code not in range 2xx
                if (err.response) {
                    console.log(err.response.data);
                    console.log(err.response.status);
                    console.log(err.response.headers);
                }
                // no response
                else if (err.request) {
                    console.log(err.request);
                }
                // Something wrong in setting up the request
                else {
                    console.log('Error', err.message);
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
                    },
                    password_confirmation: {
                        equalTo: '#password',
                    }
                },
                submitHandler: async function () {
                    register();
                }
            });
        });
    </script>
@endsection
