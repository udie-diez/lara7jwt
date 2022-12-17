
<div id="modal_banner" class="modal fade" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-banner">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="error"></div>

                    <div class="form-group row">
                        <label for="title" class="col-form-label col-lg-2">Judul</label>
                        <div class="col-lg-10">
                            <input type="text" id="title" name="title" class="form-control" placeholder="{{ __('Judul banner') }}" autofocus required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="image" class="col-form-label col-lg-2">Gambar</label>
                        <div class="col-lg-10">
                            <input type="file" id="image" name="image" class="form-input-styled" data-fouc placeholder="{{ __('Pilih gambar') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Deskripsi</label>
                        <div class="col-lg-10">
                            <input type="hidden" id="description" name="description" class="form-control" placeholder="{{ __('Deskripsi banner') }}">
                            <div class="trumbowyg_default" placeholder="Deskripsi banner"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="link" class="col-form-label col-lg-2">Link</label>
                        <div class="col-lg-10">
                            <input type="url" id="link" name="link" class="form-control" placeholder="{{ __('Link banner') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-form-label col-lg-2">Status</label>
                        <div class="col-lg-10">
                            <select id="status" name="status" class="form-control form-input-styled" data-fouc placeholder="{{ __('Pilih status') }}" required>
                                <option value="">Pilih status</option>
                                <option value="true">Aktif</option>
                                <option value="false">Tidak aktif</option>
                            </select>
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
