<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lop_Model extends CI_Model {
    public function insert_lop($data)
    {
        $this->db->insert('lop', $data);
    }

    public function insert_det_lop($data)
    {
        $this->db->insert('det_lop', $data);
    }
    public function is_no_lop_exists($no_lop)
    {
        $this->db->where('no_lop', $no_lop);
        $query = $this->db->get('lop');
        return $query->num_rows() > 0;
    }

    public function get_all_lop(){
        $query = $this->db->get('lop');
        return $query->result_array();  
    }
    public function get_det_lop_by_no_lop($no_lop)
    {
        $this->db->select('*');
        $this->db->from('det_lop');
        $this->db->where('no_lop', $no_lop);
    
        $this->db->not_like('tgl_invoice', '1970-01-01', 'both');
        $this->db->not_like('no_invoice', 'Nomor Faktur', 'both');
        $this->db->not_like('lokasi', 'Lokasi Barang', 'both');
    
    
        return $this->db->get()->result_array();
    }
    public function update_lop($no_lop, $data)
    {
        $this->db->where('no_lop', $no_lop);
        $this->db->update('lop', $data);
    }
    
    public function get_lop_by_no_lop($no_lop)
    {
        return $this->db->get_where('lop', ['no_lop' => $no_lop])->row_array();
    }
    public function get_all_lop_with_invoices()
    {
        $this->db->select('lop.*, COUNT(det_lop.no_invoice) AS jumlah_invoice');
        $this->db->from('lop');
        $this->db->join('det_lop', 'lop.no_lop = det_lop.no_lop', 'left');
        $this->db->group_by('lop.no_lop');
        $lop_data = $this->db->get()->result_array();
    
        foreach ($lop_data as &$lop) {
            $this->db->select('no_invoice');
            $this->db->from('det_lop');
            $this->db->where('no_lop', $lop['no_lop']);
            $this->db->where('status', 'Belum Bayar');
            $this->db->not_like('no_invoice', 'Nomor Faktur', 'both');
            $faktur_belum_bayar = $this->db->get()->result_array();
    
            $lop['faktur_belum_bayar'] = array_column($faktur_belum_bayar, 'no_invoice');
        }
    
        return $lop_data;
    }
    
    public function get_lop_by_no($no_lop)
    {
        $this->db->where('no_lop', $no_lop);
        return $this->db->get('lop')->row_array();
    }    
    
    public function get_invoices_by_lop($no_lop)
    {
        $this->db->select('*');
        $this->db->from('det_lop');
        $this->db->where('no_lop', $no_lop);
        $this->db->not_like('no_invoice', 'Nomor Faktur', 'both');
        return $this->db->get()->result_array();
    }
    
    public function delete_lop($no_lop)
    {
        $this->db->where('no_lop', $no_lop);
        $this->db->delete('det_lop');

        $this->db->where('no_lop', $no_lop);
        $this->db->delete('lop');
    }
    public function update_invoice_status($no_invoice, $status, $no_lop)
    {
        $data = ['status' => $status];
    
        if (strtolower($status) === 'bayar') {
            $data['tgl_pembayaran'] = date('Y-m-d');
        } else {
            $data['tgl_pembayaran'] = null; 
        }
    
        $this->db->where('no_invoice', $no_invoice);
        $this->db->where('no_lop', $no_lop); 
        $this->db->update('det_lop', $data);
    }
    public function get_detail_by_invoice($no_invoice, $no_lop = null)
    {
        $this->db->where('no_invoice', $no_invoice);
        if ($no_lop) {
            $this->db->where('no_lop', $no_lop);
        }
        return $this->db->get('det_lop')->row_array();
    }
    
    public function update_detail($no_lop, $no_invoice, $data)
    {
        $this->db->where('no_lop', $no_lop);
        $this->db->where('no_invoice', $no_invoice);
        $this->db->update('det_lop', $data);
    }
    
    public function delete_detail($no_invoice)
    {
        $this->db->where('no_invoice', $no_invoice);
        $this->db->delete('det_lop');
    }
    
    public function update_tanggal_tukar($no_invoice, $tgl_tukar)
    {
        $this->db->where('no_invoice', $no_invoice);
        $this->db->update('det_lop', ['tgl_tukar' => $tgl_tukar]);
    }
    
}
?>