<style>
    .modal-content{
        float: right;
        min-width: 800px;
    }
</style>

<form method="POST" action = "#">
@include('setoran.form')
<div class="modal-footer pt-5 ">
    <button type="button" id="btn-input" style="display: none;" class="btn btn-outline-success" id="btn-submit">+ Input Baru</button>
    <button type="submit" class="btn btn-outline-info" id="btn-submit">Simpan</button>
    <button type="button" class="btn btn-outline-danger" data-dismiss="modal" >Tutup</button>
</div>
</form>	