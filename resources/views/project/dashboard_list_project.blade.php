@include('layouts.mylib')
<style>
	.modal-dialog {
		max-width: 1100px;
	}
</style>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>

<script type="text/javascript">
	$('.tproject').DataTable({
		ordering: false,
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Search :</span> _INPUT_',
            searchPlaceholder: '...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
	});
</script>
<div class="card">
	<div class="card-header">
		<h6 class="card-title"></h6>

	</div>
	 
	<table class="table table-bordered table-hover tproject">
		<thead class="text-center">
			<tr>
				<th class="export">NO.</th>
				<th class="export">MITRA PERUSAHAAN</th>
				<th class="export">URAIAN PROJECT</th>
				<th class="export" style="min-width: 80px;">TANGGAL</th>
				<th class="export">NOTA PESANAN</th>
				<th class="export">NILAI PROJECT</th>
				<th class="export">PEMESAN <br>/ USER</th>
				<th class="export">AM</th>
				<th class="export">STATUS</th>
			</tr>
		</thead>
		<tbody>
			@if(isset($project) && count($project)>0 )
			@foreach($project as $p)
			<tr>
				<td class="text-center">{{ $loop->iteration }}</td>
				<td>{{ $p->perusahaan }}</td>
				<td><a target="_blank" href="{{ route('showProject',$p->id) }}" class="text-teal-800" title="Detail Project">{{ $p->nama }}</a></td>
				<td>{{ IndoTgl($p->tgl_po) }}</td>
				<td>{{ $p->no_po }}</td>
				<td class="text-right">{{ Rupiah($p->nilai) }}</td>
				<td>{{ $p->pemesan }}</td>
				<td>{{ $p->am }}</td>
				<td class="text-center">{{ $p->status == 0 ? 'CLOSED' : 'OPEN' }}</td>
			</tr>
			@endforeach
			@endif
		</tbody>

	</table>
	
</div>
<div style="float: right;">
	<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal" aria-label="Tutup">TUTUP</button>

	</div>