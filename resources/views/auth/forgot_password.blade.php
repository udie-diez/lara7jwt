@extends('layouts.app')

@section('content')
    <div class="content d-flex justify-content-center align-items-center">

        {{-- Login card --}}
        <form class="login-form form-validate" action="{{ route('forgot') }}">
            @csrf

            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="mb-0">{{ __('Password recovery') }}</h5>
                        <span class="d-block text-muted">{{ __("We'll send you instructions in email") }}</span>
                    </div>

                    <div class="error"></div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" id="email" name="email" placeholder="{{ __('Email address') }}" required>
                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
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
            $('.error').html('');
            const btnResetEl = $('.login-form button').html();
            $('.login-form button').prop('disabled', true).html(spinner);

            try {
                const form = $(event.target);
                const json = convertFormToJSON(form);
                const resp = await axios.post('/api/auth/forgot-password', json);
                $('.login-form button').prop('disabled', false).html(btnResetEl);
            } catch (err) {
                $('.login-form button').prop('disabled', false).html(btnResetEl);
                // get response with a status code not in range 2xx
                if (err.response) {
                    console.log(err.response.data);
                    console.log(err.response.status);
                    console.log(err.response.headers);
                    if (typeof err.response.data.message === 'string') {
                        const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">${err.response.data.message}</span>`;
                        $('.error').html(message);
                        return;
                    }
                    if (typeof err.response.data.message === 'object') {
                        if ($('#email-error').length === 0) {
                            if (err.response.data.message.email) {
                                const message = `<label id="email-error" class="validation-invalid-label" for="email">${err.response.data.message.email[0]}</label>`;
                                const parent = $('#email').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.email) {
                                $('#email-error').show().html(err.response.data.message.email[0]);
                            }
                        }
                    }
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
