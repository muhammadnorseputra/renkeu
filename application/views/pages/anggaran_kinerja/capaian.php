<div class="page-title">
    <div class="title_left">
        <h3><i class="fa fa-percent mr-2"></i> Persentase Capaian</h3>
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
                    <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                    <th colspan="2" class="align-middle">Target</th>
                    <th colspan="2" class="align-middle">Realisasi</th>
                    <th colspan="2">Persentase Capaian %</th>
                </tr>
                <tr class="text-center">
                    <th>Anggaran (Rp)</th>
                    <th>Kinerja %</th>
                    <th>Anggaran (Rp)</th>
                    <th>Kinerja %</th>
                    <th class="align-middle">Anggaran</th>
                    <th class="align-middle">Kinerja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_level_1 = 1; 
                foreach($programs->result() as $program): 
                $indikator_program = $this->realisasi->getIndikator(['fid_program' => $program->id]);
                        $tr = "";
                        // if($indikator_program->num_rows() > 0):
                            $indikator = $indikator_program->result_array();
                            $toEnd = count($indikator);
                            foreach($indikator as $key => $ip):
                                // Target
                                if($ip['kinerja_persentase'] === "0") {
                                    $indikator_input_count = $ip['kinerja_eviden'];
                                    $indikator_input_view = $indikator_input_count." ".$ip['keterangan_eviden'];
                                } else {
                                    $indikator_input_count = $ip['kinerja_persentase'];
                                    $indikator_input_view = $ip['kinerja_persentase']."%";
                                }

                                $target_anggaran = $this->target->getAlokasiPaguProgram($program->id)->row()->total_pagu_awal;
                                $target_kinerja = $indikator_input_count;

                                // Realisasi
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId(1, $ip['id'])->row();
                                if($realisasi->persentase === "0") {
                                    $sum_realisasi_count = $realisasi->eviden;
                                    $sum_realisasi_view = $realisasi->eviden." ".$realisasi->eviden_jenis;
                                } elseif($realisasi->eviden === "0") {
                                    $sum_realisasi_count = $realisasi->persentase;
                                    $sum_realisasi_view = $realisasi->persentase."%";
                                } else {
                                    $sum_realisasi_count = 0;
                                    $sum_realisasi_view = "-";
                                }

                                $realisasi_anggaran = $this->realisasi->getRealisasiProgram(1, $program->id);
                                $realisasi_kinerja = $sum_realisasi_count;

                                // Capaian
                                @$capaian_anggaran = round(($realisasi_anggaran/$target_anggaran)*100, 2);
                                @$capaian_kinerja = round(($realisasi_kinerja/$target_kinerja)*100, 2);


                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>".$ip['nama']."</td>
                                        <td class='align-middle text-center'>".$sum_realisasi."</td>
                                    </tr>";
                                  } elseif($key === 0) { //first
                                    $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>".$ip['nama']."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".nominal($target_anggaran)."</td>
                                        <td class='align-middle text-center'>".$indikator_input_view."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".nominal($realisasi_anggaran)."</td>
                                        <td class='align-middle text-center'>".$sum_realisasi_view."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".$capaian_anggaran." (%)</td>
                                        <td class='align-middle text-center'>".$capaian_kinerja." (%)</td>
                                    </tr>";
                                  } else { //middle
                                    $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>".$ip['nama']."</td>
                                        <td class='align-middle text-center'>".$sum_realisasi."</td>
                                    </tr>";
                                  }
                            endforeach; 
                        // endif;
                ?>
                <tr class="bg-warning">
                    <td class="text-center align-middle" rowspan="<?= $toEnd+1 ?>"><?= $no_level_1 ?></td>
                    <td class="align-middle" rowspan="<?= $toEnd+1 ?>"><?= $program->nama ?> </td>
                    <?= $tr ?>
                </tr>
                    <?php
                    if($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN'):
                    $kegiatans = $this->realisasi->kegiatans($program->id);
                    else:
                    $kegiatans = $this->realisasi->kegiatans($program->id, $this->session->userdata('part'));
                    endif;
                    
                    $no_level_2 = 1;
                    foreach($kegiatans->result() as $kegiatan): 
                    $indikator_kegiatan = $this->realisasi->getIndikator(['fid_kegiatan' => $kegiatan->id]);
                        $tr = "";
                        // if($indikator_kegiatan->num_rows() > 0):
                            $indikator_keg = $indikator_kegiatan->result_array();
                            $toEnd = count($indikator_keg);
                            foreach($indikator_keg as $key => $ik):
                                // Target
                                if($ik['kinerja_persentase'] === "0") {
                                    $indikator_input_count = $ik['kinerja_eviden'];
                                    $indikator_input_view = $ik['kinerja_eviden']." ".$ik['keterangan_eviden'];
                                } else {
                                    $indikator_input_count = $ik['kinerja_persentase'];
                                    $indikator_input_view = $ik['kinerja_persentase']."%";
                                }
                                $target_anggaran = $this->target->getAlokasiPaguKegiatan($kegiatan->id)->row()->total_pagu_awal;
                                $target_kinerja = $indikator_input_count;

                                // Realisasi
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId(1, $ik['id'])->row();
                                if($realisasi->persentase === "0") {
                                    $sum_realisasi_count = $realisasi->eviden;
                                    $sum_realisasi_view = $realisasi->eviden." ".$realisasi->eviden_jenis;
                                } elseif($realisasi->eviden === "0") {
                                    $sum_realisasi_count = $realisasi->persentase;
                                    $sum_realisasi_view = $realisasi->persentase."%";
                                } else {
                                    $sum_realisasi_count = 0;
                                    $sum_realisasi_view = "-";
                                }
                                $realisasi_anggaran = $this->realisasi->getRealisasiKegiatan(1, $kegiatan->id);
                                $realisasi_kinerja = $sum_realisasi_count;

                                // Capaian
                                @$capaian_anggaran = round(($realisasi_anggaran/$target_anggaran)*100, 2);
                                @$capaian_kinerja = round(($realisasi_kinerja/$target_kinerja)*100, 2);

                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>".$ik['nama']."</td>
                                    </tr>";
                                  } elseif($key === 0) { //first
                                    $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>".$ik['nama']."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".nominal($target_anggaran)."</td>
                                        <td class='align-middle text-center'>".$indikator_input_view."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".nominal($realisasi_anggaran)."</td>
                                        <td class='align-middle text-center'>".$sum_realisasi_view."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".$capaian_anggaran." (%)</td>
                                        <td class='align-middle text-center'>".$capaian_kinerja."</td>
                                    </tr>";
                                  } else { //middle
                                    $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>".$ik['nama']."</td>
                                        <td class='align-middle text-center'>".$indikator_input_view."</td>
                                        <td class='align-middle text-center'>".$sum_realisasi_view."</td>
                                        <td class='align-middle text-center'>".$capaian_kinerja."</td>
                                    </tr>";
                                  }
                            endforeach; 
                        // endif;
                    ?>
                    <tr class="bg-info text-white">
                        <td class="text-center align-middle" rowspan="<?= $toEnd+1 ?>"><?= $no_level_1.".".$no_level_2 ?></td>
                        <td class="align-middle" rowspan="<?= $toEnd+1 ?>"><?= $kegiatan->nama ?></td>
                        <?= $tr ?>
                    </tr>
                        <?php
                        $sub_kegiatans = $this->realisasi->sub_kegiatans($kegiatan->id);
                        $no_level_3 = 1;
                        foreach($sub_kegiatans->result() as $sub_kegiatan): 
                        $indikator_sub_kegiatan = $this->realisasi->getIndikator(['fid_sub_kegiatan' => $sub_kegiatan->id]);
                        $tr = "";
                        // if($indikator_sub_kegiatan->num_rows() > 0):
                            $indikator_sub = $indikator_sub_kegiatan->result_array();
                            $toEnd = count($indikator_sub);
                            foreach($indikator_sub as $key => $isk):
                                // Target
                                if($isk['kinerja_persentase'] === "0") {
                                    $indikator_input_count = $isk['kinerja_eviden'];
                                    $indikator_input_view = $indikator_input_count." ".$isk['keterangan_eviden'];
                                } else {
                                    $indikator_input_count = $isk['kinerja_persentase'];
                                    $indikator_input_view = $isk['kinerja_persentase']."%";
                                }

                                $target_anggaran = $this->target->getPagu(['fid_sub_kegiatan' => $sub_kegiatan->id])->total_pagu_awal;
                                $target_kinerja = $indikator_input_count;

                                // Realisasi
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId(1, $isk['id'])->row();
                                if($realisasi->persentase === "0") {
                                    $sum_realisasi_count = $realisasi->eviden;
                                    $sum_realisasi_view = $realisasi->eviden." ".$realisasi->eviden_jenis;
                                } elseif($realisasi->eviden === "0") {
                                    $sum_realisasi_count = $realisasi->persentase;
                                    $sum_realisasi_view = $realisasi->persentase."%";
                                } else {
                                    $sum_realisasi_count = 0;
                                    $sum_realisasi_view = "-";
                                }

                                $realisasi_anggaran = $this->realisasi->getRealisasiSubKegiatan(1, $sub_kegiatan->id);
                                $realisasi_kinerja = $sum_realisasi_count;

                                // Capaian
                                @$capaian_anggaran = round(($realisasi_anggaran/$target_anggaran)*100, 2);
                                @$capaian_kinerja = round(($realisasi_kinerja/$target_kinerja)*100, 2);

                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>".$isk['nama']."</td>
                                        <td class='align-middle text-center'>".$sum_realisasi."</td>
                                    </tr>";
                                  } elseif($key === 0) { //first
                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>".$isk['nama']."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".nominal($target_anggaran)."</td>
                                        <td class='align-middle text-center'>".$indikator_input_view."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".nominal($realisasi_anggaran)."</td>
                                        <td class='align-middle text-center'>".$sum_realisasi_view."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>".$capaian_anggaran." (%)</td>
                                        <td class='align-middle text-center text-nowrap'>".$capaian_kinerja." (%)</td>
                                    </tr>";
                                  } else { //middle
                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>".$isk['nama']."</td>
                                        <td class='align-middle text-center'>".$sum_realisasi."</td>
                                    </tr>";
                                  }
                            endforeach; 
                        // endif;
                        ?>
                        <tr>
                            <td class="text-center align-middle" rowspan="<?= $toEnd+1 ?>"><?= $no_level_1.".".$no_level_2.".".$no_level_3 ?></td>
                            <td class="align-middle" rowspan="<?= $toEnd+1 ?>"><?= $sub_kegiatan->nama ?></td>
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