<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-straped table-hover">
                <thead class="bg-white">
                    <th>Setting judul / Deskripsi</th>
                    <th>Value / Isi</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    <?= 
                        form_open(base_url('/app/settings/updateAll'), ['id' => 'formSettings']);
                    ?>
                    <?php foreach($data->result() as $r): ?>
                    <tr>
                        <td><b><?= $r->key ?></b> <br/> <?= ucwords($r->deskripsi) ?></td>
                        <td>
                            <?= $r->val ?>
                            <!-- Button Edit -->
                            <?php if($r->key === 'APPName' || $r->key === 'APPDescription' ||
                            $r->key === 'APPLogo' || $r->key === 'copyright' || $r->key === 'version_app'
                            && $this->session->userdata('role') === 'SUPER_ADMIN'): ?>
                                <a href="<?= base_url('/app/settings/ubah/'.encrypt_url($r->key)) ?>" class="btn btn-sm btn-info rounded-pill pull-right"><i class="fa fa-edit"></i></a>
                            <?php endif; ?>
                            <!-- Logo -->
                            <?php if($r->key === 'APPLogo' && $this->session->userdata('role') === 'SUPER_ADMIN'): ?>
                               <div><img width="50" src="<?= base_url('template/assets/'.$r->val) ?>" alt="AppLogo"></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $is_checked = $r->status === 'Y' ? 'checked' : ''; ?>
                            <?php $is_checked_value = $r->status === 'Y' ? 'Y' : 'N'; ?>
                            <input type="checkbox" name="val[<?= $r->key ?>]" value="<?= $is_checked_value ?>" class="js-switch" <?= $is_checked ?>/>
                            <input type="hidden" name="key[]" value="<?= $r->key ?>">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="bg-white">
                        <td colspan="4"><button type="submit" class="btn btn-primary rounded-0"><i class="fa fa-save mr-2"></i> Simpan Perubahan</button></td>
                    </tr>
                    <?= form_close() ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $("input[type='checkbox']").on("change", function(e) {
        let _ = $(this);
        if(_[0].checked === true) {
            _.val('Y')
        } else {
            _.val('N')
        }
        console.log(_[0])
    })
</script>