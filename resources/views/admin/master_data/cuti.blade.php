@extends('layouts.home')

@section('maincontent')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">{{$tag['judul']}}</h5>
        </div>
        <div class="card-body">
            <table id="tbl-cuti" class="table datatable-basic table-hover datatable-show-all">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('User ID') }}</th>
                        <th>{{ __('Nama') }}</th>
                        <th>{{ __('Jatah Cuti') }}</th>
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
    @include('admin.master_data.modals.forms.cuti')

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
	<script src="{{ asset('themes/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script>
        // Defaults sweet confirm
        var swalInit = swal.mixin({
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-light'
        });
        const submitCuti = async function() {
            event.preventDefault();

            swalInit.fire({
                title: 'Are you sure want to update?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                buttonsStyling: false
            }).then(function (result) {
                if (result.value) {
                    $('.error').html('');
                    const btnSubmitEl = $('#form-cuti .action-submit').html();
                    $('#form-cuti .action-submit').prop('disabled', true).html(spinner);

                    const form = $('#form-cuti')[0];
                    const json = convertFormToJSON(form);
                    const modalData = $('#modal_cuti').data();
                    let url = '';
                    if (modalData.action === 'create') {
                        url = "{{ route('cuti.create') }}";
                    } else {
                        json['_method'] = 'put';
                        url = "{{ route('cuti.update', 'rowid') }}"
                            .replace('rowid', modalData.rowid);
                    }
                    axios.post(url, json)
                    .then(function(resp) {
                        debugger
                        if (resp.data.code === 200) {
                            $('#modal_cuti').modal('hide');
                            $('.action-refresh').click();
                            noti.show({
                                title: modalData.action === 'create' ? "Tambah Cuti" : "Edit Cuti",
                                text: 'Berhasil disimpan'
                            });
                        }
                        $('#form-cuti .action-submit').prop('disabled', false).html(btnSubmitEl);
                    })
                    .catch(function(err) {
                        $('#form-cuti .action-submit').prop('disabled', false).html(btnSubmitEl);
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
                                if ($('#idUser-error').length === 0) {
                                    if (err.response.data.message.idUser) {
                                        const message = `<label id="idUser-error" class="validation-invalid-label" for="idUser">${err.response.data.message.idUser[0]}</label>`;
                                        const parent = $('#idUser').parent();
                                        parent.append(message);
                                    }
                                } else {
                                    if (err.response.data.message.idUser) {
                                        $('#idUser-error').show().html(err.response.data.message.idUser[0]);
                                    }
                                }
                                if ($('#amount-error').length === 0) {
                                    if (err.response.data.message.amount) {
                                        const message = `<label id="amount-error" class="validation-invalid-label" for="amount">${err.response.data.message.amount[0]}</label>`;
                                        const parent = $('#amount').parent();
                                        parent.append(message);
                                    }
                                } else {
                                    if (err.response.data.message.amount) {
                                        $('#amount-error').show().html(err.response.data.message.amount[0]);
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
        }
        $(function() {
            // select2
            $('#idUser').select2({
                dropdownParent: $('#modal_cuti'),
                ajax: {
                    url: "{{ route('dashboard.list') }}",
                    delay: 800,
                    data: function(params) {
                        return {
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 10) < data.recordsTotal
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                minimumInputLength: 1,
                templateResult: function(params) {
                    if (params.loading) {
                        return params.text;
                    }

                    let markup = `
                        <div class="select2-result-user clearfix">
                            <div class="select2-result-user__meta">
                                <div class="select2-result-user__name">${params.name ?? 'unknown'}</div>
                                <div class="select2-result-user__email">${params.email ?? 'unknown'}</div>
                                <div class="select2-result-user__role">${params.role ?? 'unknown'}</div>
                            </div>
                        </div>
                    `;

                    return markup;
                },
                templateSelection: function(params) {
                    return params.name || params.text;
                }
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
            var table = $('#tbl-cuti').DataTable({
                buttons: [
                    {
                        text: '<i class="icon-file-plus" data-popup="tooltip" title="Tambah Data"></i>',
                        className: 'btn btn-default action-create',
                        action: function (e, dt, node, config) {
                            $('.modal-title').html('Tambah Cuti');
                            $('#modal_cuti form').trigger('reset');
                            const data = {id: '', text: 'Nama Pengurus'};
                            const newOption = new Option(data.text, data.id, true, true);
                            $('#idUser').append(newOption).trigger('change');
                            $('#modal_cuti').data({
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
                    url: "{{ route('cuti.list') }}"
                },
                search: {
                    return: true,
                },
                searchDelay: 800,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'userId', name: 'userId'},
                    {data: 'user.name', name: 'user.name'},
                    {data: 'amount', name: 'amount'},
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
            $('#form-cuti input, #form-cuti select').on('change click blur keyup', function () {
                if ($('#form-cuti').valid()) {
                    $('#form-cuti .action-submit').prop('disabled', false);
                } else {
                    $('#form-cuti .action-submit').prop('disabled', true);
                }
            });
            // validation
            $('#form-cuti').validate({
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
                    amount: {
                        digits: true,
                    },
                    sisa: {
                        digits: true,
                    },
                },
                submitHandler: async function () {
                    submitCuti();
                }
            });
            $(document).on('click', '.action-edit', function() {
                const me = this;
                const rowid = $(me).data('rowid');
                const data = table.row($(me).parents('tr')).data();

                $('.modal-title').html('Edit Cuti');
                const obj = {id: data.userId, text: data.user.name};
                const newOption = new Option(obj.text, obj.id, true, true);
                $('#idUser').append(newOption).trigger('change');
                $('#amount').val(`${data.amount}`);
                const sisa = data.sisa ?? '';
                $('#sisa').val(`${sisa}`);

                $('#modal_cuti').data({
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
                        const url = "{{ route('cuti.destroy', 'rowid') }}"
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
