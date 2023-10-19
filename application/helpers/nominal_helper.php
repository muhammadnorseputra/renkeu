<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('nominal')) {
	function nominal($angka){
		$jd = number_format($angka, 0, ',', '.');
		return $jd;
	}
}

if (!function_exists('cekValue')) {
	function cekValue($value, $default = null){
		$jd = isset($value) ? $value : $default;
		return $jd;
	}
}
//RUN SCRIPT
// $this->load->helper('nominal');
// echo nominal('300000');
?>