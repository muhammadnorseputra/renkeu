<div class="row">
    <div class="col-md-12">
    <?= form_open(base_url("app/target/ubah_proses"), ['id' => 'formIndikator', 'data-parsley-validate' => ''], ['id' => $id_indikator]); ?>
        <div class="form-group">
            <label for="nama">Nama Indikator <span class="text-danger">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" required value="<?= $row->nama ?>">
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="persentase">Peserntase % <span class="text-danger">*</span></label>
                    <input type="number" name="persentase" id="persentase" class="form-control" required value="<?= $row->kinerja_persentase ?>">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="jumlah_eviden">Jumlah Eviden <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_eviden" id="jumlah_eviden" class="form-control" required value="<?= $row->kinerja_eviden ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="keterangan_eviden">Keterangan Eviden <span class="text-danger">*</span></label>
                    <input type="text" name="keterangan_eviden" id="keterangan_eviden" class="form-control" required value="<?= $row->keterangan_eviden ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
                <button type="button" class="btn btn-danger rounded-0" onclick="window.location.href='<?= base_url('app/target') ?>'"><i class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
        </div>    
    <?= form_close(); ?>
    </div>
</div>

<script>
    $(function(){
	let $form = $("form#formIndikator");
        $form.on("submit", function (e) {
            e.preventDefault();
            $data = $(this).serialize();
            if ($(this).parsley().isValid()) {
                $.post(
                    $(this).attr('action'),
                    $data,
                    (response) => {
                        if (response === 200) {
                            window.location.replace(`${_uri}/app/target`);
                        }
                    },
                    "json"
                );
            }
        });
    })
</script>