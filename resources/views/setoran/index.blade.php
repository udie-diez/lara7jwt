@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$('.select-search').select2();

    $("#tglfilter").on('DOMSubtreeModified',function(){
        document.getElementById("tglFilter").value = this.innerHTML;
     });

     var tgl = "{{ $tgl ?? '' }}";
     if(tgl) $("#tglfilter").html(tgl);
})  

</script>
<div class="card">
    <div class="card">
    <div class="card-body">
        <form method="POST" action = "{{ route('filterSetoran') }}">
            @csrf
            <div class="form-group row">
                <label class="col-form-label col-sm-1  font-weight-bold">FILTER  : </label>
                <label class="col-form-label pr-2">Tgl.Transaksi :</label>
                <div style="max-width: 22%;">
                    <button type="button" class="btn btn-default daterange-predefined">
                        <i class="icon-calendar position-left"> </i>
                        <span id="tglfilter"></span>
                        <b class="caret"></b>
                        <input type="hidden" id="tglFilter" name="tglFilter">
                    </button> 
                </div>
                <div class="col-sm-3">
                        <select name="f_anggota" id="f_anggota" data-placeholder="Pilih Anggota" class="select-search">
                            <option value=""></option>
                            <option value="all" <?php if(isset($anggotaid_f)){if($jenis_f=='all') echo 'selected';} ?>><strong>= SEMUA ANGGOTA =</strong></option>

                            @foreach($anggota as $r)
				                <option value="{{$r->id}}" <?php if(isset($anggotaid_f)){if($anggotaid_f==$r->id) echo 'selected';} ?>>{{$r->nik .' '.$r->nama}}</option>
                            @endforeach

                        </select>
                </div>
                <div class="col-sm-3">
                        <select name="f_jenissimpanan" id="f_jenissimpanan" data-placeholder="Pilih Simpanan" class="select">
                            <option value=""></option>
                            <option value="all" <?php if(isset($jenis_f)){if($jenis_f=='all') echo 'selected';} ?>><strong>= SEMUA SIMPANAN=</strong></option>

                            @foreach($jenis_simpanan as $r)
                                <option value="{{$r->nama}}" <?php if(isset($jenis_f)){if($jenis_f==$r->nama) echo 'selected';} ?>>{{$r->nama}}</option>
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
    <div class="card-header  header-elements-inline">
        <h5 class="card-title" id="title-form">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info btn-sm" href="{{ route('setoran')}}" title="Refresh Data"> <i class="icon-sync"></i> Refresh</a>
                <a class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ action('SetoranCont@create') }}" title="Input Setoran Baru" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Setoran Baru</a>
                <a hidden id="btn-import" class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ action('SetoranCont@import') }}" title="Import Data Excel" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-file-excel"></i> Import Data</a>
            </div>
        </div>
    </div>
   
 
    <table class="table basicx" id='table_1'>
        <thead>
            <tr>
                <th class="export">NO</th>
                <th hidden>ID</th>
                <th class="export">NO. SETORAN</th>
                <th class="export">NAMA ANGGOTA</th>
                <th class="export">NIK</th>
                <th class="text-center export">TGL.TRANSAKSI</th>
                <th class="text-center export">JENIS SIMPANAN</th>
                <th class="text-center export">JUMLAH SETORAN</th>
                <th class="export">PEMBYR. BULAN</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $id=0;$i=-1;
                foreach($data as $row){
                    if($id!=$row->id){
                        $i++;
                        $bulan = bulan($row->bulan).' '. $row->tahun ;
                        $arrdata[$i] = ['id'=>$row->id, 'bulan' => $bulan];

                    }elseif(($id==$row->id) && ($id >0)){
                        $bulan = $bulan ."\n" .bulan($row->bulan) .' '. $row->tahun;
                        $arrdata[$i] = ['id'=>$row->id, 'bulan' =>$bulan ];

                    }
                    $id = $row->id;
                }
            $id=0;
        ?>
        <?php $total = 0;$no=1; ?>
        @foreach($data as $row)
            @if($row->id != $id)

                <?php
                    $total += $row->nilai;
                    $bulanitem = '';
                    if($row->jenis_simpanan!='POKOK')
                    { for($i=0; $i < count($arrdata) ; $i++) {
                            if($row->id == $arrdata[$i]['id']){
                                $bulanitem = $arrdata[$i]['bulan'];
                                break;
                            }
                        }
                    }
                ?>
            <tr>
                <td>{{ $no++ }}.</td>
                <td hidden>{{ url('setoran/').'/'.$row->id }}</td>
                <td> <a href="#" id="btn-edit" class="text-teal-800" title="Update Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">{{ $row->nomor }}</a></td>
                <td> {{ $row->nama_anggota }}</td>
                <td> {{ $row->nik }}</td>
                <td class="text-center">{{ IndoTgl($row->tgl_transaksi) }}</td>
                <td class="text-center">{{ $row->jenis_simpanan }}</td>
                <td class="text-center">{{ number_format($row->nilai,0,'.',',') }}</td>
                <td><?php echo nl2br($bulanitem) ;?></td>
                <td class="text-center">
                    <div class="list-icons">
                        <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Update Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        <a href="{{ action('SetoranCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')" ><i class="icon-bin"></i></a>
                    </div> 
                </td>
            </tr>
            <?php $id=$row->id; ?>
            @endif

        @endforeach
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td></td>
                <td hidden></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-center">TOTAL</td>
                <td class="text-center">{{ number_format($total,0,'.',',') }}</td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<!-- /basic datatable --> 
@endsection