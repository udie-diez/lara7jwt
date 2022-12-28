<style>
    .modal-content{
        float: center;
        min-width: 600px;
    }
</style>

<form method="POST" action = "{{ route('updateUsers') }}">
@csrf
@include('data_user.form')
<div class="modal-footer mt-5">
    <button type="submit" class="btn btn-outline-info btn-sm" id="btn-submit">Simpan</button>
    <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
</div>
</form>	
<script type="text/javascript">
	$(document).ready(function() {
		$('form').on('submit',function(){
			$('#btn-submit').html("<i class='icon-spinner9 spinner position-left'></i> Menyimpan dan mengirim email..");
		})
	})
</script>