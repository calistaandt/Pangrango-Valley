<?php
class Barang_Model extends CI_Model
{
    public function get_all_barang()
    {
        $this->db->order_by('nama_barang', 'ASC');
        return $this->db->get('barang')->result_array();
    }    
    public function insert_barang($data)
    {
        return $this->db->insert('barang', $data);
    }
    public function get_all_keterangan()
    {
        return $this->db->get('keterangan')->result_array();
    }
    public function get_all_jenis_barang()
    {
        return $this->db->get('jenis_barang')->result_array();
    }
    
}

?>