@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')

<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>

<div class="card">
<div class="card">
    <div class="card-body">
        <form method="POST" action = "{{ route('filterPelunasan') }}">
            @csrf
            <div class="form-group row">
                <label class="col-form-label col-sm-1  font-weight-bold">FILTER  : </label>
                <label class="col-form-label pr-2">Periode :</label>
                <div class="col-sm-2">
                    @php $bulan=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
                     <select name="f_bulan" id="f_bulan" class="select">
                         @for($i=0;$i < 12; $i++)
                         <option value={{$i+1}} <?php 
                                if(isset($bulan_f)){
                                    if($bulan_f==$i+1) echo 'selected';
                                }else{
                                    if(($i+1)==date('m')) echo 'selected';
                                }
                                 ?>>{{ $bulan[$i] }}</option>
                        @endfor
                     </select>
                </div>
                <div class="col-sm-1">
                    <input type="text" name="f_tahun" id="f_tahun" placeholder="Tahun" class="form-control" value="{{ $tahun_f ?? date('Y') }}">
                </div>
                <div class="col-sm-2">
                        <select name="f_sumber" id="f_sumber" data-placeholder="Pilih Sumber Pinjaman" class="select">
                            <option value=""></option>
                            <option value="all" <?php if(isset($sumber_f)){if($sumber_f=='all') echo 'selected';} ?>><strong>SEMUA</strong></option>

                            @foreach($sumber as $r)
				                <option value="{{$r->id}}" <?php if(isset($sumber_f)){if($sumber_f==$r->id) echo 'selected';} ?>>{{$r->nama}}</option>
                            @endforeach

                        </select>
                </div> 
                <div class="col-sm-1">
                    <button type="submit" class="btn btn-outline-info btn-sm" id="btn-submit-f">Tampilkan</button>
                </div>
            </div>

        </form>
    </div>
    </div>
    <div class="card-header header-elements-inline">
        <h5 class="card-title" id="title-form">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <a href="{{ route('bayarPelunasan') }}" class="btn btn-outline-info btn-sm ml-1" ><i class="icon-plus2"></i> Pelunasan Baru </a>
        </div>
    </div>
 
    <table class="table basicx table-hover" id="table_1">
        <thead>
            <tr class="text-center">
                <th class="export">NO.</th>
                <th class="export">NAMA</th>
                <th class="export">NIK</th>
                <th class="export">JUMLAH <br> PINJAMAN</th>
                <th class="export">TENOR (Bln)</th>
                <th class="export">SUMBER <br>PINJAMAN</th>
                <th class="export">SISA <br> ANGSURAN</th>
                <th class="export">SISA PINJAMAN</th>
                <th class="export">JUMLAH <br> PELUNASAN</th>
                <th class="export">TANGGAL <br> PELUNASAN</th>
                <th class="text-center">AKSI</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
             
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td> {{ $row->nama }}</td>
                <td class="text-center">{{ $row->nik }}</td>
                <td class="text-center">{{ number_format($row->nilaifix,0,'.',',') }}</td>
                <td class="text-center">{{ $row->tenorfix }}</td>
                <td class="text-center">{{ $row->namasumber }}</td>
                <td class="text-center">{{ number_format($row->sisaangsuran,0,'.',',')  }}</td>
                <td class="text-center">{{ number_format($row->sisapinjaman,0,'.',',')  }}</td>
                <td class="text-center">{{ number_format($row->jumlah,0,'.',',')  }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($row->tgl_bayar)) }}</td>
                <td class="text-center" title="Data Pelunasan">
                    <div class="list-icons">
                        <a href="{{ url('pinjaman/showpelunasan').'/'.$row->pinjamanid.'/0' }}" id="btn-edit" class="list-icons-item text-info-600" title="Detail Pelunasan"  ><i class="icon-eye8"></i></a>
                        <a href="{{ action('PinjamanCont@destroyPelunasan',['id'=>$row->id]) }}" id="btn-del" onclick="return confirm('Anda yakin ingin menghapus data ini ?')" class="list-icons-item text-danger-600" title="Hapus Angsuran"  ><i class="icon-bin"></i></a>
                        
                    </div> 
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection
