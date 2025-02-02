<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation', 'session']);
        $this->load->model('User_Model');
    }

    public function index()
    {
        if ($this->input->post()) {
            $this->_login();
        } else {
            $data['title'] = 'Login';
            $data['pesan'] = $this->session->flashdata('pesan');
            $this->load->view('login/index', $data);
        }
    }
    private function _login()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Username dan Password harus diisi.</div>');
            redirect('login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User_Model->get_user_by_username($username);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'username' => $user['username']
                    ];
                    $this->session->set_userdata($data);

                    redirect('admin');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Password Salah!</div>');
                    redirect('login');
                }
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Username tidak ditemukan.</div>');
                redirect('login');
            }
        }
    }
    
    public function registrasi()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.username]');
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[4]');
        $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'required|trim|matches[password1]');
    
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Registrasi';
            $this->load->view('login/registrasi', $data);
        } else {
            $this->aksi_regis();
        }
    }
    
    public function aksi_regis()
    {
        $data_user = array(
            'nama' => htmlspecialchars($this->input->post('nama', true)),
            'username' => htmlspecialchars($this->input->post('username', true)),
            'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT)
        );
    
        $this->User_Model->insert_user($data_user);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Registrasi berhasil. Silakan login.</div>');
        redirect('login');
    }
    
    public function logout()
	{
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('id_role');
		$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
            Anda Berhasil Keluar!</div>');
		redirect('login');
	}

	public function blocked()
	{
		$this->load->view('login/blocked');
	}

}
?>
