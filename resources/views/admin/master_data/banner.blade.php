@extends('layouts.app')

@section('header_title')
    <span class="font-weight-semibold">{{ __('Master Data') }}</span> - {{ __('Banner') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> {{ __('Home') }}</a>
        <a href="#" class="breadcrumb-item">{{ __('Master Data') }}</a>
        <span class="breadcrumb-item active">{{ __('Banner') }}</span>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="card">
            <table id="tbl-banner" class="table table-bordered table-hover datatable-show-all">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Judul') }}</th>
                        <th>{{ __('Gambar') }}</th>
                        <th>{{ __('Deskripsi') }}</th>
                        <th>{{ __('Link') }}</th>
                        <th>{{ __('Status') }}</th>
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

    @include('admin.master_data.modals.forms.banner')
@endsection

@section('scripts')
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
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/trumbowyg.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/plugins/base64/trumbowyg.base64.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/plugins/cleanpaste/trumbowyg.cleanpaste.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/plugins/colors/trumbowyg.colors.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/plugins/insertaudio/trumbowyg.insertaudio.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/plugins/noembed/trumbowyg.noembed.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/plugins/template/trumbowyg.template.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/plugins/upload/trumbowyg.upload.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/editors/trumbowyg/plugins/pasteimage/trumbowyg.pasteimage.min.js') }}"></script>
    <script>
        const submitBanner = async function() {
            event.preventDefault();
            $('.error').html('');
            const btnSubmitEl = $('#form-banner .action-submit').html();
            $('#form-banner .action-submit').prop('disabled', true).html(spinner);

            try {
                const formData = new FormData();
                formData.append('title', $('#title').val());
                formData.append('image', $('#image')[0].files[0]);
                formData.append('description', $('.trumbowyg_default').trumbowyg('html'));
                formData.append('link', $('#link').val());
                formData.append('status', $('#status').val());

                const modalData = $('#modal_banner').data();
                let resp = '';
                if (modalData.action === 'create') {
                    const url = "{{ route('banner.create') }}";
                    resp = await axios.post(url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                } else {
                    formData.append('_method', 'put');
                    const url = "{{ route('banner.update', 'rowid') }}"
                        .replace('rowid', modalData.rowid);
                    resp = await axios.post(url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                }

                if (resp.data.code === 200) {
                    $('#modal_banner').modal('hide');
                    $('.action-refresh').click();
                    noti.show({
                        title: modalData.action === 'create' ? "Tambah Banner" : "Edit Banner",
                        text: 'Berhasil disimpan'
                    });
                }
                $('#form-banner .action-submit').prop('disabled', false).html(btnSubmitEl);
            } catch (err) {
                $('#form-banner .action-submit').prop('disabled', false).html(btnSubmitEl);
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
                        if ($('#title-error').length === 0) {
                            if (err.response.data.message.title) {
                                const message = `<label id="title-error" class="validation-invalid-label" for="title">${err.response.data.message.title[0]}</label>`;
                                const parent = $('#title').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.title) {
                                $('#title-error').show().html(err.response.data.message.title[0]);
                            }
                        }
                        if ($('#status-error').length === 0) {
                            if (err.response.data.message.status) {
                                const message = `<label id="status-error" class="validation-invalid-label" for="status">${err.response.data.message.status[0]}</label>`;
                                const parent = $('#status').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.status) {
                                $('#status-error').show().html(err.response.data.message.status[0]);
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
            // text editor
            $.trumbowyg.svgPath = "{{ asset('themes/js/plugins/editors/trumbowyg/ui/icons.svg') }}";
            $('.trumbowyg_default').trumbowyg();
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 150,
                    targets: [ 8 ]
                }],
                dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
            });
            var table = $('#tbl-banner').DataTable({
                buttons: [
                    {
                        text: '<i class="icon-file-plus" data-popup="tooltip" title="Tambah Data"></i>',
                        className: 'btn btn-light action-create',
                        action: function (e, dt, node, config) {
                            $('.modal-title').html('Tambah Banner');
                            $('#modal_banner form').trigger('reset');
                            $('.trumbowyg_default').trumbowyg('html', '');
                            $.uniform.update();
                            $('#modal_banner').data({
                                'action': 'create',
                            }).modal('show');
                        }
                    },
                    {
                        text: '<i class="icon-loop3" data-popup="tooltip" title="Refresh"></i>',
                        className: 'btn btn-light action-refresh',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload(null, false);
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="icon-file-excel" data-popup="tooltip" title="Export to Excel"></i>',
                        className: 'btn btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="icon-file-pdf" data-popup="tooltip" title="Export to PDF"></i>',
                        className: 'btn btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('banner.list') }}",
                    data: function(params) {
                        params.keyword = $('#tbl-anggota_filter input[type="search"]').val();
                    },
                },
                search: {
                    return: true,
                },
                searchDelay: 800,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'title', name: 'title'},
                    {data: 'image', name: 'image', render: function(data, type, row, meta) {
                        return !data ? null : `<a target="_blank" href="${data}"><img src="${data}" class="rounded-circle" width="32" height="32"></a>`;
                    }},
                    {data: 'description', name: 'description'},
                    {data: 'link', name: 'link'},
                    {data: 'status', name: 'status', render: function(data, type, row, meta) {
                        let color = data == true || data == 1 ? 'bg-blue' : 'bg-danger';
                        let text = data == true || data == 1 ? 'Aktif' : 'Tidak Aktif';
                        return `<div class="text-center">
                            <span class="badge ${color}">${text}</span>
                        </div>`;
                    }},
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
            $('#form-banner input, #form-banner select').on('change click blur keyup', function () {
                if ($('#form-banner').valid()) {
                    $('#form-banner .action-submit').prop('disabled', false);
                } else {
                    $('#form-banner .action-submit').prop('disabled', true);
                }
            });
            // validation
            $('#form-banner').validate({
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
                    submitBanner();
                }
            });
            $(document).on('click', '.action-edit', function() {
                const me = this;
                const rowid = $(me).data('rowid');
                const data = table.row($(me).parents('tr')).data();

                $('.modal-title').html('Edit Banner');
                $('#title').val(`${data.title}`);
                const description = decodeHtml(data.description);
                $('#description').val(`${description}`);
                $('.trumbowyg_default').trumbowyg('html', description);
                $('#status').val(`${data.status}`).trigger('change');
                $.uniform.update();

                $('#modal_banner').data({
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
                        const url = "{{ route('banner.destroy', 'rowid') }}"
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
