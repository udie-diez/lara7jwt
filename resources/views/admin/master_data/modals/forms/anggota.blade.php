
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
                            <input type="text" id="kode" name="kode" class="form-control" placeholder="{{ __('No Anggota') }}" autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Nama</label>
                        <div class="col-lg-10">
                            <input type="text" id="name" name="name" class="form-control" placeholder="{{ __('Nama') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Email</label>
                        <div class="col-lg-10">
                            <input type="text" id="email" name="email" class="form-control" placeholder="{{ __('Email') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Password</label>
                        <div class="col-lg-10">
                            <input type="text" id="password" name="password" class="form-control" placeholder="{{ __('Password') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Jabatan</label>
                        <div class="col-lg-10">
                            <input type="text" id="user_type" name="user_type" class="form-control" placeholder="{{ __('Jabatan') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Role</label>
                        <div class="col-lg-10">
                            <input type="text" id="role" name="role" class="form-control" placeholder="{{ __('Role') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Status</label>
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
