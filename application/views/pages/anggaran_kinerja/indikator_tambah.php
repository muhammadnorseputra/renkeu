<div class="x_panel ui-ribbon-container">
    <div class="ui-ribbon-wrapper">
        <div class="ui-ribbon bg-success">
            New !
        </div>
    </div>
    <div class="x_title">
        <h2><i class="fa fa-plus mr-2"></i> Formulir Tambah Indikator</h2>
        <div class="clearfix"></div>
    </div>
    <div id="wizard_verticle" class="form_wizard wizard_verticle">
        <ul class="list-unstyled wizard_steps anchor">
            <li>
                <a href="#step-1" class="selected" isdone="1" rel="1">
                    <span class="step_no">1</span>
                </a>
            </li>
            <li>
                <a href="#step-2" class="done" isdone="1" rel="2">
                    <span class="step_no">2</span>
                </a>
            </li>
            <li>
                <a href="#step-3" class="done" isdone="1" rel="3">
                    <span class="step_no">3</span>
                </a>
            </li>
            <li>
                <a href="#step-4" class="done" isdone="1" rel="4">
                    <span class="step_no">4</span>
                </a>
            </li>
        </ul>
        <div class="stepContainer">
            <div id="step-1" class="content" style="display: block;">
                <h2 class="StepTitle">Step 1 Referensi</h2>
                <?= form_open(base_url('app/indikator/step/1'), ['class' => 'form-horizontal form-label-left', 'id' => 'formStep1']); ?>
                <span class="section">Pilih Referensi Indikator</span>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-sm-3 label-align" for="referensi">Referensi <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10 col-sm-6">
                        <select name="referensi" id="referensi" class="form-control" required>
                            <option value="">-- Pilih Referensi --</option>
                            <?php if (in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])): ?>
                                <option value="Tujuan" <?= $data['ref'] === 'Tujuan' ? 'selected' : ''; ?>>- Tujuan</option>
                                <option value="Sasaran" <?= $data['ref'] === 'Sasaran' ? 'selected' : ''; ?>>- Sasaran</option>
                            <?php endif; ?>
                            <option value="Program" <?= $data['ref'] === 'Program' ? 'selected' : ''; ?>>- Program</option>
                            <option value="Kegiatan" <?= $data['ref'] === 'Kegiatan' ? 'selected' : ''; ?>>- Kegiatan</option>
                            <option value="SubKegiatan" <?= $data['ref'] === 'SubKegiatan' ? 'selected' : ''; ?>>- Sub Kegiatan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row d-flex justify-content-end">

                    <button type="button" class="btn btn-secondary" onclick="nextStep('<?= base_url('app/indikator') ?>')"><i class="fa fa-list mr-2"></i>Daftar Indikator</button>
                    <button type="submit" class="btn btn-primary">Simpan & Lanjutkan</button>
                </div>
                <?= form_close(); ?>
            </div>
            <div id="step-2" class="content" style="display: none;">
                <h2 class="StepTitle">Step 2 Detail</h2>
                <hr />
                <?= form_open(base_url('app/indikator/step/2'), ['class' => 'form-horizontal form-label-left', 'id' => 'formStep2']); ?>
                <?php if ($data['ref'] === 'Tujuan'): ?>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-sm-3 label-align" for="ref_tujuan">Tujuan <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-10 col-sm-6">
                            <select name="ref_tujuan" id="ref_tujuan" class="form-control" required>
                                <option value="">-- Pilih Tujuan --</option>
                                <?php foreach ($data['tujuans']->result() as $tujuan): ?>
                                    <option value="<?= $tujuan->id; ?>"><?= $tujuan->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($data['ref'] === 'Sasaran'): ?>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-sm-3 label-align" for="ref_sasaran">Sasaran <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-10 col-sm-6">
                            <select name="ref_sasaran" id="ref_sasaran" class="form-control" required>
                                <option value="">-- Pilih Sasaran --</option>
                                <?php foreach ($data['sasarans']->result() as $sasaran): ?>
                                    <option value="<?= $sasaran->id; ?>"><?= $sasaran->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($data['ref'] === 'Program'): ?>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-sm-3 label-align" for="ref_program">Program <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-10 col-sm-6">
                            <select name="ref_program" id="ref_program" class="form-control" required>
                                <option value="">-- Pilih Program --</option>
                                <?php foreach ($data['programs']->result() as $program): ?>
                                    <option value="<?= $program->id; ?>"><?= $program->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($data['ref'] === 'Kegiatan'): ?>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-sm-3 label-align" for="ref_kegiatan">Kegiatan <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-10 col-sm-6">
                            <select name="ref_kegiatan" id="ref_kegiatan" class="form-control" required>
                                <option value="">-- Pilih Kegiatan --</option>
                                <?php foreach ($data['kegiatans']->result() as $kegiatan): ?>
                                    <?php $selectedKegiatan = $data['result']['ref_kegiatan'] == $kegiatan->id ? 'selected' : ''; ?>
                                    <option value="<?= $kegiatan->id; ?>" <?= $selectedKegiatan; ?>><?= $kegiatan->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($data['ref'] === 'SubKegiatan'): ?>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-sm-3 label-align" for="parent_kegiatan">Kegiatan <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-10 col-sm-6">
                            <select name="parent_kegiatan" id="parent_kegiatan" class="form-control" required>
                                <option value="">-- Pilih Kegiatan --</option>
                                <?php foreach ($data['kegiatans']->result() as $kegiatan): ?>
                                    <option value="<?= $kegiatan->id; ?>"><?= $kegiatan->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-sm-3 label-align" for="ref_sub_kegiatan">Sub Kegiatan <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-10 col-sm-6">
                            <select name="ref_sub_kegiatan" id="ref_sub_kegiatan" class="form-control" required>
                                <option value="">-- Pilih Sub Kegiatan --</option>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-sm-3 label-align" for="indikator">Nama Indikator <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10 col-sm-6">
                        <textarea name="indikator" id="indikator" rows="3" class="form-control" placeholder="Masukan nama indikator disini ..." required><?= $data['result']['indikator'] ?? ''; ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-sm-3 label-align" for="ref_tujuan">Jenis Indikator <span class="text-danger">*</span></label>
                    <div class="col-md-10 col-sm-6">
                        <select name="ref_jenis_indikator" id="ref_jenis_indikator" class="form-control" required>
                            <option value="">-- Pilih Jenis Indikator --</option>
                            <?php foreach ($data['jenis_indikator']->result() as $j):
                                $selectedJenis = isset($data['result']['ref_jenis_indikator']) && $data['result']['ref_jenis_indikator'] == $j->id ? 'selected' : '';
                            ?>
                                <option value="<?= $j->id; ?>" <?= $selectedJenis; ?>><?= $j->nama; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-sm-3 label-align" for="periode">Periode <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10 col-sm-6">
                        <select name="periode[]" id="periode" class="form-control" multiple>
                            <?php foreach (bulanIndo() as $key => $val): ?>
                                <option value="<?= $key; ?>"
                                    <?= in_array($key, $data['result']['periode'] ?? []) ? 'selected' : ''; ?>>
                                    <?= $val; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row d-flex justify-content-end">
                    <button type="button" onclick="backStep()" class="btn btn-danger">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan & Lanjutkan</button>
                </div>
                <?= form_close(); ?>
            </div>
            <div id="step-3" class="content" style="display: none;">
                <h2 class="StepTitle">Step 3 Review</h2>
                <hr>
                <table class="table table-bordered">
                    <tr>
                        <td colspan="3" class="bg-light text-dark"><b>Referensi</b></td>
                    </tr>
                    <tr>
                        <td width="20%">Tujuan</td>
                        <td width="2%">:</td>
                        <td><?= @$this->indikator->getReferensiTujuan($data['result']['ref_tujuan'])->row()->nama; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Sasaran</td>
                        <td width="2%">:</td>
                        <td><?= @$this->indikator->getReferensiSasaran($data['result']['ref_sasaran'])->row()->nama; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Program</td>
                        <td width="2%">:</td>
                        <td><?= @$this->indikator->getReferensiProgram($data['result']['ref_program'])->row()->nama; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Kegiatan</td>
                        <td width="2%">:</td>
                        <td><?= @$this->indikator->getReferensiKegiatan($data['result']['ref_kegiatan'])->row()->nama; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Sub Kegiatan</td>
                        <td width="2%">:</td>
                        <td><?= @$this->indikator->getReferensiSubKegiatan($data['result']['ref_sub_kegiatan'])->row()->nama; ?></td>
                    </tr>
                </table>
                <table class="table table-bordered">
                    <tr>
                        <td colspan="3" class="bg-light text-dark"><b>Indikator</b></td>
                    </tr>
                    <tr>
                        <td width="20%">Nama Indikator</td>
                        <td width="2%">:</td>
                        <td><?= $data['result']['indikator'] ?? ''; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Jenis Indikator</td>
                        <td width="2%">:</td>
                        <td><?= $this->indikator->getJenisIndikator($data['result']['ref_jenis_indikator'])->row()->nama ?? ''; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Periode</td>
                        <td width="2%">:</td>
                        <td><?= periodeToBulan($data['result']['periode']); ?></td>
                    </tr>
                </table>
                <?= form_open(base_url('app/indikator/simpan'), ['id' => 'formStep3'], [
                    'ref' => $data['ref'],
                    'nama_indikator' => $data['result']['indikator'],
                    'jenis_indikator' => $data['result']['ref_jenis_indikator'],
                    'periode' => implode(',', $data['result']['periode']),
                    'ref_tujuan' => $data['result']['ref_tujuan'] ?? null,
                    'ref_sasaran' => $data['result']['ref_sasaran'] ?? null,
                    'ref_program' => $data['result']['ref_program'] ?? null,
                    'ref_kegiatan' => $data['result']['ref_kegiatan'] ?? null,
                    'ref_sub_kegiatan' => $data['result']['ref_sub_kegiatan'] ?? null,
                ]); ?>
                <div class="form-group row d-flex justify-content-end">
                    <button type="button" onclick="backStep()" class="btn btn-danger">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
                <?= form_close(); ?>
            </div>
            <div id="step-4" class="content" style="display: none;">
                <h2 class="StepTitle">Step 4 Final</h2>
                <hr>
                <?php if (isset($_GET['status']) && $_GET['status'] === 'berhasil'): ?>
                    <div class="alert alert-success">
                        <h4><i class="fa fa-check-circle mr-2"></i>Indikator Kinerja Berhasil Disimpan!</h4>
                        <p>Data indikator kinerja telah berhasil disimpan ke dalam sistem. Anda dapat menambahkan indikator baru atau kembali ke daftar indikator.</p>
                        <hr>
                        <button type="button" class="btn btn-primary" onclick="nextStep('<?= base_url('app/indikator/baru') ?>')"><i class="fa fa-plus mr-2"></i>Tambah Indikator Baru</button>
                        <button type="button" class="btn btn-secondary" onclick="nextStep('<?= base_url('app/indikator') ?>')"><i class="fa fa-list mr-2"></i>Daftar Indikator</button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['status']) && $_GET['status'] === 'gagal'): ?>
                    <div class="alert alert-danger">
                        <h4><i class="fa fa-times-circle mr-2"></i>Gagal Menyimpan Indikator Kinerja!</h4>
                        <p>Terjadi kesalahan saat menyimpan data indikator kinerja. Silakan coba lagi.</p>
                        <hr>
                        <button type="button" class="btn btn-danger" onclick="backStep()"><i class="fa fa-arrow-left mr-2"></i>Kembali</button>
                        <button type="button" class="btn btn-secondary" onclick="nextStep('<?= base_url('app/indikator') ?>')"><i class="fa fa-list mr-2"></i>Daftar Indikator</button>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<style>
    .actionBar {
        display: none;
    }
