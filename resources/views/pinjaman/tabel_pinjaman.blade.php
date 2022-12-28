<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
 
	 $('.basic').DataTable();
})
</script>
@include('layouts.mylib')
<style>
    .basic{
        font-size: small;
    }
</style>
<div class="card">

    <table class="table basic">
        <thead>
            <tr>
                <th>NO.</th>
                <th class="text-left">NAMA</th>
                <th>NIK</th>
                <th class="text-right"> PLAFON (Rp.)</th>
                <th >TENOR (Bln)</th>
                <th>ANGSURAN (Rp.)</th>
                <th>AWAL ANGSURAN</th>
                <th>AKHIR ANGSURAN</th>
            </tr>
        </thead>
        <tbody>
        
        @foreach($data as $row)

            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td> {{ $row->nama }}</td>
                <td class="text-center">{{ $row->nik }}</td>
                <td class="text-right">{{ number_format($row->nilaifix,0) }}</td>
                <td class="text-center">{{ $row->tenorfix }}</td>
                <td class="text-right">{{ number_format($row->angsuranfix,0) }}</td>
                <td class="text-center">{{ date('M - Y', strtotime($row->tgl_awal)) }}</td>
                <td class="text-center">{{ date('M - Y', strtotime($row->tgl_akhir)) }}</td>
            </tr>
        @endforeach
        </tbody>
         
    </table>
</div>