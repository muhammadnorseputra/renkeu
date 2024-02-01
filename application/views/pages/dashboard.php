<div class="clearfix"></div>
<div class="alert alert-success" role="alert">
    Selamat datang kembali <strong><?= $this->session->userdata('nama') ?></strong> [ Login as <b><?= strtolower($this->session->userdata('role')); ?></b> ] <br>
    <?= $this->users->part_detail($this->session->userdata('part')); ?> <br>
</div>
<div class="row">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-caret-square-o-right"></i>
            </div>
            <div class="count">179</div>
            <p>Realisasi kegiatan.</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-comments-o"></i>
            </div>
            <div class="count">Rp. 1.300.304.000</div>
            <p>Target alokasi pagu kegiatan</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-sort-amount-desc"></i>
            </div>
            <div class="count">179</div>

            <h3>New Sign ups</h3>
            <p>Lorem ipsum psdea itgum rixt.</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-check-square-o"></i>
            </div>
            <div class="count">179</div>

            <h3>New Sign ups</h3>
            <p>Lorem ipsum psdea itgum rixt.</p>
        </div>
    </div>
</div>