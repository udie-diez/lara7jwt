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
                <th>NO</th>
                <th>NAMA ANGGOTA</th>
                <th>NIK</th>
                <th class="text-center">SIMPANAN</th>
                <th>JUMLAH (Rp)</th>
                <th>BULAN</th>
                <th>TAHUN</th>
            </tr>
        </thead>
        <tbody>
        
        @foreach($data as $row)

            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td> {{ $row->nama_anggota }}</td>
                <td> {{ $row->nik }}</td>
                <td class="text-center" >{{ $row->jenis_simpanan }}</td>
                <td class="text-center" >{{ number_format($row->nilai,0,'.',',') }}</td>
                <td> {{ strtoupper(bulan($row->bulan))}}</td>
                <td> {{  $row->tahun }}</td>
                 
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td></td>
                <td class="text-center">TOTAL</td>
                <td>{{ number_format($data->sum('nilai')) }}</td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>