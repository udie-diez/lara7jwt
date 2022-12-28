<style>
    .modal-content{
        float: right;
        min-width: 800px;
    }
</style>

<form method="POST" action = "#">
@include('setoran.form')
<div class="modal-footer">
    <button type="submit" class="btn btn-outline-info" id="btn-submit">Simpan</button>
    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Tutup</button>
</div>
</form>	