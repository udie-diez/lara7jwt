<!-- modal -->
<div class="modal fade" id="modalMd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card-header bg-info border-bottom-info header-elements-inline">
                
                    <h4 class="modal-title" id="modalMdTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="modalError"></div>
                    <div id="modalMdContent"></div>
                </div>
                
            </div>
        </div>
    </div>
<!-- /modal -->

<script type="text/javascript">

$(document).ready(function () {
    
    var table = $('.basic').DataTable();
    var tablex = $('.basicx').DataTable();
    var tablexx = $('.basicxx').DataTable();

	$('.basic tbody ').on('click', 'tr', function () {
		var data = table.row( this ).data();
        if(!data)return false;
		var url = data[1];
        $('#modalMdContent').load(url);
        $('#modalMdTitle').html($('#btn-edit').attr('title'));
    });

    $('.basicx tbody ').on('click', 'tr', function () {
        var data = tablex.row( this ).data();
		var url = data[1];
        $('#modalMdContent').load(url);
        $('#modalMdTitle').html($('#btn-edit').attr('title'));
    });

    $('.basicxx tbody ').on('click', 'tr', function () {
        var data = tablexx.row( this ).data();
		var url = data[1];
        $('#modalMdContent').load(url);
        $('#modalMdTitle').html($('#btn-edit').attr('title'));
    });

    $('#btn-ubah').on('click', function () {
		var url = $('#url').val();
        $('#modalMdContent').load(url);
        $('#modalMdTitle').html($('#btn-edit').attr('title'));
    });
 
    $('.modalMd').off('click').on('click', function () {
        $('#modalMdContent').load($(this).attr('value'));
        $('#modalMdTitle').html($(this).attr('title'));
    });
    
});
</script>