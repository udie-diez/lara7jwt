
<div id="modal_cuti" class="modal fade" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-cuti">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="error"></div>

                    <div class="form-group row">
                        <label for="idUser" class="col-form-label col-lg-4">{{ __('Nama Pengurus') }}</label>
                        <div class="col-lg-8">
                            <select id="idUser" name="idUser" class="form-control" placeholder="{{ __('Nama Pengurus') }}" data-fouc autofocus required>
                                <option value="" selected>{{ __('Nama Pengurus') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-form-label col-lg-2">Jatah cuti</label>
                        <div class="col-lg-10">
                            <input type="text" id="amount" name="amount" class="form-control" placeholder="{{ __('Jatah cuti') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="sisa" class="col-form-label col-lg-2">Sisa cuti</label>
                        <div class="col-lg-10">
                            <input type="text" id="sisa" name="sisa" class="form-control" placeholder="{{ __('Sisa cuti') }}" required>
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
