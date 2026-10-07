<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (! function_exists('tagscript')) {
    function tagscript($src = '', $language = 'javascript', $type = 'text/javascript', $index_page = false)
    {
        $CI     = &get_instance();
        $script = '<scr' . 'ipt';
        if (is_array($src)) {
            foreach ($src as $k => $v) {
                if ($k == 'src' and strpos($v, '://') === false) {
                    $__v = file_exists(FCPATH . $v) ? '?v=' . filemtime(FCPATH . $v) : '';
                    if ($index_page === true) {
                        $script .= ' src="' . $CI->config->site_url($v) . $__v . '"';
                    } else {
                        $script .= ' src="' . $CI->config->slash_item('base_url') . $v . $__v . '"';
                    }
                } else {
                    $script .= "$k=\"$v\"";
                }
            }

            $script .= "></scr" . "ipt>\n";
        } else {
            if (strpos($src, '://') !== false) {
                $script .= ' src="' . $src . '" ';
            } elseif ($index_page === true) {
                $script .= ' src="' . $CI->config->site_url($src) . '" ';
            } else {
                $__f     = FCPATH . $src;
                $__v     = file_exists($__f) ? '?v=' . filemtime($__f) : '';
                $script .= ' src="' . $CI->config->slash_item('base_url') . $src . $__v . '" ';
            }

            $script .= 'language="' . $language . '" type="' . $type . '"';
            $script .= '></scr' . 'ipt>' . "\n";
        }
        return $script;
    }
}
if (! function_exists('asset_url')) {
    // URL asset dengan cache-busting ?v=filemtime (untuk CSS/JS lokal)
    function asset_url($path = '')
    {
        if (strpos($path, '://') !== false) {
            return $path;
        }

        $f = FCPATH . $path;
        return base_url($path) . (file_exists($f) ? '?v=' . filemtime($f) : '');
    }
}
if (! function_exists('isActive')) {
    function isActive($path)
    {
        $CI      = &get_instance();
        $segment = $CI->uri->segment(2);
        if ($path === $segment) {
            return 'active';
        }
    }
}
