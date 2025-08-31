<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelWhatsapp extends CI_Model {

    public function getSessions()
    {
        // Implement API call to get WhatsApp sessions
        return $this->db->select('w.*, u.nohp')->from('t_whatsapp_sessions as w')->join('t_users as u', 'u.id = w.fid_user')->get();
    }

    public function createSession($data)
    {
        // insert to database response api whatsapp session
        return $this->db->insert('t_whatsapp_sessions', $data);
    }

    public function updateSession($data, $whr)
    {
        return $this->db->update('t_whatsapp_sessions', $data, $whr);
    }

    public function stopSession($name)
    {
        return $this->db->delete('t_whatsapp_sessions', ['name' => $name]);
    }
}