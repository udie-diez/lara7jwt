
<div id="modal_hari_libur" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-hari-libur">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="error"></div>

                    <div class="form-group row">
                        <label for="date" class="col-form-label col-lg-2">Tanggal</label>
                        <div class="col-lg-10">
                            <input type="text" id="date" name="date" class="form-control" placeholder="{{ __('Tanggal') }}" autofocus required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-form-label col-lg-2">Keterangan</label>
                        <div class="col-lg-10">
                            <textarea id="description" name="description" class="form-control" placeholder="{{ __('Keterangan') }}" required></textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn bg-primary action-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
