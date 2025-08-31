<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

if (!function_exists('generate_qr')) {
    /**
     * Generate QR Code dengan auto version & handling data panjang
     *
     * @param string $data Data yang akan diubah menjadi QR Code
     * @param array $options Custom options (opsional)
     * @param bool $returnImage Return base64 image atau tampilkan langsung
     * @return string|void
     */
    function generateQr($data, $options = [], $returnImage = false)
    {
        // default options
        $defaultOptions = [
            'version'    => 10,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L, // Low = kapasitas maksimal
            'scale'      => 10,
        ];

        $opts = new QROptions(array_merge($defaultOptions, $options));
        $qrcode = new QRCode($opts);

        if ($returnImage) {
            return $qrcode->render($data);
        } else {
            header('Content-Type: image/png');
            echo $qrcode->render($data);
        }
    }
}
