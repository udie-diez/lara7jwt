@extends('layouts.app')

@section('header_title')
    <span class="font-weight-semibold">{{ __('Home') }}</span> - {{ __('Dashboard') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> {{ __('Home') }}</a>
        <span class="breadcrumb-item active">{{ __('Dashboard') }}</span>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="card">

            <div class="card-header header-elements-inline">
                <h6 class="card-title">{{ __('Daftar Anggota') }}</h6>
                <div class="header-elements">
                    <button type="button" class="btn btn-outline-primary action-create">
                        <i class="icon-plus3 mr-2"></i> Anggota
                    </button>
                </div>
            </div>

            <div class="card-body py-0">
                <div class="row">
                    @php $anggotaAktif = 0; $anggotaTidakAktif = 0; $anggotaKeluar = 0; $count = $count ?? 0; @endphp
                    @foreach ($anggota as $user)
                        @switch($user->status)
                            @case('aktif')
                                @php $anggotaAktif = $user->total @endphp
                                @break
                            @case('tidak')
                                @php $anggotaTidakAktif = $user->total @endphp
                                @break
                            @case('keluar')
                                @php $anggotaKeluar = $user->total @endphp
                                @break
                        @endswitch
                    @endforeach
                    <div class="col-xl-3 col-md-6 anggota-aktif">
                        <div class="card card-body bg-info">
                            <div class="media">
                                <div class="media-body">
                                    <h1 class="media-title font-weight-semibold">{{ $anggotaAktif }}</h1>
                                    <span>AKTIF <i class="icon-circle-right2 ml-2"></i></span>
                                </div>

                                <div class="mt-2 ml-3">
                                    <i class="icon-user-check icon-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 anggota-tidak">
                        <div class="card card-body bg-warning">
                            <div class="media">
                                <div class="media-body">
                                    <h1 class="media-title font-weight-semibold">{{ $anggotaTidakAktif }}</h1>
                                    <span>NON AKTIF <i class="icon-circle-right2 ml-2"></i></span>
                                </div>

                                <div class="mt-2 ml-3">
                                    <i class="icon-user-block icon-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 anggota-keluar">
                        <div class="card card-body bg-danger">
                            <div class="media">
                                <div class="media-body">
                                    <h1 class="media-title font-weight-semibold">{{ $anggotaKeluar }}</h1>
                                    <span>KELUAR <i class="icon-circle-right2 ml-2"></i></span>
                                </div>

                                <div class="mt-2 ml-3">
                                    <i class="icon-user-block icon-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 anggota-jumlah">
                        <div class="card card-body bg-success">
                            <div class="media">
                                <div class="media-body">
                                    <h1 class="media-title font-weight-semibold">{{ $count }}</h1>
                                    <span>JUMLAH <i class="icon-circle-right2 ml-2"></i></span>
                                </div>

                                <div class="mt-2 ml-3">
                                    <i class="icon-users4 icon-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <table id="tbl-anggota" class="table table-bordered table-hover datatable-show-all">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>No. Anggota</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Lokasi Kerja</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('admin.master_data.modals.forms.anggota')
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
    <script>
        const getAnggotaSummary = async function() {
            const resp = await axios.get("{{ route('dashboard.counter') }}");
            const data = resp.data;
            const anggotaAktif = data.anggota.filter(a => a.status === 'aktif').map(a => a.total)[0];
            const anggotaTidakAktif = data.anggota.filter(a => a.status === 'tidak').map(a => a.total)[0];
            const anggotaKeluar = data.anggota.filter(a => a.status === 'keluar').map(a => a.total)[0];
            $(`.anggota-aktif .media-title`).html(anggotaAktif ?? 0);
            $(`.anggota-tidak .media-title`).html(anggotaTidakAktif ?? 0);
            $(`.anggota-keluar .media-title`).html(anggotaKeluar ?? 0);
            $(`.anggota-jumlah .media-title`).html(data.count ?? 0);
        }
        const submitAnggota = async function() {
            event.preventDefault();
            $('.error').html('');
            const btnSubmitEl = $('#form-anggota .action-submit').html();
            $('#form-anggota .action-submit').prop('disabled', true).html(spinner);

            try {
                const form = $(event.target);
                const json = convertFormToJSON(form);
                const modalData = $('#modal_anggota').data();
                let resp = '';
                if (modalData.action === 'create') {
                    const url = "{{ route('dashboard.create') }}";
                    resp = await axios.post(url, json);
                } else {
                    json['_method'] = 'put';
                    const url = "{{ route('dashboard.update', 'rowid') }}"
                        .replace('rowid', modalData.rowid);
                    resp = await axios.post(url, json);
                }

                if (resp.data.status === 'success') {
                    $('#modal_anggota').modal('hide');
                    $('.action-refresh').click();
                    noti.show({
                        title: modalData.action === 'create' ? "Tambah Anggota" : "Edit Anggota",
                        text: 'Berhasil disimpan'
                    });
                    getAnggotaSummary();
                }
                $('#form-anggota .action-submit').prop('disabled', false).html(btnSubmitEl);
            } catch (err) {
                $('#form-anggota .action-submit').prop('disabled', false).html(btnSubmitEl);
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
                        if ($('#no_anggota-error').length === 0) {
                            if (err.response.data.message.no_anggota) {
                                const message = `<label id="no_anggota-error" class="validation-invalid-label" for="no_anggota">${err.response.data.message.no_anggota[0]}</label>`;
                                const parent = $('#no_anggota').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.no_anggota) {
                                $('#no_anggota-error').show().html(err.response.data.message.no_anggota[0]);
                            }
                        }
                        if ($('#nama-error').length === 0) {
                            if (err.response.data.message.nama) {
                                const message = `<label id="nama-error" class="validation-invalid-label" for="nama">${err.response.data.message.nama[0]}</label>`;
                                const parent = $('#nama').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.nama) {
                                $('#nama-error').show().html(err.response.data.message.nama[0]);
                            }
                        }
                        if ($('#nik-error').length === 0) {
                            if (err.response.data.message.nik) {
                                const message = `<label id="nik-error" class="validation-invalid-label" for="nik">${err.response.data.message.nik[0]}</label>`;
                                const parent = $('#nik').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.nik) {
                                $('#nik-error').show().html(err.response.data.message.nik[0]);
                            }
                        }
                        if ($('#phone_number-error').length === 0) {
                            if (err.response.data.message.phone_number) {
                                const message = `<label id="phone_number-error" class="validation-invalid-label" for="phone_number">${err.response.data.message.phone_number[0]}</label>`;
                                const parent = $('#phone_number').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.phone_number) {
                                $('#phone_number-error').show().html(err.response.data.message.phone_number[0]);
                            }
                        }
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
                        if ($('#lokasi_kerja-error').length === 0) {
                            if (err.response.data.message.lokasi_kerja) {
                                const message = `<label id="lokasi_kerja-error" class="validation-invalid-label" for="lokasi_kerja">${err.response.data.message.lokasi_kerja[0]}</label>`;
                                const parent = $('#lokasi_kerja').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.lokasi_kerja) {
                                $('#lokasi_kerja-error').show().html(err.response.data.message.lokasi_kerja[0]);
                            }
                        }
                        if ($('#jabatan-error').length === 0) {
                            if (err.response.data.message.jabatan) {
                                const message = `<label id="jabatan-error" class="validation-invalid-label" for="jabatan">${err.response.data.message.jabatan[0]}</label>`;
                                const parent = $('#jabatan').parent();
                                parent.append(message);
                            }
                        } else {
                            if (err.response.data.message.jabatan) {
                                $('#jabatan-error').show().html(err.response.data.message.jabatan[0]);
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
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 150,
                    targets: [ 9 ]
                }],
                dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
            });
            var table = $('#tbl-anggota').DataTable({
                buttons: [
                    {
                        text: '<i class="icon-loop3"></i>',
                        className: 'btn btn-light action-refresh',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload(null, false);
                            getAnggotaSummary();
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="icon-file-excel"></i>',
                        className: 'btn btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="icon-file-pdf"></i>',
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
                    url: "{{ route('dashboard.list') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'no_anggota', name: 'no_anggota'},
                    {data: 'nama', name: 'nama'},
                    {data: 'nik', name: 'nik'},
                    {data: 'phone_number', name: 'phone_number'},
                    {data: 'email', name: 'email'},
                    {data: 'lokasi_kerja', name: 'lokasi_kerja'},
                    {data: 'jabatan', name: 'jabatan'},
                    {data: 'status', name: 'status', render: function(data, type, row, meta) {
                        switch (data) {
                            case 'aktif':
                                color = 'bg-blue';
                                break;
                            case 'tidak':
                                color = 'bg-danger';
                                break;
                            default:
                                color = 'bg-grey';
                                break;
                        }
                        return `<div class="text-center">
                            <span class="badge ${color}">${data}</span>
                        </div>`;
                    }},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
            // disable submit button until form is valid
            $('#form-anggota input, #form-anggota select').on('change click blur keyup', function () {
                if ($('#form-anggota').valid()) {
                    $('#form-anggota .action-submit').prop('disabled', false);
                } else {
                    $('#form-anggota .action-submit').prop('disabled', true);
                }
            });
            // validation
            $('#form-anggota').validate({
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
                    nik: {digits: true},
                    phone_number: {digits: true},
                    email: {email: true},
                },
                submitHandler: async function () {
                    submitAnggota();
                }
            });
            $(document).on('click', '.action-create', function() {
                const me = this;
                const rowid = $(me).data('rowid');
                const data = table.row($(me).parents('tr')).data();

                $('.modal-title').html('Tambah Anggota');
                $('#form-anggota').trigger('reset');

                $('#modal_anggota').data({
                    'action': 'create',
                }).modal('show');
            });
            $(document).on('click', '.action-edit', function() {
                const me = this;
                const rowid = $(me).data('rowid');
                const data = table.row($(me).parents('tr')).data();

                $('.modal-title').html('Edit Anggota');
                $('#no_anggota').val(data.no_anggota);
                $('#nama').val(data.nama);
                $('#nik').val(data.nik);
                $('#phone_number').val(data.phone_number);
                $('#email').val(data.email);
                $('#lokasi_kerja').val(data.lokasi_kerja).trigger('change');
                $('#jabatan').val(data.jabatan).trigger('change');
                $('#status').val(data.status).trigger('change');

                $('#modal_anggota').data({
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
                        const url = "{{ route('dashboard.destroy', 'rowid') }}"
                            .replace('rowid', rowid);
                        axios.delete(url)
                        .then(() => {
                            table.row($(me).parents('tr')).remove().draw();
                            getAnggotaSummary();
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
