@extends('layouts.app')

@section('page_header')

@endsection

@section('content')
    <div class="content">
        <div class="card">
            <table id="tbl-jenis-cuti" class="table table-bordered table-hover datatable-show-all">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Nama alasan</th>
                        <th>Memotong cuti tahunan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
	<script src="{{ asset('themes/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script>
        $(function() {
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 100,
                    // targets: [ 5 ]
                }],
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
            });
            $('#tbl-jenis-cuti').DataTable({
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('jenisCuti.list') }}",
                    headers: { 'Authorization': `Bearer ${getAccT()}` }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'alasan', name: 'alasan'},
                    {data: 'potong_cuti_tahunan', name: 'potong_cuti_tahunan'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection
