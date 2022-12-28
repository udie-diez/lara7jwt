@include('layouts.mylib')
<table class="table table-hover table-bordered">
    <thead class="text-center">
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Nama </th>
            <th rowspan="2">Target Setahun <br> (Rp.)</th>
            <th colspan="2">Cash-in</th>
            <th colspan="2">Tagihan</th>
            <th colspan="2">Potensi</th>
            <th rowspan="2"><span class="text-danger"> Defisit</span> / <span class="text-success"> Surplus</span> <br> (Rp)</th>
        </tr>
        <tr>
            <th>Jumlah (Rp.)</th>
            <th> Persen (%) </th>
            <th>Jumlah (Rp.)</th>
            <th> Persen (%) </th>
            <th>Jumlah (Rp.)</th>
            <th> Persen (%) </th>
        </tr>
    </thead>
    <tbody class="text-right">
        @if(isset($progrespegawai))
        @php $no=1;$tot_ttarget = $tot_cashin = $tot_tagihan = $tot_potensi = 0; @endphp
        @foreach($progrespegawai as $p)
        <?php

        $ttarget = $p->ttarget ?? 0;
        $cashin = $p->cashin ?? 0;
        $tagihan = $p->tagihan ?? 0;
        $potensi = $p->potensi ?? 0;
        $tot_ttarget += $ttarget;
        $tot_cashin += $cashin;
        $tot_potensi += $potensi;
        $tot_tagihan += $tagihan;
        ?>

        <tr>
            <td class="text-center">{{$no++}}.</td>
            <td class="text-left"><a class="text-default" href="{{ route('projectDbpegawai', $p->id) }}"> {{$p->nama}} </a></td>
            <td>{{ Rupiah($ttarget) }}</td>
            <td>{{ Rupiah($cashin) }}</td>
            <td class="text-center">{{ Rupiah( ($ttarget > 0 ? $cashin/$ttarget : 0) * 100,2) }}</td>
            <td>{{ Rupiah($tagihan) }}</td>
            <td class="text-center">{{ Rupiah( ($ttarget > 0 ? $tagihan/$ttarget : 0) * 100,2) }}</td>
            <td>{{ Rupiah($potensi) }}</td>
            <td class="text-center">{{ Rupiah( ($ttarget > 0 ? $potensi/$ttarget : 0) * 100,2) }}</td>
            <td><span class="{{$ttarget > $cashin ? 'text-danger' : 'text-success' }}"> {{Rupiah($ttarget - $cashin) }}</span></td>
        </tr>
        @endforeach
        @endif

    </tbody>
    <tfoot class="font-weight-bold text-right">
        <tr>
            <td></td>
            <td class="text-center">Total</td>
            <td>{{ Rupiah($tot_ttarget) }}</td>
            <td>{{ Rupiah($tot_cashin) }}</td>
            <td class="text-center">{{ Rupiah( ($tot_cashin/$tot_ttarget) * 100,2) }}</td>
            <td>{{ Rupiah($tot_tagihan) }}</td>
            <td class="text-center">{{ Rupiah( ($tot_tagihan/$tot_ttarget) * 100,2) }}</td>
            <td>{{ Rupiah($tot_potensi) }}</td>
            <td class="text-center">{{ Rupiah( ($tot_potensi/$tot_ttarget) * 100,2) }}</td>
            <td>{{ Rupiah($tot_ttarget - $tot_cashin) }}</td>
        </tr>
    </tfoot>
</table>