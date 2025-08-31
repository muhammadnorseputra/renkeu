<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Session Whatsapp Notify</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <!-- CONTENT -->
                    <div class="col-sm-12">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $this->session->flashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success" role="alert">
                                <?= $this->session->flashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('qr')): ?>
                            <div class="text-center mb-3">
                                Please scan the QR code below with your WhatsApp application: <br>
                                <img src="<?= generateQr($this->session->flashdata('qr'), [], true); ?>" alt="QR Code" class="img-fluid" style="max-width: 400px;">
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['code'])): ?>
                            <div class="text-center mb-3">
                                Please scan the QR code below with your WhatsApp application: <br>
                                <img src="<?= generateQr(decrypt_url($_GET['code']), [], true); ?>" alt="QR Code" class="img-fluid" style="max-width: 400px;">
                                <br>
                                <a href="<?= base_url("app/whatsapp") ?>" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Batal</a>
                            </div>
                        <?php endif; ?>

                        <div id="loading" style="display:none; text-align:center; padding:10px;">
                            <i class="fa fa-spinner fa-spin fa-2x"></i>
                            <p>Processing...</p>
                        </div>
                        <div class="card-box table-responsive">
                            <button class="btn rounded-0 btn-info btn-compose"><i class="fa fa-plus mr-2"></i> Buat Session Baru</button>
                            <table id="table-messages" class="table jambo_table bulk_action dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Session Name</th>
                                        <th>No. Whatsapp</th>
                                        <th>Created At</th>
                                        <th class="block">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($sessions && $sessions->num_rows() > 0): ?>
                                        <?php foreach ($sessions->result() as $key => $session): ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td><?= $session->name ?></td>
                                                <td><?= $session->nohp ?></td>
                                                <td><?= date_indo(substr($session->created_at, 0, 10)) ?>, <?= date('H:i', strtotime($session->created_at)) ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <a id="btnStop" class="btn btn-sm btn-danger" href="<?= base_url("app/whatsapp/stop/" . encrypt_url($session->name)) ?>">
                                                            <i class="fa fa-stop text-warning"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-primary" href="<?= base_url("app/whatsapp?code=" . encrypt_url($session->code)) ?>"><i class="fa fa-qrcode"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No sessions available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /CONTENT -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- compose -->
<div class="compose col-md-3">
    <?= form_open(base_url('app/whatsapp/create'), ['id' => 'formSession']); ?>
    <div class="compose-header">
        New Session
        <button type="button" class="close compose-close">
            <span>×</span>
        </button>
    </div>

    <div class="compose-body pt-3">
        <div class="form-horizontal form-label-left">
            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3">Session Name <span class="text-danger">*</span></label>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="session_name" class="form-control" placeholder="Enter session name" required>
                </div>
            </div>
        </div>
    </div>
    <div class="divider-dashed"></div>
    <div class="compose-footer">
        <button id="send" class="btn btn-success rounded-0" type="submit"><i class="fa fa-send"></i> Simpan</button>
        <button class="btn btn-danger rounded-0 compose-close" type="button"><i class="fa fa-close"></i> Batal</button>
    </div>

    <?= form_close() ?>
</div>
<!-- /compose -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('formSession');
        const sendButton = document.getElementById('send');
        const loadingDiv = document.getElementById('loading');

        form.addEventListener('submit', function() {
            // tampilkan loading
            loadingDiv.style.display = 'block';

            // disable tombol submit supaya tidak bisa diklik dua kali
            sendButton.disabled = true;
            sendButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Loading...';
        });
    });
</script>