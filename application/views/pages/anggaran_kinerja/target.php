
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa fa-dollar mr-2"></i> Target Anggaran & Kinerja</h3>
    </div>
</div>

<div class="clearfix"></div>

<div class="x_panel">
    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th rowspan="2" class="align-middle">No</th>
                <th rowspan="2" class="align-middle sticky-col">Program/Kegiatan/Sub Kegiatan</th>
                <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                <th colspan="2">Target</th>
            </tr>
            <tr class="text-center">
                <th>Anggaran (Rp)</th>
                <th>Kinerja</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no_level_1 = 1; 
            foreach($programs->result() as $program): 
            $indikator_program = $this->target->getIndikator(['fid_program' => $program->id]);
            ?>
            <tr class="bg-warning">
                <td class="text-center align-middle"><?= $no_level_1 ?></td>
                <td class="align-middle"><?= $program->nama ?></td>
                <td class="text-left text-nowrap">
                    <?php  
                        if($indikator_program->num_rows() > 0):
                            foreach($indikator_program->result() as $ip):
                    ?>
                        <?= $ip->nama ?>
                        <hr>
                    <?php 
                            endforeach; 
                        endif;
                    ?>
                    <button class="btn btn-sm btn-light m-0 rounded" onclick="TambahIndikator('Program','<?= $program->id ?>','<?= base_url('app/target/tambah_indikator/ref_programs') ?>')"><i class="fa fa-plus"></i> Tambah</button>
                </td>
                <td class="align-middle text-right">
                    <?= @nominal($this->target->getAlokasiPaguProgram($program->id)->row()->total_pagu_awal); ?>
                </td>
                <td>
                <?php  
                        if($indikator_program->num_rows() > 0):
                            foreach($indikator_program->result() as $ip):
                    ?>
                        <?= $ip->kinerja_persentase ?>%
                        <hr>
                    <?php 
                            endforeach; 
                        endif;
                    ?>
                </td>
            </tr>
                <?php
                if($this->session->userdata('role') === 'SUPER_ADMIN'):
                $kegiatans = $this->target->kegiatans($program->id);
                else:
                $kegiatans = $this->target->kegiatans($program->id, $this->session->userdata('part'));
                endif;
                
                $no_level_2 = 1;
                foreach($kegiatans->result() as $kegiatan): 
                $indikator_kegiatan = $this->target->getIndikator(['fid_kegiatan' => $kegiatan->id]);
                ?>
                <tr class="bg-info text-white">
                    <td class="text-center align-middle"><?= $no_level_1.".".$no_level_2 ?></td>
                    <td class="align-middle"><?= $kegiatan->nama ?></td>
                    <td class="text-left text-nowrap">
                        <?php  
                            if($indikator_kegiatan->num_rows() > 0):
                                foreach($indikator_kegiatan->result() as $ik):
                        ?>
                        <?= $ik->nama ?>
                        <hr>
                        <?php 
                                endforeach; 
                            endif;
                        ?>
                        <button class="btn btn-sm btn-light m-0 rounded" onclick="TambahIndikator('Kegiatan','<?= $kegiatan->id ?>','<?= base_url('app/target/tambah_indikator/ref_kegiatans') ?>')"><i class="fa fa-plus"></i> Tambah</button>
                    </td>
                    <td class="align-middle text-right">
                        <?= @nominal($this->target->getAlokasiPaguKegiatan($kegiatan->id)->row()->total_pagu_awal); ?>
                    </td>
                    <td>
                    <?php  
                            if($indikator_kegiatan->num_rows() > 0):
                                foreach($indikator_kegiatan->result() as $ik):
                        ?>
                            <?php
                            if($ik->kinerja_persentase === "0") {
                                echo $ik->kinerja_eviden." ".$ik->keterangan_eviden;
                            } else {
                                echo $ik->kinerja_persentase."%";
                            }
                            ?>
                            <hr>
                        <?php 
                                endforeach; 
                            endif;
                        ?>
                    </td>
                </tr>
                    <?php
                    $sub_kegiatans = $this->target->sub_kegiatans($kegiatan->id);
                    $no_level_3 = 1;
                    foreach($sub_kegiatans->result() as $sub_kegiatan): 
                    $indikator_sub_kegiatan = $this->target->getIndikator(['fid_sub_kegiatan' => $sub_kegiatan->id]);
                    ?>
                    <tr>
                        <td class="text-center align-middle"><?= $no_level_1.".".$no_level_2.".".$no_level_3 ?></td>
                        <td class="align-middle"><?= $sub_kegiatan->nama ?></td>
                        <td class="text-left text-nowrap">
                        <?php  
                            if($indikator_sub_kegiatan->num_rows() > 0):
                                foreach($indikator_sub_kegiatan->result() as $isk):
                        ?>
                            <?= $isk->nama ?>
                            <hr>
                        <?php 
                                endforeach; 
                            endif;
                        ?>
                            <button class="btn btn-sm btn-primary m-0 rounded" onclick="TambahIndikator('Sub Kegiatan','<?= $sub_kegiatan->id ?>','<?= base_url('app/target/tambah_indikator/ref_sub_kegiatans') ?>')"><i class="fa fa-plus"></i> Tambah</button>
                        </td>
                        <td class="text-right align-middle"><?= @nominal($this->target->getPagu(['fid_sub_kegiatan' => $sub_kegiatan->id])->total_pagu_awal); ?></td>
                        <td class="text-nowrap">
                        <?php  
                            if($indikator_sub_kegiatan->num_rows() > 0):
                                foreach($indikator_sub_kegiatan->result() as $isk):
                        ?>
                            <?php
                            if($isk->kinerja_persentase === "0") {
                                echo $isk->kinerja_eviden." ".$isk->keterangan_eviden;
                            } else {
                                echo $isk->kinerja_persentase."%";
                            }
                            ?>
                            <hr>
                        <?php 
                                endforeach; 
                            endif;
                        ?>
                        </td>
                    </tr>
                    <?php 
                    $no_level_3++;
                    endforeach; 
                    ?>
                <?php 
                $no_level_2++;
                endforeach; 
                ?>
            <?php
            $no_level_1++; 
            endforeach; 
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Indikator -->
<div class="modal fade modal-indikator" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open('#', ['id' => 'formIndikator', 'data-parsley-validate' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Processing ...</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama">Nama Indikator <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="persentase">Peserntase % <span class="text-danger">*</span></label>
                            <input type="number" name="persentase" id="persentase" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jumlah_eviden">Jumlah Eviden <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_eviden" id="jumlah_eviden" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan_eviden">Keterangan Eviden <span class="text-danger">*</span></label>
                            <input type="text" name="keterangan_eviden" id="keterangan_eviden" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
