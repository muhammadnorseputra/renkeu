
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa fa-dollar mr-2"></i> Target Anggaran & Kinerja</h3>
    </div>
</div>

<div class="clearfix"></div>

<div class="x_panel">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th rowspan="2" class="align-middle">No</th>
                    <th rowspan="2" class="align-middle sticky-col">Program/Kegiatan/Sub Kegiatan</th>
                    <th rowspan="2"></th>
                    <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                    <th colspan="2">Target</th>
                    <th colspan="2">Aksi</th>
                </tr>
                <tr class="text-center">
                    <th>Anggaran (Rp)</th>
                    <th class="align-middle">Kinerja</th>
                    <th class="align-middle">U</th>
                    <th class="align-middle">H</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_level_1 = 1; 
                foreach($programs->result() as $program): 
                $indikator_program = $this->target->getIndikator(['fid_program' => $program->id]);
                    $tr = "";
                    // if($indikator_program->num_rows() > 0):
                        $indikator = $indikator_program->result_array();
                        $toEnd = count($indikator);
                        foreach($indikator as $key => $ip):
                            $button_hapus = '<button class="btn btn-danger btn-sm m-0" id="HapusIndikator" data-id="'.$ip['id'].'" data-label="Program" type="button"><i class="fa fa-trash"></i></button>';
                            $button_ubah = '<button class="btn btn-info btn-sm m-0" onclick="window.location.replace(\''.base_url("app/target/ubah/".$ip['indikator_id']."/ref_programs").'\')" type="button"><i class="fa fa-pencil"></i></button>';
                            if($ip['persentase'] === "0") {
                                $indikator_input = $ip['eviden_jumlah']." ".$ip['eviden_jenis'];
                            } else {
                                $indikator_input = $ip['persentase']."%";
                            }
                            $rowspan = $toEnd++;
                            if (0 === --$toEnd) { //last
                                $tr .= "
                                <tr class='bg-warning'>
                                    <td class='align-middle'>".$ip['nama']."</td>
                                    <td class='align-middle text-center'>".$indikator_input."</td>
                                    <td class='align-middle'>".$button_ubah."</td>
                                    <td class='align-middle'>".$button_hapus."</td>
                                </tr>";
                                } elseif($key === 0) { //first
                                $tr .= "
                                <tr class='bg-warning'>
                                    <td class='align-middle'>".$ip['nama']."</td>
                                    <td rowspan='".$rowspan."' class='align-middle text-right'>".@nominal($this->target->getAlokasiPaguProgram($program->id)->row()->total_pagu_awal)."</td>
                                    <td class='align-middle text-center'>".$indikator_input."</td>
                                    <td class='align-middle'>".$button_ubah."</td>
                                    <td class='align-middle'>".$button_hapus."</td>
                                </tr>";
                                } else { //middle
                                $tr .= "
                                <tr class='bg-warning'>
                                    <td class='align-middle'>".$ip['nama']."</td>
                                    <td class='align-middle text-center'>".$indikator_input."</td>
                                    <td class='align-middle'>".$button_ubah."</td>
                                    <td class='align-middle'>".$button_hapus."</td>
                                </tr>";
                                }
                        endforeach; 
                    // endif;
                ?>
                <tr class="bg-warning">
                    <td class="text-center align-middle" rowspan="<?= $toEnd+1 ?>"><?= $no_level_1 ?></td>
                    <td class="align-middle" rowspan="<?= $toEnd+1 ?>"><?= $program->nama ?></td>
                    <td class="align-middle text-center" rowspan="<?= $toEnd+1 ?>"><button class="btn btn-sm btn-light m-0 rounded" id="TambahIndikator" data-id="<?= $program->id ?>" data-label="Program" data-ref="ref_programs"><i class="fa fa-plus"></i></button></td>
                    <?= $tr ?>
                </tr>
                    <?php
                    if($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN'):
                    $kegiatans = $this->target->kegiatans($program->id);
                    else:
                    $kegiatans = $this->target->kegiatans($program->id, $this->session->userdata('part'));
                    endif;
                    
                    $no_level_2 = 1;
                    foreach($kegiatans->result() as $kegiatan): 
                    $indikator_kegiatan = $this->target->getIndikator(['fid_kegiatan' => $kegiatan->id]);
                    $tr = "";
                    // if($indikator_kegiatan->num_rows() > 0):
                        $indikator = $indikator_kegiatan->result_array();
                        $toEnd = count($indikator);
                        foreach($indikator as $key => $ik):
                            $button_hapus = '<button class="btn btn-danger btn-sm m-0" id="HapusIndikator" data-id="'.$ik['id'].'" data-label="Kegiatan" type="button"><i class="fa fa-trash"></i></button>';
                            $button_ubah = '<button class="btn btn-info btn-sm m-0" onclick="window.location.replace(\''.base_url("app/target/ubah/".$ik['indikator_id']."/ref_kegiatans").'\')" type="button"><i class="fa fa-pencil"></i></button>';
                            if($ik['persentase'] === "0") {
                                $indikator_input = $ik['eviden_jumlah']." ".$ik['eviden_jenis'];
                            } else {
                                $indikator_input = $ik['persentase']."%";
                            }
                            $rowspan = $toEnd++;
                            if (0 === --$toEnd) { //last
                                $tr .= "
                                <tr class='bg-info text-white'>
                                    <td class='align-middle'>".$ik['nama']."</td>
                                    <td class='align-middle text-center'>".$indikator_input."</td>
                                    <td class='align-middle'>".$button_ubah."</td>
                                    <td class='align-middle'>".$button_hapus."</td>
                                </tr>";
                                } elseif($key === 0) { //first
                                $tr .= "
                                <tr class='bg-info text-white'>
                                    <td class='align-middle'>".$ik['nama']."</td>
                                    <td rowspan='".$rowspan."' class='align-middle text-right'>".@nominal($this->target->getAlokasiPaguKegiatan($kegiatan->id)->row()->total_pagu_awal)."</td>
                                    <td class='align-middle text-center'>".$indikator_input."</td>
                                    <td class='align-middle'>".$button_ubah."</td>
                                    <td class='align-middle'>".$button_hapus."</td>
                                </tr>";
                                } else { //middle
                                $tr .= "
                                <tr class='bg-info text-white'>
                                    <td class='align-middle'>".$ik['nama']."</td>
                                    <td class='align-middle text-center'>".$indikator_input."</td>
                                    <td class='align-middle'>".$button_ubah."</td>
                                    <td class='align-middle'>".$button_hapus."</td>
                                </tr>";
                                }
                        endforeach; 
                    // endif;
                    ?>
                    <tr class="bg-info text-white">
                        <td class="text-center align-middle" rowspan="<?= $toEnd+1 ?>"><?= $no_level_1.".".$no_level_2 ?></td>
                        <td class="align-middle" rowspan="<?= $toEnd+1 ?>"><?= $kegiatan->nama ?></td>
                        <td class="align-middle text-center" rowspan="<?= $toEnd+1 ?>"><button class="btn btn-sm btn-light m-0 rounded" id="TambahIndikator" data-id="<?= $kegiatan->id ?>" data-label="Kegiatan" data-ref="ref_kegiatans"><i class="fa fa-plus"></i></button></td>
                        <?= $tr ?>
                    </tr>
                        <?php
                        $sub_kegiatans = $this->target->sub_kegiatans($kegiatan->id);
                        $no_level_3 = 1;
                        foreach($sub_kegiatans->result() as $sub_kegiatan): 
                        $indikator_sub_kegiatan = $this->target->getIndikator(['fid_sub_kegiatan' => $sub_kegiatan->id]);
                        $tr = "";
                        // if($indikator_sub_kegiatan->num_rows() > 0):
                            $indikator = $indikator_sub_kegiatan->result_array();
                            $toEnd = count($indikator);
                            foreach($indikator as $key => $isk):
                                $button_hapus = '<button class="btn btn-danger btn-sm m-0" id="HapusIndikator" data-id="'.$isk['id'].'" data-label="Sub Kegiatan" type="button"><i class="fa fa-trash"></i></button>';
                                $button_ubah = '<button class="btn btn-info btn-sm m-0" onclick="window.location.replace(\''.base_url("app/target/ubah/".$isk['indikator_id']."/ref_sub_kegiatans").'\')" type="button"><i class="fa fa-pencil"></i></button>';
                                if($isk['persentase'] === "0") {
                                    $indikator_input = $isk['eviden_jumlah']." ".$isk['eviden_jenis'];
                                } else {
                                    $indikator_input = $isk['persentase']."%";
                                }
                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>".$isk['nama']."</td>
                                        <td class='align-middle text-center'>".$indikator_input."</td>
                                        <td class='align-middle'>".$button_ubah."</td>
                                        <td class='align-middle'>".$button_hapus."</td>
                                    </tr>";
                                    } elseif($key === 0) { //first
                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>".$isk['nama']."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".@nominal($this->target->getPagu(['fid_sub_kegiatan' => $sub_kegiatan->id])->total_pagu_awal)."</td>
                                        <td class='align-middle text-center'>".$indikator_input."</td>
                                        <td class='align-middle'>".$button_ubah."</td>
                                        <td class='align-middle'>".$button_hapus."</td>
                                    </tr>";
                                    } else { //middle
                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>".$isk['nama']."</td>
                                        <td class='align-middle text-center'>".$indikator_input."</td>
                                        <td class='align-middle'>".$button_ubah."</td>
                                        <td class='align-middle'>".$button_hapus."</td>
                                    </tr>";
                                    }
                            endforeach; 
                        // endif;
                        ?>
                        <tr>
                            <td class="text-center align-middle" rowspan="<?= $toEnd+1 ?>"><?= $no_level_1.".".$no_level_2.".".$no_level_3 ?></td>
                            <td class="align-middle" rowspan="<?= $toEnd+1 ?>"><?= $sub_kegiatan->nama ?></td>
                            <td class="align-middle text-center" rowspan="<?= $toEnd+1 ?>"><button class="btn btn-sm btn-primary m-0 rounded" id="TambahIndikator" data-id="<?= $sub_kegiatan->id ?>" data-label="Sub Kegiatan" data-ref="ref_sub_kegiatans"><i class="fa fa-plus"></i></button></td>
                            <?= $tr ?>
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
</div>

<!-- Modal Tambah Indikator -->
<div class="modal fade modal-indikator" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url("app/target/tambah_indikator"), ['id' => 'formIndikator', 'data-parsley-validate' => '']); ?>
        <input type="hidden" name="id">
        <input type="hidden" name="ref">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
