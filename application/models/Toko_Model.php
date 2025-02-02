<?php
    class Toko_Model extends CI_Model
    {
        public function get_all_toko()
        {
            return $this->db->get('toko')->result_array();
        }
    
        public function insert_toko($data)
        {
            return $this->db->insert('toko', $data);
        }
    }
?>