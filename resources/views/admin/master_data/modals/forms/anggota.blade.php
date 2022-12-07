
<div id="modal_anggota" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-anggota">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="error"></div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">No. Anggota</label>
                        <div class="col-lg-10">
                            <input type="text" id="no_anggota" name="no_anggota" class="form-control" placeholder="{{ __('No Anggota') }}" autofocus required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Nama</label>
                        <div class="col-lg-10">
                            <input type="text" id="nama" name="nama" class="form-control" placeholder="{{ __('Nama') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">NIK</label>
                        <div class="col-lg-10">
                            <input type="text" id="nik" name="nik" class="form-control" placeholder="{{ __('NIK') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Phone</label>
                        <div class="col-lg-10">
                            <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="{{ __('Phone') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Email</label>
                        <div class="col-lg-10">
                            <input type="text" id="email" name="email" class="form-control" placeholder="{{ __('Email') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Lokasi kerja</label>
                        <div class="col-lg-10">
                            <input type="text" id="lokasi_kerja" name="lokasi_kerja" class="form-control" placeholder="{{ __('Lokasi kerja') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Jabatan</label>
                        <div class="col-lg-10">
                            <input type="text" id="Jabatan" name="Jabatan" class="form-control" placeholder="{{ __('Jabatan') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Status</label>
                        <div class="col-lg-10">
                            <select id="status" name="status" class="form-control form-input-styled" data-fouc placeholder="{{ __('Pilih status') }}" required>
                                <option value="">Pilih status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak">Tidak aktif</option>
                                <option value="keluar">Keluar</option>
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
