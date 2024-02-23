<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link pb-4 font-weight-bold" style="font-size:16px" id="global-tab" data-toggle="tab" href="#global" role="tab" aria-controls="global" aria-selected="false"><i class="fa fa-cogs mr-2"></i>Global</a>
            </li>
            <li class="nav-item ml-2">
                <a class="nav-link pb-4 font-weight-bold" style="font-size:16px" id="periode-tab" data-toggle="tab" href="#periode" role="tab" aria-controls="periode" aria-selected="false"><i class="fa fa-calendar mr-2"></i>Periode</a>
            </li>
        </ul>
        <div class="x_panel" style="border-top:0">
            <div class="x_content">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade <?= @$_GET['globalShow']." ".@$_GET['globalActive'] ?>" id="global" role="tabpanel" aria-labelledby="global-tab">
                        <h4>Pengaturan Global</h4>
                        <div class="table-responsive">
                            <table class="table table-straped">
                                <tbody>
                                    <?=
                                    form_open(base_url('/app/settings/updateAll'), ['id' => 'formSettings']);
                                    ?>
                                    <?php foreach ($data->result() as $r) : ?>
                                        <tr>
                                            <td><b><?= $r->key ?></b> <br /> <?= ucwords($r->deskripsi) ?></td>
                                            <td class="text-wrap">
                                                <?= $r->val ?>
                                                <!-- Button Edit -->
                                                <?php if (
                                                    $r->key === 'APPName' || $r->key === 'APPDescription' ||
                                                    $r->key === 'APPLogo' || $r->key === 'copyright' || $r->key === 'version_app'
                                                    && $this->session->userdata('role') === 'SUPER_ADMIN'
                                                ) : ?>
                                                    <a href="<?= base_url('/app/settings/ubah/' . encrypt_url($r->key)) ?>" class="btn btn-sm btn-info rounded-pill pull-right"><i class="fa fa-edit"></i></a>
                                                <?php endif; ?>
                                                <!-- Logo -->
                                                <?php if ($r->key === 'APPLogo' && $this->session->userdata('role') === 'SUPER_ADMIN') : ?>
                                                    <div><img width="50" src="<?= base_url('template/assets/' . $r->val) ?>" alt="AppLogo"></div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php $is_checked = $r->status === 'Y' ? 'checked' : ''; ?>
                                                <?php $is_checked_value = $r->status === 'Y' ? 'Y' : 'N'; ?>
                                                <input type="checkbox" name="val[<?= $r->key ?>]" value="<?= $is_checked_value ?>" class="js-switch" <?= $is_checked ?> />
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
                    <div class="tab-pane fade <?= @$_GET['periodeShow']." ".@$_GET['periodeActive'] ?>" id="periode" role="tabpanel" aria-labelledby="periode-tab"></div>
                </div>
            </div>
        </div>
    </div>
</div>