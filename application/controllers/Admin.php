<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require_once APPPATH . '../vendor/autoload.php';

class Admin extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('User_Model');
        $this->load->model('Lop_Model');
        $this->load->model('Barang_Model');
        $this->load->model('Toko_Model');
        $this->load->model('PO_Model');
        $this->load->model('Dashboard_Model');
        $this->load->library('email');
    }
    public function index()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }

        $username = $this->session->userdata('username');
        $data['user'] = $this->db->get_where('user', ['username' => $username])->row_array();
        $data['title'] = 'Dashboard';

        $filter = $this->input->get('filter') ?: 'today';
        
        switch ($filter) {
            case 'today':
                $data['filter_label'] = 'Hari Ini';
                break;
            case 'this_month':
                $data['filter_label'] = 'Bulan Ini';
                break;
            case 'this_year':
                $data['filter_label'] = 'Tahun Ini';
                break;
            default:
                $data['filter_label'] = 'Hari Ini';
                break;
        }

        $data['sales_data'] = $this->Dashboard_Model->get_sales_data($filter);
        $data['revenue_data'] = $this->Dashboard_Model->get_revenue_data($filter);
        $data['customers_data'] = $this->Dashboard_Model->get_customers_data($filter);
        $data['reports_data'] = $this->Dashboard_Model->get_reports_data($filter);
        $data['top_selling'] = $this->Dashboard_Model->get_top_selling($filter);

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('dashboard', $data);
        $this->load->view('template/footer');
    }
    public function dataLOP() {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $username = $this->session->userdata('username');
        $data['user'] = $this->db->get_where('user', ['username' => $username])->row_array();
        $data['title'] = 'Data LOP';
    
        $data['lop'] = $this->Lop_Model->get_all_lop_with_invoices();
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('datalop', $data);
        $this->load->view('template/footer');
    }    

    public function formLOP()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Form LOP';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('form_lop', $data);
        $this->load->view('template/footer');
    }

    public function upload_excel()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
            $file = $_FILES['excel_file'];
            $filePath = $file['tmp_name'];
            $noLop = $this->input->post('no_lop');
            $lopDate = $this->input->post('tgl_lop');
            $total = $this->input->post('total');
            
            if ($this->Lop_Model->is_no_lop_exists($noLop)) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">No LOP telah terdaftar. Silakan ganti dengan No LOP yang berbeda.</div>');
                redirect('form_lop');
            }
            
            if ($file['error'] === UPLOAD_ERR_OK) {
                $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (!in_array($fileExt, ['xls', 'xlsx'])) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger">Hanya file Excel (.xls, .xlsx) yang diterima!</div>');
                    redirect('form_lop');
                }
                
                try {
                    $spreadsheet = IOFactory::load($filePath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $data = $sheet->toArray(null, true, true, true);
                    
                    foreach ($data as $index => $row) {
                        if ($index === 1) continue; 
    
                        if (empty($row)) {
                            continue;
                        }
                        
                        $headerCheck = [
                            'No. Urut', 'Tanggal Faktur', 'Nomor Faktur', 'DPP', 'PPN', 'Total', 'Lokasi Barang'
                        ];
                        $rowValues = array_map(function($value) {
                            return $value === null ? '' : trim($value); 
                        }, $row);
    
                        if (isset($rowValues[0]) && in_array($rowValues[0], $headerCheck)) {
                            continue; 
                        }
                        
                        $tglInvoice = trim((isset($row['B']) ? $row['B'] : ''));
                        $noInvoice = trim((isset($row['C']) ? $row['C'] : '') .
                                           (isset($row['D']) ? $row['D'] : '') .
                                           (isset($row['E']) ? $row['E'] : '') .
                                           (isset($row['F']) ? $row['F'] : ''));
                        $pembayaran = trim((isset($row['M']) ? $row['M'] : '') .
                                           (isset($row['N']) ? $row['N'] : ''));
                        
                        $lokasi = trim(implode(" ", [
                            isset($row['O']) ? $row['O'] : '',
                            isset($row['P']) ? $row['P'] : '',
                            isset($row['Q']) ? $row['Q'] : '',
                            isset($row['R']) ? $row['R'] : '',
                            isset($row['S']) ? $row['S'] : ''
                        ]));
                        $ttdTerima = 'tidak';
                        $status = 'belum bayar';
                        
                        if ($noInvoice && $pembayaran && $lokasi) {
                            if ($tglInvoice) {
                                $tglInvoice = date('Y-m-d', strtotime($tglInvoice)); 
                            }
                            
                            $detlopData = [
                                'no_lop'      => $noLop,
                                'tgl_lop'     => $lopDate,
                                'tgl_invoice' => $tglInvoice,
                                'no_invoice'  => $noInvoice,
                                'pembayaran'  => $pembayaran,
                                'lokasi'      => $lokasi,
                                'ttd_terima'  => $ttdTerima,
                                'status'      => $status
                            ];
                            
                            $this->Lop_Model->insert_det_lop($detlopData);
                        }
                    }
                    
                    $lopData = [
                        'no_lop' => $noLop,
                        'tgl_lop' => $lopDate,
                        'total' => $total,
                        'sisa' => $total,
                        'tempo' => $this->calculate_tempo($lopDate)
                    ];
                    $this->Lop_Model->insert_lop($lopData);
                    
                    $this->session->set_flashdata('message', '<div class="alert alert-success">File berhasil diunggah dan data disimpan!</div>');
                    redirect('admin/datalop');
                } catch (Exception $e) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger">Error membaca file Excel: ' . $e->getMessage() . '</div>');
                    redirect('admin/formlop');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal mengunggah file.</div>');
                redirect('admin/formlop');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Harap unggah file yang benar.</div>');
            redirect('admin/formlop');
        }
    }
    public function update_lop($no_lop)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Update Data LOP';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['lop'] = $this->db->get_where('lop', ['no_lop' => $no_lop])->row_array();
    
        if (!$data['lop']) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">LOP tidak ditemukan!</div>');
            redirect('admin/dataLOP');
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_no_lop = $this->input->post('no_lop', true);
            $new_tgl_lop = $this->input->post('tgl_lop', true);
    
            $update_data_lop = [
                'tgl_lop' => $new_tgl_lop,
                'total'   => $this->input->post('total', true),
                'sisa'    => $this->input->post('sisa', true),
                'tempo' => $this->calculate_tempo($new_tgl_lop)
            ];
    
            $this->db->trans_start(); // Mulai transaksi database
    
            // Periksa jika no_lop berubah
            if ($new_no_lop !== $no_lop) {
                $existing_lop = $this->db->get_where('lop', ['no_lop' => $new_no_lop])->row_array();
                if ($existing_lop) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">No LOP baru sudah terdaftar!</div>');
                    redirect('admin/update_lop/' . $no_lop);
                }
    
                // Update no_lop dan tgl_lop di tabel lop
                $this->db->where('no_lop', $no_lop);
                $this->db->update('lop', array_merge(['no_lop' => $new_no_lop], $update_data_lop));
    
                // Update no_lop dan tgl_lop di tabel det_lop
                $this->db->where('no_lop', $no_lop);
                $this->db->update('det_lop', ['no_lop' => $new_no_lop, 'tgl_lop' => $new_tgl_lop]);
            } else {
                // Update tgl_lop di tabel lop
                $this->db->where('no_lop', $no_lop);
                $this->db->update('lop', $update_data_lop);
    
                // Update tgl_lop di tabel det_lop
                $this->db->where('no_lop', $no_lop);
                $this->db->update('det_lop', ['tgl_lop' => $new_tgl_lop]);
            }
    
            $this->db->trans_complete(); // Selesaikan transaksi database
    
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Terjadi kesalahan saat memperbarui data!</div>');
                redirect('admin/update_lop/' . $no_lop);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data LOP berhasil diperbarui!</div>');
                redirect('admin/dataLOP');
            }
        }
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('update_lop', $data);
        $this->load->view('template/footer');
    }
    
    private function calculate_tempo($tgl_lop)
    {
        $date = new DateTime($tgl_lop);
        $day = (int)$date->format('d');
    
        if ($day >= 25 || $day <= 9) {
            // Jatuh tempo adalah tanggal 25 bulan berikutnya
            $date->modify('first day of next month')->setDate(
                (int)$date->format('Y'),
                (int)$date->format('m'),
                25
            );
        } elseif ($day >= 10 && $day <= 24) {
            // Jatuh tempo adalah tanggal 10 bulan berikutnya
            $date->modify('first day of next month')->setDate(
                (int)$date->format('Y'),
                (int)$date->format('m'),
                10
            );
        }
    
        return $date->format('Y-m-d');
    }
    
    
    public function delete_lop($no_lop)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }

        $po = $this->Lop_Model->get_lop_by_no_lop($no_lop);
        if ($po) {
            $this->Lop_Model->delete_lop($no_lop);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data LOP dan detailnya berhasil dihapus!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data LOP tidak ditemukan!</div>');
        }
        redirect('admin/datalop');
    }
    public function detail_lop($no_lop)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Detail LOP';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
    
        $data['lop_details'] = $this->Lop_Model->get_det_lop_by_no_lop($no_lop);
        $lop_data = $this->Lop_Model->get_lop_by_no_lop($no_lop);
    
        if (!$lop_data) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nomor LOP tidak ditemukan.</div>');
            redirect('admin/dataLOP');
        }
    
        $data['lop'] = $lop_data;
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('detail_lop', $data);
        $this->load->view('template/footer');
    }
    public function update_detail_lop($no_lop, $no_invoice) 
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        $no_invoice = str_replace('-', '/', urldecode($no_invoice)); 
        $data['title'] = 'Update Detail LOP';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

        $data['detail'] = $this->Lop_Model->get_detail_by_invoice($no_invoice, $no_lop);

        if (!$data['detail']) {
            $this->session->set_flashdata('message', 
                '<div class="alert alert-danger" role="alert">Detail LOP tidak ditemukan!</div>');
            redirect('admin/detail_lop/' . $no_lop);
        }

        $this->form_validation->set_rules('no_invoice', 'Nomor Faktur', 'required');
        $this->form_validation->set_rules('tgl_invoice', 'Tanggal Faktur', 'required');
        $this->form_validation->set_rules('pembayaran', 'Total Pembayaran', 'required|numeric');
        $this->form_validation->set_rules('tgl_pembayaran', 'Tanggal Pembayaran');
        $this->form_validation->set_rules('lokasi', 'Lokasi Barang', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('update_detail_lop', $data);
            $this->load->view('template/footer');
        } else {
            $update_data = [
                'no_invoice' => $this->input->post('no_invoice', true),
                'tgl_invoice' => $this->input->post('tgl_invoice', true),
                'pembayaran' => $this->input->post('pembayaran', true),
                'tgl_pembayaran' => $this->input->post('tgl_pembayaran', true),
                'lokasi' => $this->input->post('lokasi', true),
            ];

            $this->Lop_Model->update_detail($no_lop, $no_invoice, $update_data);
            $this->session->set_flashdata('message', 
                '<div class="alert alert-success" role="alert">Data Detail LOP berhasil diperbarui!</div>');
            redirect('admin/detail_lop/' . $no_lop);
        }
    }
    public function delete_detail_lop($no_lop, $no_invoice)
    {
        $no_invoice = str_replace('-', '/', urldecode($no_invoice)); // Decode `no_invoice` kembali ke bentuk aslinya
    
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $detail = $this->Lop_Model->get_detail_by_invoice($no_invoice, $no_lop);
    
        if ($detail) {
            $this->Lop_Model->delete_detail($no_invoice);
    
            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-success" role="alert">Detail LOP berhasil dihapus!</div>'
            );
    
            redirect('admin/detail_lop/' . $no_lop);
        } else {
            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-danger" role="alert">Detail LOP tidak ditemukan!</div>'
            );
            redirect('admin/dataLOP');
        }
    }
    
    public function update_tanda_terima()
    {
        $no_invoice = $this->input->post('no_invoice', true);
        $ttd_terima = $this->input->post('ttd_terima', true);
        $no_lop = $this->input->post('no_lop', true); 
    
        if ($no_invoice && $ttd_terima !== null) {
            $this->db->where('no_invoice', $no_invoice)
                     ->update('det_lop', ['ttd_terima' => $ttd_terima]);
    
            $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Tanda Terima berhasil diupdate!</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Gagal mengupdate Tanda Terima!</div>');
        }
    
        redirect('admin/detail_lop/' . $no_lop);
    }
    public function update_tanggal_tukar()
    {
        $no_invoice = $this->input->post('no_invoice');
        $tgl_tukar = $this->input->post('tgl_tukar');
        $no_lop = $this->input->post('no_lop');
    
        if ($no_invoice && $tgl_tukar) {
            $this->Lop_Model->update_tanggal_tukar($no_invoice, $tgl_tukar);
    
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Tanggal Tukar berhasil diperbarui!</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Gagal memperbarui Tanggal Tukar.</div>');
        }
    
        redirect('admin/detail_lop/' . $no_lop);
    }              

    public function pembayaran($no_lop)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Detail Pembayaran';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['invoice'] = $this->Lop_Model->get_invoices_by_lop($no_lop);
    
        $lop = $this->Lop_Model->get_lop_by_no($no_lop);
        $invoices = $this->Lop_Model->get_invoices_by_lop($no_lop);
    
        if (!$lop) {
            show_404();
        }
    
        $dataPembayaran = [
            'lop' => $lop,
            'invoices' => isset($invoices) ? $invoices : [],
        ];
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('pembayaran', $dataPembayaran);
        $this->load->view('template/footer');
    }
    public function proses_pembayaran()
    {
        $no_lop = $this->input->post('no_lop', true);
        $checkedInvoices = $this->input->post('invoice');
    
        if (!is_array($checkedInvoices)) {
            $checkedInvoices = [];
        }
    
        $lop = $this->Lop_Model->get_lop_by_no($no_lop);
        $allInvoices = $this->Lop_Model->get_invoices_by_lop($no_lop);
    
        if (!$lop || empty($allInvoices)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data LOP tidak valid atau tidak memiliki invoice.</div>');
            redirect('admin/datalop');
            return;
        }
    
        $totalPembayaran = 0;
    
        foreach ($allInvoices as $invoice) {
            $no_invoice = $invoice['no_invoice'];
            $isChecked = array_key_exists($no_invoice, $checkedInvoices);
    
            if ($isChecked) {
                $totalPembayaran += floatval($checkedInvoices[$no_invoice]);
                $this->Lop_Model->update_invoice_status($no_invoice, 'Bayar', $no_lop, date('Y-m-d'));
            } else {
                $this->Lop_Model->update_invoice_status($no_invoice, 'Belum Bayar', $no_lop, null);
            }
        }

        $sisaBaru = floatval($lop['total']) - $totalPembayaran;
        $this->Lop_Model->update_lop($no_lop, [
            'pembayaran' => $totalPembayaran,
            'sisa' => $sisaBaru,
        ]);
    
        $this->session->set_flashdata('message', '<div class="alert alert-success">Pembayaran berhasil diproses untuk LOP: ' . htmlspecialchars($no_lop) . '</div>');
        redirect('admin/datalop');
    }
    
    public function barang()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Data Barang';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
    
        $data['barang'] = $this->db->get('barang')->result_array(); 
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('barang', $data);
        $this->load->view('template/footer');
    }
    
    public function addbarang()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Tambah Data Barang';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('add_barang', $data);
        $this->load->view('template/footer');
    }
    
    public function save_barang()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('isi_karton', 'Isi Karton', 'required|numeric');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        $this->form_validation->set_rules('harga', 'Harga Unit', 'required|numeric');
        $this->form_validation->set_rules(
            'kode_barang',
            'Kode Barang',
            'required|is_unique[barang.kode_barang]',
            [
                'is_unique' => 'Kode barang tidak boleh sama!' 
            ]
        );
    
        if ($this->form_validation->run() == FALSE) {
            $this->addbarang();
        } else {
            $data = [
                'kode_barang' => $this->input->post('kode_barang'),
                'nama_barang' => $this->input->post('nama_barang'),
                'isi_karton' => $this->input->post('isi_karton'),
                'satuan' => $this->input->post('satuan'),
                'harga' => $this->input->post('harga'),
                'created_at' => date('Y-m-d H:i:s'),
            ];
    
            $this->Barang_Model->insert_barang($data);
    
            $this->session->set_flashdata('message', '<div class="alert alert-success">Barang berhasil ditambahkan!</div>');
            redirect('admin/barang');
        }
    }
    
    public function update_barang($kode_barang)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Update Data Barang';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
    
        $data['barang'] = $this->db->get_where('barang', ['kode_barang' => $kode_barang])->row_array();
    
        if (!$data['barang']) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Barang tidak ditemukan!</div>');
            redirect('admin/barang');
        }
    
        $this->form_validation->set_rules('kode_barang', 'Kode Barang');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('isi_karton', 'Isi Karton', 'required|numeric');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        $this->form_validation->set_rules('harga', 'Harga Satuan', 'required|numeric');
    
        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('update_barang', $data);
            $this->load->view('template/footer');
        } else {
            $update_data = [
                'kode_barang' => $this->input->post('kode_barang'),
                'nama_barang' => $this->input->post('nama_barang'),
                'isi_karton' => $this->input->post('isi_karton'),
                'satuan' => $this->input->post('satuan'),
                'harga' => $this->input->post('harga'),
            ];
    
            $this->db->where('kode_barang', $kode_barang);
            $this->db->update('barang', $update_data);
    
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data barang berhasil diperbarui!</div>');
            redirect('admin/barang');
        }
    }
    public function delete_barang($kode_barang)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $barang = $this->db->get_where('barang', ['kode_barang' => $kode_barang])->row_array();
    
        if ($barang) {
            $this->db->where('kode_barang', $kode_barang);
            $this->db->delete('barang');
    
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data barang berhasil dihapus!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Barang tidak ditemukan!</div>');
        }
    
        redirect('admin/barang');
    }
    public function toko()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Data Toko';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['toko'] = $this->Toko_Model->get_all_toko();
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('toko', $data); 
        $this->load->view('template/footer');
    }
    
    public function addtoko()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Tambah Data Toko';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('add_toko', $data);
        $this->load->view('template/footer');
    }
    
    public function save_toko()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $this->form_validation->set_rules('store_name', 'Nama Toko', 'required');
        $this->form_validation->set_rules('kode', 'Inisial Toko', 'required');
        $this->form_validation->set_rules('store_address', 'Alamat Toko', 'required');
        $this->form_validation->set_rules(
            'store_code',
            'Kode Toko',
            'required|is_unique[toko.store_code]',
            [
                'is_unique' => 'Kode toko tidak boleh sama!' 
            ]
        );
    
        if ($this->form_validation->run() == FALSE) {
            $this->addtoko(); 
        } else {
            $data = [
                'store_code'   => $this->input->post('store_code'),
                'kode'         => $this->input->post('kode'),
                'store_name'   => $this->input->post('store_name'),
                'store_address'=> $this->input->post('store_address')
            ];
    
            $this->Toko_Model->insert_toko($data);
            
            $this->session->set_flashdata('message', '<div class="alert alert-success">Toko berhasil ditambahkan!</div>');
            redirect('admin/toko'); 
        }
    }
    public function update_toko($store_code)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Update Data Toko';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
    
        $data['toko'] = $this->db->get_where('toko', ['store_code' => $store_code])->row_array();
    
        if (!$data['toko']) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Toko tidak ditemukan!</div>');
            redirect('admin/toko');
        }
    
        $this->form_validation->set_rules('store_code', 'Kode Toko', 'required');
        $this->form_validation->set_rules('store_name', 'Nama Toko', 'required');
        $this->form_validation->set_rules('kode', 'Inisial Toko', 'required');
        $this->form_validation->set_rules('store_address', 'Alamat Toko', 'required');
    
        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('update_toko', $data);
            $this->load->view('template/footer');
        } else {
            $update_data = [
                'store_code' => $this->input->post('store_code'),
                'store_name' => $this->input->post('store_name'),
                'kode' => $this->input->post('kode'),
                'store_address' => $this->input->post('store_address'),
            ];
    
            $this->db->where('store_code', $store_code);
            $this->db->update('toko', $update_data);
    
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data Toko berhasil diperbarui!</div>');
            redirect('admin/toko');
        }
    }
    public function delete_toko($store_code)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $toko = $this->db->get_where('toko', ['store_code' => $store_code])->row_array();
    
        if ($toko) {
            $this->db->where('store_code', $store_code);
            $this->db->delete('toko');
    
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data toko berhasil dihapus!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Toko tidak ditemukan!</div>');
        }
    
        redirect('admin/toko');
    }
    public function dataPO()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }

        $data['title'] = 'Data PO Toko';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['po_list'] = $this->PO_Model->get_all_po();
        $data['po'] = $this->PO_Model->get_all_po();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('datapo', $data); 
        $this->load->view('template/footer');
    }

    public function formPO()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Form PO';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['toko'] = $this->PO_Model->get_all_toko();
        $data['barang'] = $this->Barang_Model->get_all_barang();
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('form_po', $data);
        $this->load->view('template/footer');
    }    
    
    public function save_po()
    {
        $noPo = $this->input->post('no_po');
        $storeCode = $this->input->post('store_code');
        $poDate = $this->input->post('tgl_po');
        $doDate = $this->input->post('tgl_kirim');
        $total = $this->input->post('total');
        $barang = $this->input->post('kode_barang');
        $pesan = $this->input->post('pesan');
        $harga = $this->input->post('harga');
        $total_harga = $this->input->post('total_harga');
                
        if ($this->PO_Model->is_no_po_exists($noPo)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">No PO telah terdaftar. Silakan ganti dengan No PO yang berbeda.</div>');
            redirect('admin/formPO');
        }
    
        $poData = [
            'no_po'      => $noPo,
            'store_code' => $storeCode,
            'tgl_po'     => $poDate,
            'tgl_kirim'  => $doDate,
            'total'      => $total,
        ];
        $this->PO_Model->insert_po($poData);
    
        foreach ($barang as $key => $kodeBarang) {
            $detPoData = [
                'no_po'       => $noPo,
                'kode_barang' => $kodeBarang,
                'pesan'       => $pesan[$key],
                'harga'       => $harga[$key],
                'total_harga' => $total_harga[$key],
            ];
            $this->PO_Model->insert_det_po($detPoData);
    
            $barangData = $this->PO_Model->get_barang_by_kode($kodeBarang);
            if ($barangData && $barangData['harga'] != $harga[$key]) {
                $this->PO_Model->update_barang_harga($kodeBarang, $harga[$key]);
            }
        }
    
        $this->session->set_flashdata('message', '<div class="alert alert-success">Data PO berhasil disimpan!</div>');
        redirect('admin/datapo');
    }
    
    public function update_po($no_po)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Update Data PO';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['toko'] = $this->PO_Model->get_all_toko();
    
        $data['po'] = $this->db->get_where('po', ['no_po' => $no_po])->row_array();
    
        if (!$data['po']) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">PO tidak ditemukan!</div>');
            redirect('admin/barang');
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $update_data = [
                'store_code' => $this->input->post('store_code'),
                'tgl_po' => $this->input->post('tgl_po'),
                'tgl_kirim' => $this->input->post('tgl_kirim'),
                'total' => $this->input->post('total')
            ];
    
            $this->db->where('no_po', $no_po);
            $this->db->update('po', $update_data);
    
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data PO berhasil diperbarui!</div>');
            redirect('admin/datapo');
        }
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('update_po', $data);
        $this->load->view('template/footer');
    }
    
    public function check_tgl_kirim($tgl_kirim)
    {
        $tgl_po = $this->input->post('tgl_po');
        
        if (strtotime($tgl_po) > strtotime($tgl_kirim)) {
            $this->form_validation->set_message('check_tgl_kirim', 'Tanggal Kirim harus setelah atau sama dengan Tanggal PO.');
            return false;
        }
    
        return true;
    }

    public function delete_po($no_po)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }

        $po = $this->PO_Model->get_po_by_no_po($no_po);
        if ($po) {
            $this->PO_Model->delete_po($no_po);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data PO dan detailnya berhasil dihapus!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data PO tidak ditemukan!</div>');
        }
        redirect('admin/datapo');
    }

    public function detail_po($no_po)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Detail PO';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['po_details'] = $this->PO_Model->get_det_po_by_no_po($no_po);
    
        if (!empty($data['po_details'])) {
            $data['no_po'] = $data['po_details'][0]['no_po']; 
        } else {
            $data['no_po'] = ''; 
        }
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('detail_po', $data);
        $this->load->view('template/footer');
    }
    public function update_detail_po($no_po, $kode_barang)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Update Detail PO';
        $data['user'] = $this->db->get_where('user', [
            'username' => $this->session->userdata('username')
        ])->row_array();
    
        $data['detail'] = $this->PO_Model->get_detail_by_item_no_and_po($kode_barang, $no_po);
    
        if (!$data['detail']) {
            $this->session->set_flashdata('message', 
                '<div class="alert alert-danger" role="alert">Detail PO tidak ditemukan!</div>');
            redirect('admin/detail_po/' . $no_po);
        }
    
        $this->form_validation->set_rules('pesan', 'Jumlah Pesanan', 'required|integer');
    
        if ($this->form_validation->run() == false) {
            log_message('debug', 'Data detail: ' . print_r($data['detail'], true));
    
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('update_detail_po', $data);
            $this->load->view('template/footer');
        } else {
            $update_data = [
                'pesan' => $this->input->post('pesan'),
                'harga' => $this->input->post('harga'),
                'total_harga' => $this->input->post('pesan') * $this->input->post('harga'),
            ];
    
            $this->PO_Model->update_detail($no_po, $kode_barang, $update_data);
    
            $this->db->select_sum('total_harga');
            $this->db->where('no_po', $no_po);
            $total_po = $this->db->get('det_po')->row()->total_harga;
    
            $this->db->where('no_po', $no_po);
            $this->db->update('po', ['total' => $total_po]);
    
            $this->session->set_flashdata('message', 
                '<div class="alert alert-success" role="alert">Data Detail PO berhasil diperbarui dan total PO diperbarui!</div>');
            redirect('admin/detail_po/' . $no_po);
        }
    }    
    
    public function delete_detail_po($kode_barang)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $detail = $this->PO_Model->get_detail_by_item_no($kode_barang);
        if ($detail) {
            $this->PO_Model->delete_detail($kode_barang);
    
            $this->db->select_sum('total_harga');
            $this->db->where('no_po', $detail['no_po']);
            $total_po = $this->db->get('det_po')->row()->total_harga;
    
            $this->db->where('no_po', $detail['no_po']);
            $this->db->update('po', ['total' => $total_po]);
    
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data PO berhasil dihapus dan total PO diperbarui!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data PO tidak ditemukan!</div>');
        }
    
        redirect('admin/detail_po/' . $detail['no_po']);
    }
    
    public function faktur($no_po = NULL)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        if ($no_po === NULL) {
            show_error('No. Faktur tidak ditemukan!', 404, 'Error');
        }

        $po_data = $this->PO_Model->get_po_by_no_po_faktur($no_po);
        if (!$po_data) {
            show_error('Data PO tidak ditemukan!', 404, 'Error');
        }
    
        $det_po_data = $this->PO_Model->get_det_po_by_no_po($no_po);
    
        $total_harga = 0;
        foreach ($det_po_data as $item) {
            $total_harga += $item['pesan'] * $item['harga'];
        }
    
        $data['title'] = 'Faktur Penjualan';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['po_data'] = $po_data; 
        $data['det_po_data'] = $det_po_data;
        $data['total_harga'] = $total_harga;
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('faktur', $data);
        $this->load->view('template/footer');
    }
    public function no_faktur($no_po)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Update Detail PO';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
    
        $data['po'] = $this->db->get_where('po', ['no_po' => $no_po])->row_array();
    
        if (!$data['po']) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">PO tidak ditemukan!</div>');
            redirect('admin/faktur'); 
        }
    
        $this->form_validation->set_rules(
            'no_faktur',
            'No. Faktur',
            'required|is_unique[po.no_faktur]',
            [
                'is_unique' => 'No. Faktur tidak boleh sama!' 
            ]
        );
    
        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('no_faktur', $data);
            $this->load->view('template/footer');
        } else {
            $update_data = [
                'no_faktur' => $this->input->post('no_faktur'),
            ];
    
            $this->PO_Model->update_no_faktur($no_po, $update_data);
    
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">No. Faktur berhasil ditambah!</div>');
            redirect('admin/faktur/' . $no_po);
        }
    }
    public function send_email()
    {
        $data = json_decode(file_get_contents('php://input'), true);
    
        $email = $data['email'];
        $subject = $data['subject'];
        $message = $data['message'];
        $factureData = $data['factureData']; 
    
        $htmlContent = "<html><body>";
        $htmlContent .= "<h2>Invoice Faktur</h2>";
        $htmlContent .= $factureData;
        $htmlContent .= "</body></html>";
    
        $mail = new PHPMailer(true);
    
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  
            $mail->SMTPAuth = true;
            $mail->Username = 'calista.anindita@mhs.unsoed.ac.id';
            $mail->Password = 'Warnaungu4*';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('calista.anindita@mhs.unsoed.ac.id', 'Pangrango Valley');
            $mail->addAddress($email);  
    
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlContent;
    
            if ($mail->send()) {
                echo json_encode(['success' => true, 'message' => 'Email berhasil dikirim']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengirim email']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Email tidak dapat dikirim. Error: {$mail->ErrorInfo}"]);
        }
    }
    public function send_datim()
    {
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (!isset($data['email'], $data['subject'], $data['message'], $data['factureData'])) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }
    
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            echo json_encode(['success' => false, 'message' => 'Email tidak valid']);
            return;
        }
    
        $subject = htmlspecialchars($data['subject']);
        $message = nl2br(htmlspecialchars($data['message']));
        $factureData = $data['factureData']; // Jangan decode di sini, biarkan HTML seperti semula
    
        $htmlContent = "<html><body>";
        $htmlContent .= "<h2>DataTimbangan</h2>";
        $htmlContent .= $factureData; // Isi HTML yang dikirim
        $htmlContent .= "</body></html>";
    
        $mail = new PHPMailer(true);
    
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'calista.anindita@mhs.unsoed.ac.id';
            $mail->Password = 'Warnaungu4*'; // Ganti dengan App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('calista.anindita@mhs.unsoed.ac.id', 'Pangrango Valley');
            $mail->addAddress($email);
    
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlContent;
    
            if ($mail->send()) {
                echo json_encode(['success' => true, 'message' => 'Email berhasil dikirim']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengirim email']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Email tidak dapat dikirim. Error: " . $mail->ErrorInfo]);
        }
    }
    
    public function datagudang()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }

        $data['title'] = 'Data Gudang';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

        $data['barang_per_hari'] = $this->PO_Model->get_barang_per_hari();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('datagudang', $data);
        $this->load->view('template/footer');
    }

    public function detail_gudang($date)
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    
        $data['title'] = 'Detail Barang Gudang';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
    
        // Ambil data dari model
        $barang_data = $this->PO_Model->get_detail_barang_with_stores($date);
        $data['detail_barang'] = $barang_data['data'];
        $data['stores'] = $barang_data['stores'];
        $data['tanggal'] = $date;
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('detail_gudang', $data);
        $this->load->view('template/footer');
    }    

}
?>
