@extends('layouts.home')
@section('maincontent')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>

        <div class="header-elements">

            <div class="btn-group">
            </div>
        </div>
    </div>
    <form action="{{ route('updateMapping') }}" method="post">
    @csrf
    <div class="card-body">

        @foreach ($data as $row)
        <div class="form-group row">
            <label class="col-form-label col-sm-2">{{ $row->tag  }}</label>
            <div class="col-sm-5">
                <select name="{{$row->jenis}}" class="select-search" data-placeholder="Pilih">
                    <option value=""></option>
                    @foreach($akun as $r)
                    <option value="{{$r->id}}" <?php if (($row->akunid) == $r->id) echo 'selected';
                                                ?>>{{'('.$r->kode . ') - ' .$r->nama}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endforeach
        @include('layouts.session')
    </div>

    <div class="card-footer">
        <button type="submit" onclick="return confirm('Anda ingin menyimpan data Akun Mapping ini ?')" class="btn btn-outline-info btn-sm">Simpan</button>
    </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {


    });
</script>
@endsection