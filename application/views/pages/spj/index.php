<?php 
$tab = isset($_GET['tab']) ? $_GET['tab'] : '#inbox';
        
if(urldecode($tab) === '#inbox') {
    $inbox = 'active';
    $is_active_inbox = true;
    $is_show_inbox = "show";
} else {
    $is_show_inbox = "";
    $is_active_inbox = false;
    $inbox = '';
}
?>
<div class="row">
    <div class="col-md-12">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item float-right">
                <a class="nav-link <?= $inbox ?>" style="font-size:16px; font-weight: bold" id="inbox-tab" data-toggle="tab" href="#inbox" role="tab" aria-controls="inbox" aria-selected="<?= $is_active_inbox ?>"><i class="fa fa-inbox mr-2"></i>Utama</a>
            </li>
        </ul>
        <div class="x_panel" style="border-top:0">
            <div class="x_content">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane <?= $inbox ?> <?= $is_show_inbox ?>" id="inbox" role="tabpanel" aria-labelledby="inbox-tab"></div>
                </div>
            </div>
        </div>
    </div>
</div>