<style>
    .modal-content{
        float: right;
        min-width: 600px;
    }
</style>

<form method="POST" action = "{{ route('storeSumberPinjaman') }}">
@csrf
@include('data_sumber_pinjaman.form')
<div class="modal-footer">
    <button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
    <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
</div>
</form>	