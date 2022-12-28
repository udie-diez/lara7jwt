@extends('layouts.home')

@section('maincontent')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">{{$tag['judul']}}</h5>
        </div>
        <div class="card-body">
            <div class="error"></div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group row">
                        <label for="user" class="col-form-label col-lg-4">{{ __('Cari User') }}</label>
                        <div class="col-lg-8">
                            <select id="user" name="user" class="form-control" placeholder="{{ __('Cari User') }}" data-fouc>
                                <option value="" selected>{{ __('Cari User') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label for="date" class="col-form-label col-lg-4">{{ __('Cari Tanggal') }}</label>
                        <div class="col-lg-8">
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

            <table id="tbl-presensi" class="table datatable-basic table-hover datatable-show-all">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Tanggal') }}</th>
                        <th>{{ __('Check in') }}</th>
                        <th>{{ __('Check out') }}</th>
                        <th>{{ __('Lokasi Check in') }}</th>
                        <th>{{ __('Lokasi Check out') }}</th>
                        <th>{{ __('Koordinat Check in') }}</th>
                        <th>{{ __('Koordinat Check out') }}</th>
                        <th>{{ __('WFO/WFH') }}</th>
                        <th>{{ __('Kehadiran') }}</th>
                        <th>{{ __('Keterangan') }}</th>
                        <th>{{ __('Plan Check in') }}</th>
                        <th>{{ __('Plan Check out') }}</th>
                        <th>{{ __('Persentasi Progress') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

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
	<script src="{{ asset('themes/js/plugins/pickers/daterangepicker.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script>
	<script src="{{ asset('themes/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script>
        const validateForm = function() {
            if ($('#user').val() == '') {
                const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">Mohon pilih user</span>`;
                $('.error').html(message);
                return false
            }
            if ($('#date').val() == '') {
                const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">Mohon pilih tanggal</span>`;
                $('.error').html(message);
                return false
            }
            $('.error').html('');
            return true;
        }
        const getData = function(params) {
            const btnDownloadText = $('.action-download').html();
            if (params.download) {
                $('.action-download').prop('disabled', true).html(spinner);
                const url = "{{ route('report.presensiUser.download') }}";
                const query = new URLSearchParams(params);
                $('.action-download').prop('disabled', false).html(btnDownloadText);
                return window.open(`${url}?${query.toString()}`);
            }

            const btnFindText = $('.action-find').html();
            $('.action-find').prop('disabled', true).html(spinner);
            return axios.get("{{ route('report.presensiUser.list') }}", {
                params: params
            })
            .then(function(resp) {
                drawTable(resp.data);
                $('.action-find').prop('disabled', false).html(btnFindText);
            })
            .catch(function(err) {
                $('.action-find').prop('disabled', false).html(btnFindText);
                const message = `<span class="d-block mt-0 mb-3 validation-invalid-label">${err.toString()}</span>`;
                return $('.error').html(message);
            });
        }
        const drawTable = function(params) {
            let i = 1;
            const table = $('#tbl-presensi').DataTable({
                destroy: true,
                data: params.data,
                buttons: [
                    {
                        text: `<i class="icon-file-excel mr-2"></i> {{ __('Download') }}`,
                        className: 'btn btn-default action-download',
                    },
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                columns: [
                    {data: null, name: 'DT_RowIndex', orderable: false, searchable: false, render: function(data, type, row, meta) {
                        return i++;
                    }},
                    {data: 'date', name: 'date', render: function(data, type, row, meta) {
                        return moment(data).format('DD');
                    }},
                    {data: 'check_in', name: 'check_in', render: function(data, type, row, meta) {
                        return moment(data).format('HH:mm:ss');
                    }},
                    {data: 'check_out', name: 'check_out', render: function(data, type, row, meta) {
                        return moment(data).format('HH:mm:ss');
                    }},
                    {data: 'alamat_checkin', name: 'alamat_checkin'},
                    {data: 'alamat_checkout', name: 'alamat_checkout'},
                    {data: 'latlong_checkin', name: 'latlong_checkin'},
                    {data: 'latlong_checkout', name: 'latlong_checkout'},
                    {data: 'is_wfo', name: 'is_wfo', render: function(data, type, row, meta) {
                        return data == true ? 'WFO' : 'WFH';
                    }},
                    {data: null, name: 'kehadiran', render: function(data, type, row, meta) {
                        return 'Hadir';
                    }},
                    {data: null, name: 'reasons', render: function(data, type, row, meta) {
                        const is_ontime = row.is_ontime ? 'Tepat waktu' : '';
                        const is_late = row.is_late ? 'Terlambat' : '';
                        const is_early = row.is_early ? 'Pulang cepat' : '';
                        const is_wfo = row.is_wfo ? 'WFO' : 'WFH';
                        const is_cuti = row.is_cuti ? 'Cuti' : '';
                        const is_weekend = row.is_weekend ? 'Penghujung minggu' : '';
                        const is_holiday = row.is_holiday ? 'Hari libur' : '';
                        const reasonsList = [is_ontime, is_late, is_early, is_cuti, is_weekend, is_holiday];
                        const reasons = _.filter(reasonsList).join(', ');
                        return reasons;
                    }},
                    {data: 'plan_checkin', name: 'plan_checkin'},
                    {data: 'plan_checkout', name: 'plan_checkout'},
                    {data: 'progress', name: 'progress'},
                ]
            });
        }
        $(function() {
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
            });
            // select2
            $('#user').select2({
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
                dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                },
                buttons: [],
            });
            $('#tbl-presensi').DataTable();
            // actions
            $(document).on('click', '.action-find', function() {
                if (validateForm() !== true) return;
                const userData = $('#user').select2('data')[0];
                const range = $('#date').val().split(' - ');
                getData({
                    idUser: userData.id,
                    name: userData.name,
                    startDate: range[0],
                    endDate: range[1],
                });
            });
            $(document).on('click', '.action-download', function() {
                if (validateForm() !== true) return;
                const userData = $('#user').select2('data')[0];
                const range = $('#date').val().split(' - ');
                getData({
                    idUser: userData.id,
                    name: userData.name,
                    startDate: range[0],
                    endDate: range[1],
                    download: true,
                });
            });
        });
    </script>
@endsection
