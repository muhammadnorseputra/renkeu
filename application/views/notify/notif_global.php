<?php 
$notify = $this->notify->getNotify('t_notify', ['mode' => 'GLOBAL', 'is_aktif' => 'Y']);
$row    = $notify->row();
$type   = strtolower(isset($row->type) ? $row->type : '');
$icon   = strtolower(isset($row->type_icon) ? $row->type_icon : '');
$msg    = ucwords(isset($row->message) ? substr($row->message,0,190) : '');
if($notify->num_rows() > 0):
?>
<div class="header sticky-top">
	<div class="alert alert-<?= $type ?> border-0 mb-0 fade show rounded-0 text-black" role="alert">
		<span class="alert-icon"><?= $icon ?></span>
		<span class="alert-text"><?= $msg ?></span>
	</div>
</div>
<?php endif; ?>