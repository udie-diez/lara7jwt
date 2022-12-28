<style>
    .modal-content{
        float: right;
        min-width: 800px;
    }
</style>

<form method="POST" action = "{{ route('updatePengelola') }}">
@csrf
@include('data_pengelola.form')
<div class="modal-footer">
    <button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
    <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
</div>
</form>	