<?php 
$notify = $this->notify->getNotify('t_notify', ['mode' => 'GLOBAL', 'is_aktif' => 'Y']);
$row    = $notify->row();
$type   = strtolower(isset($row->type) ? $row->type : '');
$icon   = strtolower(isset($row->type_icon) ? $row->type_icon : '');
$msg    = (isset($row->message) ? $row->message : '');
if(isset($row)):
?>
	<div class="alert alert-<?= $type ?> border-0 mb-0 fade show rounded-0" role="alert">
		<span class="alert-icon mr-1"><?= $icon ?></span>
		<span class="alert-text truncate"><?= $msg ?></span> 
	</div>
<?php endif; ?>