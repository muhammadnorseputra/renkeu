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

if (! function_exists('terbilang')) {
	function terbilang($number)
	{
		if (!is_numeric($number) || $number == 0) {
			return 'Nol rupiah';
		}

		$number = abs((int)$number);

		$words = array(
			'',
			'satu',
			'dua',
			'tiga',
			'empat',
			'lima',
			'enam',
			'tujuh',
			'delapan',
			'sembilan',
			'sepuluh',
			'sebelas'
		);

		$scales = array(
			1000000000000 => ' triliun',
			1000000000    => ' milyar',
			1000000       => ' juta',
			1000          => ' ribu',
			1             => ''
		);

		$result = '';

		foreach ($scales as $value => $scale) {

			if ($number < $value) {
				continue;
			}

			$count  = (int)($number / $value);
			$number = $number % $value;

			if ($count == 0) {
				continue;
			}

			if ($count < 12) {
				$result .= ' ' . $words[$count] . $scale;
			} elseif ($count < 20) {
				$result .= ' ' . $words[$count - 10] . ' belas' . $scale;
			} elseif ($count < 100) {
				$puluh = (int)($count / 10);
				$sisa  = $count % 10;

				$result .= ' ' . $words[$puluh] . ' puluh';
				if ($sisa > 0) {
					$result .= ' ' . $words[$sisa];
				}
				$result .= $scale;
			} elseif ($count < 200) {
				$result .= ' seratus' . $scale;
				if ($count > 100) {
					$result .= ' ' . $words[$count - 100];
				}
			} elseif ($count < 1000) {
				$ratus = (int)($count / 100);
				$sisa  = $count % 100;

				$result .= ' ' . $words[$ratus] . ' ratus';
				if ($sisa > 0) {
					if ($sisa < 12) {
						$result .= ' ' . $words[$sisa];
					} elseif ($sisa < 20) {
						$result .= ' ' . $words[$sisa - 10] . ' belas';
					} else {
						$result .= ' ' . $words[(int)($sisa / 10)] . ' puluh';
						if ($sisa % 10 > 0) {
							$result .= ' ' . $words[$sisa % 10];
						}
					}
				}
				$result .= $scale;
			}
		}

		// Khusus seribu (bukan satu ribu)
		$result = str_replace(' satu ribu', ' seribu', $result);

		return ucfirst(trim($result)) . ' rupiah';
	}
}
//RUN SCRIPT
// $this->load->helper('nominal');
// echo nominal('300000');
?>