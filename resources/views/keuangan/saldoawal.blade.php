@extends('layouts.home')
@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <form method="POST" action="{{ route('saldoawalupdate') }}">
        @csrf
        <div class="card-header  header-elements-inline">
            <h5 class="card-title">{{$tag['judul']}}</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <label for="">Tanggal Mulai Per: 1 Jan 2021</label>
                </div>
            </div>
        </div>
        <div class="card-body" style="height: 500px;overflow-y: auto;">
            <table class="table table-hover">
                <thead class="bg-slate-300">
                    <tr>
                        <th hidden>NO.</th>
                        <th width="100px">AKUN</th>
                        <th width="400px">NAMA AKUN</th>
                        <th class="text-center" width="200px">DEBIT (Rp)</th>
                        <th class="text-center" width="200px">KREDIT (Rp)</th>
                        <th class="text-center">KATEGORI</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totdebit=$totkredit=0 @endphp
                    @foreach($data as $row)
                    <?php
                    $kategori = '';
                    $tagx =  substr($row->kode, 3, 4) == '0000' ? 'font-weight-bold' : '';
                    $idmax = $row->id;
                    $totdebit += $row->debit;
                    $totkredit += $row->kredit;
                    foreach ($data as $key) {
                        if (substr($row->kode, 0, 2) . '.0000' == $key->kode) {
                            $kategori = $key->nama;
                            break;
                        }
                    }
                    ?>
                    @if($row->jenis==1)
                    <tr class="<?= @$tagx ?>">
                        <td hidden>{{ $loop->iteration }}</td>
                        <td><?= @($row->jenis == 1 ? '&nbsp;&nbsp;&nbsp; ' : '') . $row->kode ?></td>
                        <td><?= @($row->jenis == 1 ? '&nbsp;&nbsp;&nbsp; ' : '') . $row->nama ?></td>
                        <td class="text-right"><input type="text" name="d_{{$row->id}}" id="d_{{$row->id}}" class="form-control text-right nilai debit" value="{{ $row->debit > 0 ? rupiah($row->debit,2) : ''  }}"></td>
                        <td class="text-right"><input type="text" name="k_{{$row->id}}" id="k_{{$row->id}}" class="form-control text-right nilai kredit" value="{{ $row->kredit > 0 ? rupiah($row->kredit,2) : '' }}"></td>
                        <td class="text-center"> {{ $kategori }}</td>

                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-body">
            <table width="100%">
                <tbody style="background-color: lightgray;">
                    <tr class="font-weight-bold">
                        <td class="text-center" width="500px">JUMLAH</td>
                        <td class="text-right" id="totdebit" width="200px">{{ rupiah($totdebit,2) }}</td>
                        <td class="text-right" id="totkredit" width="200px">{{ rupiah($totkredit,2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            <input type="hidden" name="maxid" id="" value="{{$idmax}}">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.history.go(-1); return false;">Kembali</button>
            <button type="submit" class="btn btn-outline-info btn-sm" onclick="return confirm('Anda yakin ingin menyimpan data Saldo Awal ini ??')">Simpan</button>
        </div>
    </form>
</div>
<!-- /basic datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        window.scroll(0, document.documentElement.scrollHeight);
        $('.basic').DataTable().page.len(130).draw();
        // $('.nilai').on('keyup', function() {
        //     var id = $(this).attr('id');
        //     $('#' + id).val(formatRupiah(this.value));
        // })

        $('form').on('submit', function(e){
            var kredit = $('#totkredit').text();
            var debit = $('#totdebit').text();
            
            if(kredit != debit){
                alert('Perhatian. Jumlah Debit dan Kredit tidak sama (Tidak Seimbang), silahkan sesuaikan terlabih dahulu .');
                e.preventDefault();
                return false;

            }
        })

        $('.nilai').change(function() {
            var id = $(this).attr('id');
            $('#' + id).val(formatRupiah(this.value));
            var tot = 0;
            var nilai = 0;

            $('.debit').each(function() {

                nilai = this.value ? this.value : 0;

                nilai = nilai.toString().replace(/[^,\d]/g, '').toString();
                nilai = nilai.replace(',', '.');
                tot = +parseFloat(nilai).toFixed(2) + +parseFloat(tot).toFixed(2);
            });
            tot = parseFloat(tot).toFixed(2).toString().replace('.', ',');
            $('#totdebit').text(formatRupiah(tot));

            var tot = 0;
            var nilai = 0;

            $('.kredit').each(function() {

                nilai = this.value ? this.value : 0;

                nilai = nilai.toString().replace(/[^,\d]/g, '').toString();
                nilai = nilai.replace(',', '.');
                tot = +parseFloat(nilai).toFixed(2) + +parseFloat(tot).toFixed(2);
            });
            tot = parseFloat(tot).toFixed(2).toString().replace('.', ',');
            $('#totkredit').text(formatRupiah(tot));

        });
    })
</script>

@endsection