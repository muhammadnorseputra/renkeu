<div class="clearfix"></div>
<div class="alert alert-success" role="alert">
    Selamat datang kembali <strong><?= $this->session->userdata('nama') ?></strong> [ Login as <b><?= strtolower($this->session->userdata('role')); ?></b> ] <br> 
    <?= $this->users->part_detail($this->session->userdata('part')); ?> <br>   
</div>