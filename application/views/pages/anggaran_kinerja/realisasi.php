<div class="page-title">
    <div class="title_left">
        <h3><i class="fa fa-dollar mr-2"></i> Realisasi Anggaran & Kinerja</h3>
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
                    <th colspan="2">Realisasi</th>
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
                $indikator_program = $this->realisasi->getIndikator(['fid_program' => $program->id]);
                        $tr = "";
                        if($indikator_program->num_rows() > 0):
                            $indikator = $indikator_program->result_array();
                            $toEnd = count($indikator);
                            foreach($indikator as $key => $ip):
                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>".$ip['nama']."</td>
                                        <td class='align-middle text-center'>".$ip['id']."</td>
                                    </tr>";
                                  } elseif($key === 0) { //first
                                    $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>".$ip['nama']."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>abc</td>
                                        <td class='align-middle text-center'>".$ip['id']."</td>
                                    </tr>";
                                  } else { //middle
                                    $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>".$ip['nama']."</td>
                                        <td class='align-middle text-center'>".$ip['id']."</td>
                                    </tr>";
                                  }
                            endforeach; 
                        endif;
                ?>
                <tr class="bg-warning">
                    <td class="text-center align-middle" rowspan="<?= $toEnd+1 ?>"><?= $no_level_1 ?></td>
                    <td class="align-middle" rowspan="<?= $toEnd+1 ?>"><?= $program->nama ?> </td>
                    <?= $tr ?>
                </tr>
                    <?php
                    if($this->session->userdata('role') === 'SUPER_ADMIN'):
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
                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>".$ik['nama']."</td>
                                        <td class='align-middle text-center'>".$ik['id']."</td>
                                    </tr>";
                                  } elseif($key === 0) { //first
                                    $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>".$ik['nama']."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>abc</td>
                                        <td class='align-middle text-center'>".$ik['id']."</td>
                                    </tr>";
                                  } else { //middle
                                    $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>".$ik['nama']."</td>
                                        <td class='align-middle text-center'>".$ik['id']."</td>
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
                        if($indikator_sub_kegiatan->num_rows() > 0):
                            $indikator_sub = $indikator_sub_kegiatan->result_array();
                            $toEnd = count($indikator_sub);
                            foreach($indikator_sub as $key => $isk):
                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>".$isk['nama']."</td>
                                        <td class='align-middle text-center'>".$isk['id']."</td>
                                    </tr>";
                                  } elseif($key === 0) { //first
                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>".$isk['nama']."</td>
                                        <td rowspan='".$rowspan."' class='align-middle text-right'>abc</td>
                                        <td class='align-middle text-center'>".$isk['id']."</td>
                                    </tr>";
                                  } else { //middle
                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>".$isk['nama']."</td>
                                        <td class='align-middle text-center'>".$isk['id']."</td>
                                    </tr>";
                                  }
                            endforeach; 
                        endif;
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