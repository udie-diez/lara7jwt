<style>
    .modal-content{
        float: right;
        min-width: 600px;
    }
</style>

<form method="POST" action = "{{ route('storeJenisSimpanan') }}">
@csrf
@include('data_jenis_simpanan.form')
<div class="modal-footer">
    <button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
    <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
</div>
</form>	