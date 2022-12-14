@extends('layouts.app')

@section('header_title')
    <span class="font-weight-semibold">{{ __('Master Data') }}</span> - {{ __('Hari Libur') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> {{ __('Home') }}</a>
        <a href="#" class="breadcrumb-item">{{ __('Master Data') }}</a>
        <span class="breadcrumb-item active">{{ __('Hari Libur') }}</span>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="error"></div>

                <div class="form-group row">
                    <label for="date" class="col-form-label col-lg-2">Cari Tanggal</label>
                    <div class="col-lg-10">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="icon-calendar22"></i>
                                </span>
                            </span>
                            <input type="text" id="date" name="date" class="form-control" placeholder="{{ __('Cari Tanggal') }}">
                        </div>
                    </div>
                </div>
            </div>

            <table id="tbl-hari-libur" class="table table-bordered table-hover datatable-show-all">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('admin.master_data.modals.forms.hari_libur')
    @include('admin.master_data.modals.forms.jenis_cuti')
@endsection

@section('scripts')
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
        const getHariLibur = async function(start, end) {
            try {
                const params = new URLSearchParams({
                    startDate: start,
                    endDate: end
                });
                resp = await axios.get(`{{ route('hariLibur.list') }}?${params.toString()}`);
                drawTable({data: resp.data.data});
            } catch (err) {
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
        const drawTable = async function(data) {
            window.table = $('#tbl-hari-libur').DataTable({
                destroy: true,
                // buttons: [
                //     {
                //         text: '<i class="icon-file-plus" data-popup="tooltip" title="Tambah Data"></i>',
                //         className: 'btn btn-light action-create',
                //         action: function (e, dt, node, config) {
                //             $('.modal-title').html('Tambah Hari Libur');
                //             $('#modal_hari_libur form').trigger('reset');
                //             $.uniform.update();
                //             $('#modal_hari_libur').data({
                //                 'action': 'create',
                //             }).modal('show');
                //         }
                //     },
                //     {
                //         text: '<i class="icon-loop3" data-popup="tooltip" title="Refresh"></i>',
                //         className: 'btn btn-light action-refresh',
                //         action: function(e, dt, node, config) {
                //             dt.ajax.reload(null, false);
                //         }
                //     },
                //     {
                //         extend: 'excelHtml5',
                //         text: '<i class="icon-file-excel" data-popup="tooltip" title="Export to Excel"></i>',
                //         className: 'btn btn-light',
                //         exportOptions: {
                //             columns: ':visible'
                //         }
                //     },
                //     {
                //         extend: 'pdfHtml5',
                //         text: '<i class="icon-file-pdf" data-popup="tooltip" title="Export to PDF"></i>',
                //         className: 'btn btn-light',
                //         exportOptions: {
                //             columns: ':visible'
                //         }
                //     },
                // ],
                // lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                data: data,
                columns: [
                    // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'date', name: 'date', render: function(data, type, row) {
                        return moment(data).format('YYYY-MM-DD');
                    }},
                    {data: 'description', name: 'description'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }
        // const submitHariLibur = async function() {
        //     event.preventDefault();
        //     $('.error').html('');
        //     const btnSubmitEl = $('#form-hari-libur .action-submit').html();
        //     $('#form-hari-libur .action-submit').prop('disabled', true).html(spinner);

        //     try {
        //         const form = $(event.target);
        //         const json = convertFormToJSON(form);
        //         const modalData = $('#modal_hari_libur').data();
        //         let resp = '';
        //         if (modalData.action === 'create') {
        //             const url = "{{ route('hariLibur.create') }}";
        //             resp = await axios.post(url, json);
        //         } else {
        //             json['_method'] = 'put';
        //             const url = "{{ route('hariLibur.update', 'rowid') }}"
        //                 .replace('rowid', modalData.rowid);
        //             resp = await axios.post(url, json);
        //         }

        //         if (resp.data.code === 200) {
        //             $('#modal_hari_libur').modal('hide');
        //             $('.action-refresh').click();
        //             noti.show({
        //                 title: modalData.action === 'create' ? "Tambah Hari Libur" : "Edit Hari Libur",
        //                 text: 'Berhasil disimpan'
        //             });
        //         }
        //         $('#form-hari-libur .action-submit').prop('disabled', false).html(btnSubmitEl);
        //     } catch (err) {
        //         $('#form-hari-libur .action-submit').prop('disabled', false).html(btnSubmitEl);
        //         // get response with a status code not in range 2xx
        //         if (err.response) {
        //             console.log(err.response.data);
        //             console.log(err.response.status);
        //             console.log(err.response.headers);
        //             if (typeof err.response.data.message === 'string') {
        //                 const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">${err.response.data.message}</span>`;
        //                 $('.error').html(message);
        //                 return;
        //             }
        //             if (typeof err.response.data.message === 'object') {
        //                 if ($('#date-error').length === 0) {
        //                     if (err.response.data.message.date) {
        //                         const message = `<label id="date-error" class="validation-invalid-label" for="date">${err.response.data.message.date[0]}</label>`;
        //                         const parent = $('#date').parent();
        //                         parent.append(message);
        //                     }
        //                 } else {
        //                     if (err.response.data.message.date) {
        //                         $('#date-error').show().html(err.response.data.message.date[0]);
        //                     }
        //                 }
        //                 if ($('#description-error').length === 0) {
        //                     if (err.response.data.message.description) {
        //                         const message = `<label id="description-error" class="validation-invalid-label" for="description">${err.response.data.message.description[0]}</label>`;
        //                         const parent = $('#description').parent();
        //                         parent.append(message);
        //                     }
        //                 } else {
        //                     if (err.response.data.message.description) {
        //                         $('#description-error').show().html(err.response.data.message.description[0]);
        //                     }
        //                 }
        //             }
        //         }
        //         // no response
        //         else if (err.request) {
        //             console.log(err.request);
        //             const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">${err.request}</span>`;
        //             $('.error').html(message);
        //         }
        //         // Something wrong in setting up the request
        //         else {
        //             console.log('Error', err.message);
        //             const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">${err.message}</span>`;
        //             $('.error').html(message);
        //         }
        //         console.log(err.config);
        //     }
        // }
        $(function() {
            // styled form input
            $('.form-input-styled').uniform({
                fileButtonClass: 'action btn bg-blue'
            });
            // daterange picker
            $('#date').daterangepicker({
                startDate: moment().startOf('year'),
                endDate: moment().endOf('year'),
                showDropdowns: true,
                applyClass: 'btn-primary',
                cancelClass: 'btn-light',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }, function(start, end) {
                getHariLibur(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });
            getHariLibur(moment().startOf('year').format('YYYY-MM-DD'), moment().endOf('year').format('YYYY-MM-DD'));
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 150,
                    targets: [ 3 ]
                }],
                dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
            });
            // disable submit button until form is valid
            $('#form-hari-libur input, #form-hari-libur select').on('change click blur keyup', function () {
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
                    submitHariLibur();
                }
            });
            $(document).on('click', '.action-edit', function() {
                const me = this;
                const rowid = $(me).data('rowid');
                const data = table.row($(me).parents('tr')).data();

                $('.modal-title').html('Edit Hari Libur');
                $('#date').val(`${data.date}`);
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
            $(document).on('show.bs.modal', '.modal', function() {
                const zIndex = 1040 + 10 * $('.modal:visible').length;
                $(this).css('z-index', zIndex);
                setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
            });
        });
    </script>
@endsection
