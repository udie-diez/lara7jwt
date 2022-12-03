
<div id="modal_alasan_cuti" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-alasan-cuti">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="error"></div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Jenis cuti</label>
                        <div class="col-lg-10">
                            <select id="jenis_cuti" name="jenis_cuti_id" class="form-control form-input-styled" data-fouc placeholder="{{ __('Pilih jenis cuti') }}" required>
                                <option value="">Pilih jenis cuti</option>
                                @foreach ($jenis_cuti as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->alasan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Nama alasan</label>
                        <div class="col-lg-10">
                            <input type="text" id="alasan" name="alasan" class="form-control" placeholder="{{ __('Nama alasan') }}" autofocus required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Maksimum hari</label>
                        <div class="col-lg-10">
                            <div class="input-group">
                                <input type="text" id="max_hari" name="max_hari" class="form-control" placeholder="{{ __('Maksimum hari') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">hari</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Status</label>
                        <div class="col-lg-10">
                            <select id="status" name="status" class="form-control form-input-styled" data-fouc placeholder="{{ __('Pilih status') }}" required>
                                <option value="">Pilih status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak">Tidak aktif</option>
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