</style>

<script>
    function nextStep(path) {
        return (window.location.href = path);
    }

    function backStep() {
        return window.history.back();
    }

    // Onload
    $(function() {
        var getStep = urlParams.get("step");
        if (getStep == "") {
            isStep = 0;
        } else {
            isStep = getStep;
        }
        $("#wizard_verticle").smartWizard({
            // Properties
            selected: isStep,
            keyNavigation: false, // Enable/Disable key navigation(left and right keys are used if enabled)
            enableAllSteps: false, // Enable/Disable all steps on first load
            transitionEffect: "slide", // Effect on navigation, none/fade/slide/slideleft
            contentURL: null, // specifying content url enables ajax content loading
            contentURLData: null, // override ajax query parameters
            contentCache: false, // cache step contents, if false content is fetched always from ajax url
            cycleSteps: false, // cycle step navigation
            enableFinishButton: false, // makes finish button enabled always
            hideButtonsOnDisabled: true, // when the previous/next/finish buttons are disabled, hide them instead
            errorSteps: [], // array of step numbers to highlighting as error steps
            labelNext: "Selanjutnya", // label for Next button
            labelPrevious: "Sebelumnya", // label for Previous button
            labelFinish: "Selesai", // label for Finish button
            noForwardJumping: true,
            ajaxType: "POST",
            // Events
            onLeaveStep: null, // triggers when leaving a step
            onShowStep: null, // triggers when showing a step
            onFinish: null, // triggers when Finish button is clicked
            buttonOrder: ["next", "prev", "finish"], // button order, to hide a button remove it from the list
        });

        let formStep1 = $("form#formStep1");
        let selectReferensi = formStep1.find("select#referensi");

        selectReferensi.on("change", function() {
            let val = $(this).val();
            if (val == "") {
                return $.notify('Referensi indikator wajib dipilih', {
                    timer: 800,
                    delay: 100,
                    type: "warning"
                });
            }
        });

        let formStep2 = $("form#formStep2");

        // Handle parent_kegiatan change
        formStep2.find("select#parent_kegiatan").on("change", async function() {
            let kegiatanId = $(this).val();
            let subKegiatanSelect = formStep2.find("select#ref_sub_kegiatan");

            if (kegiatanId !== "") {
                try {
                    let response = await fetch(`<?= base_url('app/indikator/getSubKegiatan/') ?>${kegiatanId}`);

                    if (!response.ok) {
                        throw new Error("Gagal mengambil data sub kegiatan");
                    }

                    let data = await response.json(); // karena server kirim HTML (bukan JSON)
                    subKegiatanSelect.html(data);
                } catch (error) {
                    console.error(error);
                    subKegiatanSelect.html('<option value="">-- Gagal memuat Sub Kegiatan --</option>');
                }
            } else {
                subKegiatanSelect.html('<option value="">-- Pilih Sub Kegiatan --</option>');
            }
        });

        $("select[name='referensi'],select[name='ref_tujuan'],select[name='ref_sasaran'],select[name='ref_program'],select[name='parent_kegiatan'],select[name='ref_kegiatan'],select[name='ref_sub_kegiatan'], select[name='ref_jenis_indikator']").select2();
        $("select#periode").select2({
            placeholder: '-- Pilih Periode Indikator --',
            tags: false,
            allowClear: true,
            tokenSeparators: [',', ' ']
        });

        $('textarea#indikator').autocomplete({
            serviceUrl: '<?= base_url('app/indikator/autocomplete/indikator') ?>',
            minChars: 3,
            deferRequestBy: 300,
        });

        let formStep3 = $("form#formStep3");
        formStep3.on("submit", async function(e) {
            e.preventDefault(); // Prevent default form submission
            const url = $(this).attr('action');
            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]'); // ambil tombol submit
            const originalText = submitBtn.html(); // simpan teks asli tombol

            try {
                // Ubah tombol jadi loading dan nonaktifkan
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (result.success) {
                    $.notify(result.message, {
                        timer: 800,
                        delay: 100,
                        type: "success"
                    });

                    // Redirect (gunakan dari server jika ada, atau fallback ke default)
                    window.location.href = result.redirect_url || '<?= base_url('app/indikator/baru?step=3&status=gagal') ?>';
                } else {
                    $.notify(result.message || 'Gagal menyimpan data indikator.', {
                        timer: 800,
                        delay: 100,
                        type: "danger"
                    });
                }
            } catch (error) {
                console.error('Terjadi kesalahan:', error);
                $.notify('Terjadi kesalahan pada koneksi server.', {
                    timer: 800,
                    delay: 100,
                    type: "danger"
                });
            } finally {
                // Kembalikan tombol ke kondisi semula
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    })
</script>