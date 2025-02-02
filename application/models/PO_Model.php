<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PO_Model extends CI_Model {
    
    public function insert_po($data)
    {
        $this->db->insert('po', $data);
    }

    public function insert_det_po($data)
    {
        return $this->db->insert('det_po', $data);
    }
    
    public function get_all_po()
    {
        $this->db->select('po.no_po, toko.store_name, po.tgl_po, po.tgl_kirim, po.total');
        $this->db->from('po');
        $this->db->join('toko', 'po.store_code = toko.store_code');
        $po_list = $this->db->get()->result_array();
        return $po_list;
    }    

    public function get_det_po_by_no_po($no_po)
    {
        $this->db->select('
            det_po.*,
            barang.harga,
            barang.nama_barang,
            barang.kode_barang,
            barang.satuan,
            barang.isi_karton
        ');
        $this->db->from('det_po');
        $this->db->join('barang', 'det_po.kode_barang = barang.kode_barang', 'left');
        $this->db->where('det_po.no_po', $no_po);
    
        $query = $this->db->get();
        $result = $query->result_array();
    
        foreach ($result as &$item) {
            $item['jumlah_satuan'] = $item['pesan'] * $item['isi_karton'];
        }
    
        return $result;
    }
    
    public function get_barang_per_hari()
    {
        $this->db->select('DATE(po.tgl_po) as tanggal, SUM(det_po.pesan) as total_barang');
        $this->db->from('det_po');
        $this->db->join('po', 'det_po.no_po = po.no_po');
        $this->db->group_by('DATE(po.tgl_po)');
        $this->db->order_by('tanggal', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_detail_barang_per_hari($date)
    {
        $this->db->select('det_po.kode_barang, det_po.nama_barang, SUM(det_po.pesan) as total_barang, barang.isi_karton, barang.satuan');
        $this->db->from('det_po');
        $this->db->join('po', 'det_po.no_po = po.no_po');
        $this->db->join('barang', 'det_po.kode_barang = barang.kode_barang', 'left');
        $this->db->where('DATE(po.tgl_po)', $date);
        $this->db->group_by('det_po.kode_barang');
        $this->db->order_by('det_po.kode_barang', 'ASC');
        $this->db->not_like('kode_barang', 'SUPPLIER', 'both');
        $this->db->not_like('kode_barang', 'DESCRIPTION', 'both');
        $this->db->not_like('nama_barang', 'ITEM DESCRIPTION', 'both');
        $this->db->not_like('kode_barang', 'ITEM NO', 'both');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_po_by_no_po($no_po)
    {
        return $this->db->get_where('po', ['no_po' => $no_po])->row_array();
    }

    public function update_po($no_po, $update_data)
    {
        $this->db->where('no_po', $no_po);
        $this->db->update('po', $update_data);
    }

    public function delete_po($no_po)
    {
        $this->db->where('no_po', $no_po);
        $this->db->delete('det_po');

        $this->db->where('no_po', $no_po);
        $this->db->delete('po');
    }

    public function get_all_toko()
    {
        $this->db->order_by('store_code', 'ASC');
        return $this->db->get('toko')->result_array();
    }

    public function get_all_det_po($no_po)
    {
        return $this->db->get('det_po')->result_array();
    }

    public function get_detail_by_item_no($kode_barang)
    {
        return $this->db->get_where('det_po', ['kode_barang' => $kode_barang])->row_array();
    }
    
    public function update_detail($no_po, $kode_barang, $update_data)
    {
        $this->db->where('no_po', $no_po);
        $this->db->where('kode_barang', $kode_barang);
        $this->db->update('det_po', $update_data);
    }
    public function update_no_faktur($no_po, $update_data)
    {
        $this->db->where('no_po', $no_po);
        $this->db->update('po', $update_data);
    }
    
    public function delete_detail($kode_barang)
    {
        $this->db->where('kode_barang', $kode_barang);
        $this->db->delete('det_po');
    }
    public function get_detail_by_item_no_and_po($kode_barang, $no_po)
    {
        $this->db->select('det_po.*, barang.nama_barang, barang.isi_karton, barang.satuan');
        $this->db->from('det_po');
        $this->db->join('barang', 'det_po.kode_barang = barang.kode_barang', 'left');
        $this->db->where('det_po.kode_barang', $kode_barang);
        $this->db->where('det_po.no_po', $no_po);
        $result = $this->db->get()->row_array();
    
        if (!$result) {
            log_message('error', "No detail found for kode_barang: $kode_barang and no_po: $no_po");
        }
    
        return $result;
    }
    public function is_no_po_exists($no_po)
    {
        $this->db->where('no_po', $no_po);
        $query = $this->db->get('po');
        return $query->num_rows() > 0;
    }
    public function get_po_by_no_po_faktur($no_po)
    {
        $this->db->select('po.*, toko.store_name, toko.store_address');
        $this->db->from('po');
        $this->db->join('toko', 'po.store_code = toko.store_code', 'left');
        $this->db->where('po.no_po', $no_po);
        return $this->db->get()->row_array();
    }
    public function update_barang_harga($kodeBarang, $hargaBaru)
    {
        $this->db->where('kode_barang', $kodeBarang);
        $this->db->update('barang', ['harga' => $hargaBaru]);
    }
    
    public function get_barang_by_kode($kodeBarang)
    {
        return $this->db->get_where('barang', ['kode_barang' => $kodeBarang])->row_array();
    }
    public function get_detail_barang_with_stores($date)
    {
        // Ambil daftar toko yang melakukan pemesanan pada tanggal tertentu
        $stores = $this->db->distinct()
                           ->select('toko.kode') // Ambil kode, bukan store_code
                           ->from('toko')
                           ->join('po', 'toko.store_code = po.store_code', 'inner')
                           ->where('DATE(po.tgl_po)', $date)
                           ->get()
                           ->result_array();
    
        // Buat daftar kolom toko berdasarkan kode
        $store_columns = array_column($stores, 'kode');
    
        // Query untuk mendapatkan detail barang beserta jumlah pemesanannya per toko
        $this->db->select('barang.nama_barang, IFNULL(SUM(det_po.pesan), 0) AS total_pesan');
    
        foreach ($stores as $store) {
            $kode = $store['kode'];
            $this->db->select("
                IFNULL(SUM(CASE WHEN toko.kode = '$kode' THEN det_po.pesan ELSE 0 END), 0) AS `$kode`
            ");
        }
    
        $this->db->from('barang');
        $this->db->join('det_po', 'det_po.kode_barang = barang.kode_barang', 'left');
        $this->db->join('po', 'det_po.no_po = po.no_po', 'left');
        $this->db->join('toko', 'po.store_code = toko.store_code', 'left'); // Gabungkan tabel toko
        $this->db->where('DATE(po.tgl_po)', $date);
        $this->db->group_by('barang.nama_barang');
    
        $query = $this->db->get();
        $result = $query->result_array();
    
        return ['data' => $result, 'stores' => $store_columns];
    }
                         
}
?>
