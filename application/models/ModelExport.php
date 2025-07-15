<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelExport extends CI_Model
{
    public function getPartName($id)
    {
        $q = $this->db->select('nama, singkatan')->from('ref_parts')->where('id', $id)->get();
        return $q->row();
    }
}
