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

function get_only_numbers($string){
    return preg_replace("/[^0-9]/", "", $string);
}
//RUN SCRIPT
// $this->load->helper('nominal');
// echo nominal('300000');
?>