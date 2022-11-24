@extends('layouts.app')

@section('content')
    <div class="content d-flex justify-content-center align-items-center">

        {{-- Login card --}}
        <form class="login-form form-validate" action="{{ route('reset', $token) }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="mb-0">{{ __('Password recovery') }}</h5>
                        <span class="d-block text-muted">{{ __("One more step to gain your access") }}</span>
                    </div>

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

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{ __('Retype password') }}" required>
                        <div class="form-control-feedback toggle-password hide-password">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-blue btn-block"><i class="icon-spinner11 mr-2"></i> {{ __('Reset password') }}</button>
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
        const recovery = async function () {
            event.preventDefault();
            const spinner = `Loading... <i class="icon-spinner2 spinner ml-2"></i>`;
            const btnResetEl = $('.login-form button').html();
            $('.login-form button').prop('disabled', true).html(spinner);

            try {
                const form = $(event.target);
                const json = convertFormToJSON(form);
                const resp = await axios.post('/api/auth/reset-password', json);
                console.log(resp);
                $('.login-form button').prop('disabled', false).html(btnResetEl);
                setTimeout(window.location.href = '/auth/login', 3000);
            } catch (err) {
                $('.login-form button').prop('disabled', false).html(btnResetEl);
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
                    }
                },
                submitHandler: async function () {
                    recovery();
                }
            });
        });
    </script>
@endsection
