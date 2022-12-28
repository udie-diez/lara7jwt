<style>
    .modal-content{
        float: right;
        min-width: 800px;
    }
</style>

<form method="POST" action = "{{ route('updateRegister') }}">
@csrf
@include('register.form')
<div class="modal-footer mt-5">
    <button hidden type="submit" class="btn btn-outline-info">Simpan</button>
    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Tutup</button>
</div>
</form>	