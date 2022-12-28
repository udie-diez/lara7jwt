@include('layouts.mylib')
<style>
	.modal-content {
		float: right;
		min-width: 800px;
	}
</style>
<div class="card">
    <div class="card-header" style="font-size: medium;">
        <div class="card-title" >Laporan Jurnal</div>
        <div class="card-title">{{$jenis.' #'.$nomor}}</div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead class="bg-info-300">
                <tr>
                    <th>Nomor Akun</th>
                    <th>Nama Akun</th>
                    <th class="text-right">Debit (Rp.)</th>
                    <th class="text-right">Kredit (Rp.)</th>
                </tr>
            </thead>
            <tbody>
                <?php $tdebet = $tkredit = 0; ?>
                @foreach($data as $row)
                <tr>
                    <td>{{$row->kode}}</td>
                    <td>{{$row->nama}}</td>
                    <td class="text-right">{{Rupiah($row->debit,2)}}</td>
                    <td class="text-right">{{Rupiah($row->kredit,2)}}</td>
                </tr>
                <?php
                $tdebet += $row->debit;
                $tkredit += $row->kredit;
                ?>
                @endforeach
            </tbody>
            <tfoot style="font-weight: bold;">
                <tr>
                    <td colspan="2">Total</td>
                    <td class="text-right">{{Rupiah($tdebet,2)}}</td>
                    <td class="text-right">{{Rupiah($tkredit,2)}}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="card-footer text-right">
        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    })
</script>