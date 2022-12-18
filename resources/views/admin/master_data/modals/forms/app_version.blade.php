
<div id="modal_app_version" class="modal fade" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-app-version">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="error"></div>

                    <div class="form-group row">
                        <label for="os" class="col-form-label col-lg-2">OS</label>
                        <div class="col-lg-10">
                            <input type="text" id="os" name="os" class="form-control" placeholder="{{ __('OS') }}" autofocus required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="file" class="col-form-label col-lg-2">File</label>
                        <div class="col-lg-10">
                            <input type="file" id="file" name="file" class="form-input-styled" data-fouc placeholder="{{ __('Pilih file') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="version">Version</label>
                                <input type="text" id="version" name="version" class="form-control" placeholder="{{ __('Version') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="build">Build</label>
                                <input type="text" id="build" name="build" class="form-control" placeholder="{{ __('Build') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-form-label col-lg-2">Deskripsi</label>
                        <div class="col-lg-10">
                            <textarea id="description" name="description" class="form-control" placeholder="{{ __('Deskripsi') }}"></textarea>
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
