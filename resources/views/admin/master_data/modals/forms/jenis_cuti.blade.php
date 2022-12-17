
<div id="modal_jenis_cuti" class="modal fade" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-jenis-cuti">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="error"></div>

                    <div class="form-group row">
                        <label for="description" class="col-form-label col-lg-2">Nama alasan</label>
                        <div class="col-lg-10">
                            <input type="text" id="description" name="description" class="form-control" placeholder="{{ __('Nama alasan') }}" autofocus required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="isAnnualLeave" class="col-form-label col-lg-2">Memotong cuti tahunan</label>
                        <div class="col-lg-10">
                            <select id="isAnnualLeave" name="isAnnualLeave" class="form-control form-input-styled" data-fouc placeholder="{{ __('Pilih status') }}" required>
                                <option value="">Pilih status</option>
                                <option value="true">Ya</option>
                                <option value="false">Tidak</option>
                            </select>
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
