<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_Model extends CI_Model
{
    public function get_sales_data($filter)
    {
        $this->db->select_sum('det_po.pesan', 'total_sales');
        $this->db->join('po', 'po.no_po = det_po.no_po', 'inner');
        $this->apply_date_filter('po.tgl_po', $filter);
        $query = $this->db->get('det_po');
        return $query->row()->total_sales;
    }
    public function get_revenue_data($filter)
    {
        $this->db->select_sum('total', 'total_revenue');
        $this->apply_date_filter('po.tgl_po', $filter);
        $query = $this->db->get('po');
        return $query->row()->total_revenue;
    }
    public function get_customers_data($filter)
    {
        $this->apply_date_filter('po.tgl_po', $filter);
        $this->db->from('po');
        return $this->db->count_all_results();
    }

    public function get_reports_data($filter)
    {
        $this->apply_date_filter('tgl_po', $filter);
        $this->db->select('DATE(tgl_po) as date, COUNT(*) as total');
        $this->db->group_by('DATE(tgl_po)');
        $this->db->order_by('date', 'ASC');
        $query = $this->db->get('po');
        return $query->result_array();
    }

    public function get_top_selling($filter)
    {
        $this->db->select('barang.nama_barang as nama_barang, 
                           barang.harga as harga, 
                           SUM(det_po.pesan) as total_pesan, 
                           SUM(det_po.pesan * barang.harga) as total_revenue'); // Perhitungan pendapatan
        $this->db->join('po', 'po.no_po = det_po.no_po', 'inner');
        $this->db->join('barang', 'barang.kode_barang = det_po.kode_barang', 'inner');
        $this->apply_date_filter('po.tgl_po', $filter);
        $this->db->group_by('det_po.kode_barang');
        $this->db->order_by('total_pesan', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get('det_po');
        return $query->result_array();
    }

    private function apply_date_filter($column, $filter)
    {
        switch ($filter) {
            case 'today':
                $this->db->where("DATE($column)", date('Y-m-d'));
                break;
            case 'this_month':
                $this->db->where("MONTH($column)", date('m'));
                $this->db->where("YEAR($column)", date('Y'));
                break;
            case 'this_year':
                $this->db->where("YEAR($column)", date('Y'));
                break;
            default:
                // Tidak ada filter jika tidak sesuai
                break;
        }
    }
    
}
