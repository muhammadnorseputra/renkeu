<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('TeleSendMessage')) {
    /**
     * Kirim pesan ke Telegram
     *
     * @param string $chat_id
     * @param string $message
     * @return mixed
     */
    function TeleSendMessage($chat_id, $message, $parse_mode = 'HTML')
    {
        $CI = &get_instance();

        // Bisa taruh token di config/database/env
        $botToken = getenv('TOKEN_BOT_TELEGRAM');
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        $data = [
            "chat_id" => $chat_id,
            "text"    => $message,
            "parse_mode" => $parse_mode
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'Telegram Error: ' . curl_error($ch));
            return false;
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
