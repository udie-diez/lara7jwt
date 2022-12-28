@extends('layouts.home')
@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info modalMd" href="#" value="{{ action('AkunCont@create') }}" title="Input Data akun" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> akun</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table basic table-hover">
            <thead class="bg-slate-300">
                <tr>
                    <th>NO.</th>
                    <th hidden>ID</th>
                    <th width="150px">KODE AKUN</th>
                    <th>NAMA AKUN</th>
                    <th class="text-center">KATEGORI</th>
                    <th hidden width="150px" class="text-right">SALDO(Rp)</th>
                    <th class="text-center">JENIS</th>

                    <th width="100px" class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <?php
                $kategori = '';
                $tagx =  substr($row->kode, 3, 4) == '0000' ? 'font-weight-bold' : '';
                foreach ($data as $key) {
                    if (substr($row->kode, 0, 2) . '.0000' == $key->kode) {
                        $kategori = $key->nama;
                        break;
                    }
                }

                ?>
                <tr class="<?= @$tagx ?>">
                    <td>{{ $loop->iteration }}</td>
                    <td hidden>{{ url('akun/show').'/'.$row->id }}</td>
                    <td><?= @($row->jenis == 1 ? '&nbsp;&nbsp;&nbsp; ' : '') . $row->kode ?></td>
                    <td><a href="{{ route('detailAkun', $row->id)}}" id="btn-edit" class="list-icons-item text-teal-800" title="Transaksi Akun"><?= @($row->jenis == 1 ? '&nbsp;&nbsp;&nbsp; ' : '') . $row->nama ?></a></td>
                    <td class="text-center"> {{ $kategori }}</td>
                    <td hidden class="text-right"> {{ rupiah($row->saldo,2) }}</td>
                    <td class="text-center"><?php echo $row->jenis == 1 ? 'DETAIL' : 'HEADER'  ?></td>
                    <td class="text-right">
                        <div class="list-icons">
                            <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                            <a href="{{ action('AkunCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /basic datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.basic').DataTable().page.len(130).draw();

    })
</script>

@endsection