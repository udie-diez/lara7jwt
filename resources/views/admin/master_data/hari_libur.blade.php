@extends('layouts.home')

@section('maincontent')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">{{$tag['judul']}}</h5>
        </div>
        <div class="card-body">
            <div class="error"></div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group row">
                        <label for="daterange" class="col-form-label col-lg-2">{{ __('Cari Tanggal') }}</label>
                        <div class="col-lg-10">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="icon-calendar22"></i>
                                    </span>
                                </span>
                                <input type="text" id="daterange" name="daterange" class="form-control datepicker" placeholder="{{ __('Cari Tanggal') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label for="" class="col-form-label col-lg-4"></label>
                        <div class="col-lg-8">
                            <button type="button" class="btn btn-primary action-find">
                                <i class="icon-search4 mr-2"></i> {{ __('Cari') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <table id="tbl-hari-libur" class="table datatable-basic table-hover datatable-show-all">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Tanggal') }}</th>
                        <th>{{ __('Keterangan') }}</th>
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
    @include('admin.master_data.modals.forms.hari_libur')
    @include('admin.master_data.modals.forms.hari_libur_bulk')

    <script src="{{ asset('themes/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script src="{{ asset('themes/js/main/axios.min.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/forms/validation/localization/messages_id.js') }}"></script>
    <script src="{{ asset('themes/js/plugins/forms/validation/additional_methods.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/ui/moment/moment.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script>
        const getData = function(params) {
            const btnFindText = $('.action-find').html();
            $('.action-find').prop('disabled', true).html(spinner);
            return axios.get("{{ route('hariLibur.list') }}", {
                params: params
            })
            .then(function(resp) {
                drawTable(resp.data);
                $('.action-find').prop('disabled', false).html(btnFindText);
                $('.error').html('');
            })
            .catch(function(err) {
                $('.action-find').prop('disabled', false).html(btnFindText);
                const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">${err.toString()}</span>`;
                return $('.error').html(message);
            });
        }
        const drawTable = function(params) {
            let i = 1;
            table = $('#tbl-hari-libur').DataTable({
                destroy: true,
                data: params.data,
                buttons: [
                    {
                        text: '<i class="icon-file-plus" data-popup="tooltip" title="Tambah Data"></i>',
                        className: 'btn btn-default action-create',
                        action: function (e, dt, node, config) {
                            $('.modal-title').html('Tambah Hari Libur');
                            $('#modal_hari_libur_bulk form').trigger('reset');
                            $.uniform.update();
                            $('#modal_hari_libur_bulk').data({
                                'action': 'create',
                            }).modal('show');
                        }
                    },
                    {
                        text: '<i class="icon-loop3" data-popup="tooltip" title="Refresh"></i>',
                        className: 'btn btn-default action-refresh',
                        action: function(e, dt, node, config) {
                            $('.action-find').click();
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
                columns: [
                    {data: null, name: 'DT_RowIndex', orderable: false, searchable: false, render: function(data, type, row, meta) {
                        return i++;
                    }},
                    {data: 'date', name: 'date', render: function(data, type, row, meta) {
                        return moment(data).format('YYYY-MM-DD');
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
        }
        const createHariLibur = async function() {
            event.preventDefault();
            $('.error').html('');
            const btnSubmitEl = $('#form-hari-libur-bulk .action-submit').html();
            $('#form-hari-libur-bulk .action-submit').prop('disabled', true).html(spinner);

            try {
                const form = $('#form-hari-libur-bulk').serialize();
                const resp = await axios.post(`{{ route('hariLibur.create') }}?${form}`);
                if (resp.data.code === 200) {
                    $('#modal_hari_libur_bulk').modal('hide');
                    $('.action-refresh').click();
                    noti.show({
                        title: "Tambah Hari Libur",
                        text: 'Berhasil disimpan'
                    });
                }
                $('#form-hari-libur-bulk .action-submit').prop('disabled', false).html(btnSubmitEl);
            } catch (err) {
                $('#form-hari-libur-bulk .action-submit').prop('disabled', false).html(btnSubmitEl);
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
                        if ($('#date-error').length === 0) {
                            if (err.response.data.message.date) {
                                const message = `<label id="date-error" class="validation-invalid-label" for="date">${err.response.data.message.date[0]}</label>`;
                                const parent = $('#date').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.date) {
                                $('#date-error').show().html(err.response.data.message.date[0]);
                            }
                        }
                        if ($('#description-error').length === 0) {
                            if (err.response.data.message.description) {
                                const message = `<label id="description-error" class="validation-invalid-label" for="description">${err.response.data.message.description[0]}</label>`;
                                const parent = $('#description').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.description) {
                                $('#description-error').show().html(err.response.data.message.description[0]);
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
        let bulkRow = 0;
        const addRow = function() {
            const markup = function() {
                bulkRow += 1;
                return `
                    <div class="row item-hari-libur">
                        <div class="col-md-5">
                            <div class="form-group row">
                                <label for="date${bulkRow}" class="col-form-label col-lg-2">{{ __('Tanggal') }}</label>
                                <div class="col-lg-10">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="icon-calendar22"></i>
                                            </span>
                                        </span>
                                        <input type="text" id="date${bulkRow}" name="date[]" class="form-control datepicker-single" placeholder="{{ __('Tanggal') }}" required autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group row">
                                <label for="description${bulkRow}" class="col-form-label col-lg-2">{{ __('Keterangan') }}</label>
                                <div class="col-lg-10">
                                    <textarea id="description${bulkRow}" name="description[]" class="form-control" placeholder="{{ __('Keterangan') }}" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-lg-2"></label>
                                <div class="col-lg-10">
                                    <button type="button" class="btn bg-transparent text-danger border-danger action-remove-row">
                                        <i class="icon-file-minus" data-popup="tooltip" title="Hapus Data"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            const row = markup();
            $(row).appendTo('#form-hari-libur-bulk .modal-body');
            // reinit components
            $('.datepicker-single').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                applyClass: 'btn-primary',
                cancelClass: 'btn-light',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        }
        const removeRow = function(el) {
            $(el).remove();
        }
        const updateHariLibur = async function() {
            event.preventDefault();
            $('.error').html('');
            const btnSubmitEl = $('#form-hari-libur .action-submit').html();
            $('#form-hari-libur .action-submit').prop('disabled', true).html(spinner);

            try {
                const form = $(event.target);
                const json = convertFormToJSON(form);
                const modalData = $('#modal_hari_libur').data();
                json['_method'] = 'put';
                json['status'] = true;
                const url = "{{ route('hariLibur.update', 'rowid') }}"
                    .replace('rowid', modalData.rowid);
                const resp = await axios.post(url, json);
                if (resp.data.code === 200) {
                    $('#modal_hari_libur').modal('hide');
                    $('.action-refresh').click();
                    noti.show({
                        title: "Edit Hari Libur",
                        text: 'Berhasil disimpan'
                    });
                }
                $('#form-hari-libur .action-submit').prop('disabled', false).html(btnSubmitEl);
            } catch (err) {
                $('#form-hari-libur .action-submit').prop('disabled', false).html(btnSubmitEl);
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
                        if ($('#date-error').length === 0) {
                            if (err.response.data.message.date) {
                                const message = `<label id="date-error" class="validation-invalid-label" for="date">${err.response.data.message.date[0]}</label>`;
                                const parent = $('#date').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.date) {
                                $('#date-error').show().html(err.response.data.message.date[0]);
                            }
                        }
                        if ($('#description-error').length === 0) {
                            if (err.response.data.message.description) {
                                const message = `<label id="description-error" class="validation-invalid-label" for="description">${err.response.data.message.description[0]}</label>`;
                                const parent = $('#description').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.description) {
                                $('#description-error').show().html(err.response.data.message.description[0]);
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
            // daterange picker
            $('.datepicker').daterangepicker({
                startDate: moment().startOf('year'),
                endDate: moment().endOf('year'),
                showDropdowns: true,
                applyClass: 'btn-primary',
                cancelClass: 'btn-light',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('.datepicker-single').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                applyClass: 'btn-primary',
                cancelClass: 'btn-light',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('.datepicker-year').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                applyClass: 'btn-primary',
                cancelClass: 'btn-light',
                locale: {
                    format: 'YYYY'
                }
            });
            // stackable modal
            $(document).on('show.bs.modal', '.modal', function() {
                const zIndex = 1040 + 10 * $('.modal:visible').length;
                $(this).css('z-index', zIndex);
                setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
            });
            // Defaults sweet confirm
            const swalInit = swal.mixin({
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-light'
            });
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 150,
                    targets: [ 5 ]
                }],
                dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                },
                buttons: [],
            });
            $('#tbl-hari-libur').DataTable();
            // init data
            const range = $('#daterange').val().split(' - ');
            getData({
                startDate: range[0],
                endDate: range[1],
            });
            $(document).on('click', '.action-find', function() {
                const range = $('#daterange').val().split(' - ');
                getData({
                    startDate: range[0],
                    endDate: range[1],
                });
            });
            $(document).on('click', '.action-edit', function() {
                const me = this;
                const rowid = $(me).data('rowid');
                const data = table.row($(me).parents('tr')).data();

                $('.modal-title').html('Edit Hari Libur');
                $('#date').val(`${moment(data.date).format('YYYY-MM-DD')}`);
                $('#description').val(`${data.description}`);
                $.uniform.update();

                $('#modal_hari_libur').data({
                    'action': 'edit',
                    'rowid': rowid,
                    'data': data
                }).modal('show');
            });
            $(document).on('click', '.action-delete', function() {
                const me = this;
                const rowid = $(me).data('rowid');
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
                        const url = "{{ route('hariLibur.destroy', 'rowid') }}"
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
            // disable submit button until form is valid
            $(document).on('change click blur keyup', '#form-hari-libur-bulk input, #form-hari-libur-bulk select, #form-hari-libur-bulk textarea', function () {
                if ($('#form-hari-libur-bulk').valid()) {
                    $('#form-hari-libur-bulk .action-submit').prop('disabled', false);
                } else {
                    $('#form-hari-libur-bulk .action-submit').prop('disabled', true);
                }
            });
            // validation
            $('#form-hari-libur-bulk').validate({
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
                submitHandler: async function () {
                    createHariLibur();
                }
            });
            $(document).on('click', '#form-hari-libur-bulk .action-add-row', function() {
                addRow();
            });
            $(document).on('click', '#form-hari-libur-bulk .action-remove-row', function() {
                removeRow($(this).closest('.item-hari-libur'));
            });
            $(document).on('change click blur keyup', '#form-hari-libur input, #form-hari-libur select, #form-hari-libur textarea', function () {
                if ($('#form-hari-libur').valid()) {
                    $('#form-hari-libur .action-submit').prop('disabled', false);
                } else {
                    $('#form-hari-libur .action-submit').prop('disabled', true);
                }
            });
            // validation
            $('#form-hari-libur').validate({
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
                submitHandler: async function () {
                    updateHariLibur();
                }
            });
        });
    </script>
@endsection
