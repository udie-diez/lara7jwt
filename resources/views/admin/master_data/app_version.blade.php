@extends('layouts.home')

@section('maincontent')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">{{$tag['judul']}}</h5>
        </div>
        <div class="card-body">
            <table id="tbl-app-version" class="table datatable-basic table-hover datatable-show-all">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('OS') }}</th>
                        <th>{{ __('Versi') }}</th>
                        <th>{{ __('Build') }}</th>
                        <th>{{ __('Link') }}</th>
                        <th>{{ __('Deskripsi') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Updated at') }}</th>
                        <th class="text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('admin.master_data.modals.forms.app_version')

    <script src="{{ asset('themes/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script src="{{ asset('themes/js/main/axios.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/ui/moment/moment.min.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/forms/validation/localization/messages_id.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/forms/validation/additional_methods.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script>
        const submitApp = async function() {
            event.preventDefault();
            $('.error').html('');
            const btnSubmitEl = $('#form-app-version .action-submit').html();
            $('#form-app-version .action-submit').prop('disabled', true).html(spinner);

            try {
                const formData = new FormData();
                formData.append('os', $('#os').val());
                formData.append('file', $('#file')[0].files[0]);
                formData.append('version', $('#version').val());
                formData.append('description', $('#description').val());
                formData.append('build', $('#build').val());

                const modalData = $('#modal_app_version').data();
                let resp = '';
                if (modalData.action === 'create') {
                    const url = "{{ route('appVersion.create') }}";
                    resp = await axios.post(url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                } else {
                    formData.append('_method', 'put');
                    const url = "{{ route('appVersion.update', 'rowid') }}"
                        .replace('rowid', modalData.rowid);
                    resp = await axios.post(url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                }

                if (resp.data.code === 200) {
                    $('#modal_app_version').modal('hide');
                    $('.action-refresh').click();
                    noti.show({
                        title: modalData.action === 'create' ? "Tambah App Version" : "Edit App Version",
                        text: 'Berhasil disimpan'
                    });
                }
                $('#form-app-version .action-submit').prop('disabled', false).html(btnSubmitEl);
            } catch (err) {
                $('#form-app-version .action-submit').prop('disabled', false).html(btnSubmitEl);
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
                        if ($('#os-error').length === 0) {
                            if (err.response.data.message.os) {
                                const message = `<label id="os-error" class="validation-invalid-label" for="os">${err.response.data.message.os[0]}</label>`;
                                const parent = $('#os').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.os) {
                                $('#os-error').show().html(err.response.data.message.os[0]);
                            }
                        }
                        if ($('#version-error').length === 0) {
                            if (err.response.data.message.version) {
                                const message = `<label id="version-error" class="validation-invalid-label" for="version">${err.response.data.message.version[0]}</label>`;
                                const parent = $('#version').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.version) {
                                $('#version-error').show().html(err.response.data.message.version[0]);
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
        $(function() {
            // styled form input
            $('.form-input-styled').uniform({
                fileButtonClass: 'action btn bg-blue'
            });
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 150,
                    targets: [ 6 ]
                }],
                dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
            });
            var table = $('#tbl-app-version').DataTable({
                buttons: [
                    {
                        text: '<i class="icon-file-plus" data-popup="tooltip" title="Tambah Data"></i>',
                        className: 'btn btn-default action-create',
                        action: function (e, dt, node, config) {
                            $('.modal-title').html('Tambah App Version');
                            $('#modal_app_version form').trigger('reset');
                            $.uniform.update();
                            $('#modal_app_version').data({
                                'action': 'create',
                            }).modal('show');
                        }
                    },
                    {
                        text: '<i class="icon-loop3" data-popup="tooltip" title="Refresh"></i>',
                        className: 'btn btn-default action-refresh',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload(null, false);
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="icon-file-excel" data-popup="tooltip" title="Export to Excel"></i>',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="icon-file-pdf" data-popup="tooltip" title="Export to PDF"></i>',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('appVersion.list') }}",
                    data: function(params) {
                        params.os = $('#tbl-anggota_filter input[type="search"]').val();
                    },
                },
                search: {
                    search: 'android',
                    return: true,
                },
                searchDelay: 800,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'os', name: 'os'},
                    {data: 'version', name: 'version'},
                    {data: 'build', name: 'build'},
                    {data: 'link', name: 'link', render: function(data, type, row, meta) {
                        return !data ? null : `<a target="_blank" href="${data}">${row.os} ${row.version}</a>`;
                    }},
                    {data: 'description', name: 'description'},
                    {data: 'createdAt', name: 'createdAt', render: function(data, type, row, meta) {
                        return moment(data).format('DD MMM YYYY HH:mm:ss');
                    }},
                    {data: 'updatedAt', name: 'updatedAt', render: function(data, type, row, meta) {
                        return moment(data).format('DD MMM YYYY HH:mm:ss');
                    }},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
            // disable submit button until form is valid
            $('#form-app-version input, #form-app-version select').on('change click blur keyup', function () {
                if ($('#form-app-version').valid()) {
                    $('#form-app-version .action-submit').prop('disabled', false);
                } else {
                    $('#form-app-version .action-submit').prop('disabled', true);
                }
            });
            // validation
            $('#form-app-version').validate({
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
                    // styled input file
                    else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                        error.appendTo( element.parent().parent() );
                    }
                    // Other elements
                    else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    link: {
                        url: true
                    }
                },
                submitHandler: async function () {
                    submitApp();
                }
            });
            $(document).on('click', '.action-edit', function() {
                const me = this;
                const rowid = $(me).data('rowid');
                const data = table.row($(me).parents('tr')).data();

                $('.modal-title').html('Edit App Version');
                $('#os').val(`${data.os}`);
                $('#version').val(`${data.version}`);
                $('#description').val(`${data.description}`);
                $('#build').val(`${data.build}`);
                $.uniform.update();

                $('#modal_app_version').data({
                    'action': 'edit',
                    'rowid': rowid,
                    'data': data
                }).modal('show');
            });
            $(document).on('click', '.action-delete', function() {
                const me = this;
                const rowid = $(me).data('rowid');
                // Defaults sweet confirm
                var swalInit = swal.mixin({
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-light'
                });
                swalInit.fire({
                    title: 'Are you sure to delete?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        const url = "{{ route('appVersion.destroy', 'rowid') }}"
                            .replace('rowid', rowid);
                        axios.delete(url)
                        .then(() => {
                            table.row($(me).parents('tr')).remove().draw();
                            swalInit.fire(
                                'Deleted!',
                                'The action was succeed',
                                'success'
                            );
                        });
                    }
                    else if (result.dismiss === swal.DismissReason.cancel) {
                        swalInit.fire(
                            'Cancelled',
                            'The action was cancelled',
                            'error'
                        );
                    }
                });
            });
        });
    </script>
@endsection
