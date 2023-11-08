<div class="row">
    <div class="col-md-6">
        <?php  
            $uri_enc = $this->uri->segment(4);
            $uri_dec = decrypt_url($uri_enc);
        ?>

        <!-- Update Settings Selain Logo -->
        <?php if($uri_dec !== 'APPLogo'): ?>
        <?= form_open(base_url('/app/settings/update/'. $uri_enc), ['id' => 'formSetting', 'class' => 'form-horizontal']) ?>
            <div class="form-group">
                <label for="key">Key <span class="text-danger">*</span></label>
                <input type="text" id="key" value="<?= $data->key ?>" class="form-control" disabled />
            </div>
            <div class="form-group">
                <label for="val">Value <span class="text-danger">*</span></label>
                <textarea name="val" id="val" cols="30" rows="10" class="form-control"><?= $data->val ?></textarea>
            </div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea name="desc" id="desc" cols="30" rows="10" class="form-control"><?= $data->deskripsi ?></textarea>
            </div>
            <div class="form-gourp">
                <button class="btn btn-success rounded-0" type="submit"><i class="fa fa-save mr-2"></i> Simpan</button>
                <button class="btn btn-danger rounded-0" type="button" onclick="window.history.back(-1)"><i class="fa fa-close mr-2"></i> Batal</button>
            </div>
        <?= form_close(); ?>
        <?php endif; ?>
        
        <!-- Update Settings Khusus Logo -->
        <?php if($uri_dec === 'APPLogo'): ?>
        <?= form_open_multipart(base_url('/app/settings/updateWithImage/'. $uri_enc), ['id' => 'formSetting', 'class' => 'form-horizontal']) ?>
            <div class="form-group">
                <label for="key">Key <span class="text-danger">*</span></label>
                <input type="text" id="key" value="<?= $data->key ?>" class="form-control" disabled />
            </div>
            <div class="divider-dashed"></div>
            <div class="form-group">
                <div><img width="50" src="<?= base_url('template/assets/'.$data->val) ?>" alt="AppLogo"></div>
            </div>
            <div class="form-group">
                <label for="customFile">Value <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-control" id="customFile">
                <p class="help-block">*) Jika logo tidak diganti, biarkan kosong bagian ini.</p>
            </div>
            <div class="divider-dashed"></div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea name="desc" id="desc" cols="30" rows="10" class="form-control"><?= $data->deskripsi ?></textarea>
            </div>
            <div class="form-gourp">
                <button class="btn btn-success rounded-0" type="submit"><i class="fa fa-save mr-2"></i> Simpan</button>
                <button class="btn btn-danger rounded-0" type="button" onclick="window.location.href='<?= base_url('/app/settings') ?>'"><i class="fa fa-close mr-2"></i> Batal</button>
            </div>
        <?= form_close(); ?>
        <?php endif; ?>
    </div>
</div>