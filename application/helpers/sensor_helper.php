<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
      
    if ( ! function_exists('sensor'))
    {
        function sensor($str) {
            $target = $str;
            $count = strlen($target) - 3;
            $asterix = '';

            for ($a = 0; $a <= $count; $a++) {
                $asterix .= '*';
            }

            $output = substr($target, 0, 4) . $asterix . substr($target, -3);

            return $output;
        }
    }

    if ( ! function_exists('LimitText'))
    {
        function limitText($msg, $count=70) {
            // FILTER ISI NOTIF
            $isi_notif = strip_tags($msg); // membuat paragraf pada isi berita dan mengabaikan tag html
            $isi = substr($isi_notif, 0, $count); // ambil sebanyak 80 karakter
            $isi = substr($isi_notif, 0, strrpos($isi, ' ')); // potong per spasi kalimat

            $more = (strlen($msg) <= $count) ? '' : '...';
            return $isi . $more;
        }
    }
    
?>