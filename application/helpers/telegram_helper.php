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

function TemplateMessageFinal($getUser, $user, $penerima) 
{
    return '
<b>DIGTA SUNANPRAJA</b>
📢 <b>Notifikasi Usulan SPJ</b>
Halo ' . $getUser->nama . ', usulan SPJ anda telah dikirim selanjutnya akan di verifikasi.
-------------
📝 Uraian   : ' . $user->uraian . '
💰 Jumlah   : Rp. ' . nominal($user->jumlah) . '
⏰ Spj Bulan : ' . bulan(strtoupper($user->bulan)) . '
📌 Status   : ' . $user->is_status . '
📌 Jml. Penerima : ' . $penerima . '
➡️ Link Berkas : ' . $user->berkas_link . '
-------------
<i>⚠️ Mohon untuk tidak dibalas, pesan ini dibuat secara otomatis (bot).
' . longdate_indo(Date('Y-m-d')) . ' ' . substr(DateTimeInput(), 10, 9) . '</i>
        ';
}

function TemplateMessageHasilVerifikasi($getSpj, $input, $note_tambahan = '', $is_proses_admin = '', $session)
{
    return '
<b>DIGTA SUNANPRAJA</b>
📢 <b>Notifikasi Usulan SPJ</b>
-------------
📝 Uraian   : ' . $getSpj->uraian . '  
💰 Jumlah   : Rp. ' . nominal($getSpj->jumlah) . ' 
⏰ Spj Bulan : ' . bulan(strtoupper($getSpj->bulan)) . '
📅 Tgl. Entri : ' . longdate_indo(substr($getSpj->entri_at, 0, 10)) . '
-------------
📌 Catatan : ' . (isset($input['catatan']) && !empty($input['catatan']) ? $input['catatan'] : '-') . '
' . $note_tambahan . '

Telah diverifikasi dengan status <b>' . $input['status'] . '</b>. ' . $is_proses_admin . ' 
Silahkan cek aplikasi Digta Sunanpraja.  
-------------
<i>⚠️ Mohon untuk tidak dibalas, pesan ini dibuat secara otomatis (bot).  
' . longdate_indo(Date('Y-m-d')) . ' ' . substr(DateTimeInput(), 10, 9) . '
by ' . $session['role'] . ' (' . $session['user_name'] . ')</i>
        ';
}

function TemplateMessageHasilVerifikasiAdmin($getSpj, $input, $session)
{
    return '
<b>DIGTA SUNANPRAJA</b>
📢 <b>Notifikasi Usulan SPJ</b>
-------------
📝 Uraian   : ' . $getSpj->uraian . '  
💰 Jumlah   : Rp. ' . nominal($getSpj->jumlah) . ' 
⏰ Spj Bulan : ' . bulan(strtoupper($getSpj->bulan)) . '
📅 Tgl. Entri : ' . longdate_indo(substr($getSpj->entri_at, 0, 10)) . '
-------------
No. Verifikasi : ' . (isset($input['nomor']) && !empty($input['nomor']) ? $input['nomor'] : '-') . '
Tgl. verifikasi : ' . (isset($input['tanggal']) && !empty($input['tanggal']) ? longdate_indo(formatToSQL($input['tanggal'])) : '-') . '
Realisasi SPJ : ' . (isset($input['is_realisasi']) && !empty($input['is_realisasi']) ? $input['is_realisasi'] : '-') . '
Verifikator : ' . $session['user_name'] . '
Catatan : ' . (isset($input['catatan']) && !empty($input['catatan']) ? $input['catatan'] : '-') . '
-------------
Telah diverifikasi dengan status <b>' . $input['status'] . '</b>.  
Silahkan cek aplikasi Digta Sunanpraja.  

<i>⚠️ Mohon untuk tidak dibalas, pesan ini dibuat secara otomatis (bot).  
' . longdate_indo(Date('Y-m-d')) . ' ' . substr(DateTimeInput(), 10, 9) . '
by ' . $session['role'] . ' (' . $session['user_name'] . ')</i>
        ';
}

function TemplateMessageApproval($detailUsul, $is_status, $session)
{
$statusText = ($is_status === 'SELESAI') ? 'APPROVE' : $detailUsul->is_status;

return '
<b>DIGTA SUNANPRAJA</b>
📢 <b>Notifikasi Usulan SPJ</b>
-------------
📝 Uraian   : ' . $detailUsul->uraian . '  
💰 Jumlah   : Rp. ' . nominal($detailUsul->jumlah) . ' 
⏰ Spj Bulan : ' . bulan(strtoupper($detailUsul->bulan)) . '
📅 Tgl. Entri : ' . longdate_indo(substr($detailUsul->entri_at, 0, 10)) . '
-------------
Telah difinalisasi <b>ADMIN</b> dengan status <b>' . $statusText . '</b>.  
Silahkan cek aplikasi Digta Sunanpraja.  
-------------
⚠️ Mohon untuk tidak dibalas, pesan ini dibuat secara otomatis (bot).  
' . longdate_indo(Date('Y-m-d')) . ' ' . substr(DateTimeInput(), 10, 9) . '
<i>by ' . $session['role'] . ' (' . $session['user_name'] . ')</i>';
}

function TemplateMessageApprovalBendahara($detailUsul, $is_status, $session)
{
return '
<b>DIGTA SUNANPRAJA</b>
📢 <b>Notifikasi Usulan SPJ</b>
-------------
📝 Uraian   : ' . $detailUsul->uraian . '  
💰 Jumlah   : Rp. ' . nominal($detailUsul->jumlah) . ' 
⏰ Spj Bulan : ' . bulan(strtoupper($detailUsul->bulan)) . '
📅 Tgl. Entri : ' . longdate_indo(substr($detailUsul->entri_at, 0, 10)) . '
-------------
No. BKU : ' . (isset($detailUsul->nomor_pembukuan) && !empty($detailUsul->nomor_pembukuan) ? $detailUsul->nomor_pembukuan : '-') . '
Tgl. BKU : ' . (isset($detailUsul->tanggal_pembukuan) && !empty($detailUsul->tanggal_pembukuan) ? longdate_indo(substr($detailUsul->tanggal_pembukuan, 0, 10)) : '-') . '
-------------
Telah diproses <b>BENDAHARA</b> dengan status <b>' . $is_status . '</b>. 

-------------
⚠️ Mohon untuk tidak dibalas, pesan ini dibuat secara otomatis (bot).  
' . longdate_indo(Date('Y-m-d')) . ' ' . substr(DateTimeInput(), 10, 9) . '
<i>by ' . $session['role'] . ' (' . $session['user_name'] . ')</i>';
}