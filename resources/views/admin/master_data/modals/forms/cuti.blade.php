
<div id="modal_cuti" class="modal fade" tabindex="-1">
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
                        <label for="name" class="col-form-label col-lg-2">Nama pengurus</label>
                        <div class="col-lg-10">
                            <input type="hidden" id="idUser" name="idUser" class="form-control" value="{{ Session::get('users')['id'] }}">
                            <input type="text" id="name" name="name" class="form-control" placeholder="{{ __('Nama pengurus') }}" value="{{ Session::get('users')['name'] }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-form-label col-lg-2">Jatah cuti</label>
                        <div class="col-lg-10">
                            <input type="text" id="amount" name="amount" class="form-control" placeholder="{{ __('Jatah cuti') }}" autofocus required>
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
