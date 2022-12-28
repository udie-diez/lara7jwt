@extends('layouts.home')
@section('maincontent')

<div class="card">
    <div class="card-header mt-5">
        <center>
            <h1 class="card-title"><i class="icon-cancel-circle2 icon-2x text-danger"></i> Maaf, Anda tidak memiliki akses ! </h1>
        </center>

    </div>
    <div class="mb-5">
        <center>
            <h6><a class="btn btn-outline-warning btn-sm" href="javascript:window.history.go(-1)" title="Kembali"><i class="icon-circle-left2"></i> Kembali</a></h6>
        </center>
    </div>

</div>
<!-- /basic datatable -->
@endsection