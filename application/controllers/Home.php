<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
  private $data;

  public function __construct()
  {
    parent::__construct();
    $this->data['judul'] = "Sistem KP - Dashboard";

    $this->load->model('User_Model');
    $this->load->model('Dosen_Model');

    if ($this->session->userdata('username') == NULL) {
      redirect('auth');
    } else {
      $this->data['username'] = $this->session->userdata('username');

      $this->data['role_id'] = $this->session->userdata('role_id');
    }
  }

  public function index()
  {

    if ($this->session->userdata('role_id') == 1) {
      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/dosen/index');
      $this->load->view('templates/footer');
    } else if ($this->session->userdata('role_id') == 2) {
      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/kp/index');
      $this->load->view('templates/footer');
    } else {
      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/index');
      $this->load->view('templates/footer');
    }
  }

  public function users()
  {
    $users = $this->User_Model->getUsers();
    if ($users) {
      $this->data['exist'] = true;
      $this->data['users'] = $users;
    } else {
      $this->data['exist'] = true;
      $this->data['users'] = NULL;
    }

    $this->load->view('templates/header', $this->data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('home/users');
    $this->load->view('templates/footer');
  }

  public function tambahUser()
  {
    $this->form_validation->set_rules('username', 'Username', 'required|trim');
    $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[8]|max_length[16]|matches[password2]');
    $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

    $this->form_validation->set_rules('niy', 'NIY', 'required|trim|numeric|min_length[10]|max_length[10]');
    $this->form_validation->set_rules('nama', 'Nama', 'required|trim');

    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/user/tambah');
      $this->load->view('templates/footer');
    } else {
      $data = [
        'username' => htmlspecialchars($this->input->post('username', 1)),
        'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
        'is_active' => 1,
        'role_id' => 1,
        'created_at' => time()
      ];

      $user = [
        'niy' => htmlspecialchars($this->input->post('niy', 1)),
        'nama' => htmlspecialchars($this->input->post('nama', 1)),
        'created_at' => time()
      ];

      if ($this->User_Model->addUser($data, $user)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Akun berhasil ditambahkan!</div>');
      } else {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Akun gagal ditambahkan!</div>');
      }

      redirect('home/users');
    }
  }

  public function aktivasi()
  {
    $id = $this->input->get('id');
    if ($id) {
      if ($this->User_Model->activate($id)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Status aktif berhasil diubah!</div>');
      } else {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Status aktif gagal diubah!</div>');
      }
    } else {
      $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Gagal diubah!</div>');
    }

    redirect('home/users');
  }

  public function daftar_kp()
  {
    $this->form_validation->set_rules('nim', 'NIM', 'required|trim|numeric');
    $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
    $this->form_validation->set_rules('judul', 'Judul', 'required|trim');
    $this->form_validation->set_rules('lokasi', 'Lokasi', 'required|trim');

    if ($this->form_validation->run() == NULL) {

      $is_registered = $this->User_Model->isRegistered();

      $data['register'] = NULL;

      if ($is_registered['jumlah'] != 0) {
        $data['register'] = $is_registered['jumlah'];
      }

      $data['jenis'] = $this->User_Model->getSkema();
      $data['dosen'] = $this->User_Model->getDosen();

      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/kp/daftar_kp', $data);
      $this->load->view('templates/footer');
    } else {
      $user = [
        "nim" => htmlspecialchars($this->input->post('nim', 1)),
        "nama" => htmlspecialchars($this->input->post('nama', 1))
      ];

      $kp = [
        "judul" => htmlspecialchars($this->input->post('judul', 1)),
        "lokasi" => htmlspecialchars($this->input->post('lokasi', 1)),
        "skema_id" => $this->input->post('jenis'),
        "dosen_niy" => htmlspecialchars($this->input->post('dosen', 1)),
        "created_at" => time()
      ];

      if ($this->User_Model->daftarKP($user, $kp)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
      } else {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data gagal disimpan!</div>');
      }
      redirect('home/daftar_kp');
    }
  }

  public function upload_berkas_kp()
  {
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/kp/upload_berkas_kp');
      $this->load->view('templates/footer');
    } else { }
  }

  public function info_kp()
  {
    if ($this->form_validation->run() == FALSE) {
      $data['notifikasi'] = $this->User_Model->getNotifikasi();

      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/kp/info_kp', $data);
      $this->load->view('templates/footer');
    } else { }
  }

  public function nilai_kp()
  {
    if ($this->form_validation->run() == FALSE) {
      $nilai = $this->User_Model->getNilai();

      $data['uts'] = $nilai['uts'];
      $data['uas'] = $nilai['uas'];

      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/kp/nilai_kp', $data);
      $this->load->view('templates/footer');
    } else {
      $nilai = [
        'uts' => $this->input->post('nilai_UTS'),
        'uas' => $this->input->post('nilai_UAS')
      ];

      if ($this->User_Model->ubahNilai($nilai)) { }
    }
  }

  public function download_berkas_kp()
  {
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/kp/unduh_berkas_kp');
      $this->load->view('templates/footer');
    } else { }
  }

  public function info_kp_dosen()
  {
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/dosen/info_kp');
      $this->load->view('templates/footer');
    } else { }
  }

  public function bimbingan_kp()
  {
    if ($this->form_validation->run() == FALSE) {

      $data['mahasiswa'] = $this->Dosen_Model->getMahasiswa();

      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/dosen/bimbingan', $data);
      $this->load->view('templates/footer');
    } else { }
  }

  public function ubahMahasiswa($nim)
  {
    $this->form_validation->set_rules('nilai_UTS', 'Nilai_UTS', 'required');
    $this->form_validation->set_rules('nilai_UAS', 'Nilai_UAS', 'required');

    if ($this->form_validation->run() == FALSE) {
      $data['nim'] = $nim;
      // $mahasiswa = $this->User_Model->getMahasiswa($nim);

      // $data['nim'] = $nim;

      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/dosen/ubahMahasiswa', $data);
      $this->load->view('templates/footer');
    } else {

      // $mahasiswa = $this->User_Model->getMahasiswa($nim);
      // var_dump($mahasiswa);
      // die;

      $nilai = [
        'nim' => $nim,
        'uts' => $this->input->post('nilai_UTS'),
        'uas' => $this->input->post('nilai_UAS')
      ];

      if ($this->Dosen_Model->ubahNilai($nilai)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
      } else {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>');
      }

      redirect('home/bimbingan_kp');
    }
  }

  public function notifikasi() {

    $data['notifikasi'] = $this->User_Model->getNotifikasi();

    $this->load->view('templates/header', $this->data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('home/user/notifikasi', $data);
    $this->load->view('templates/footer');
  }

  public function editNotifikasi($id) {
    $this->form_validation->set_rules('notifikasi', 'Notifiaksi', 'required', [
      'required' => 'Harus diisi ketika ingin menambahkan'
    ]);

    if($this->form_validation->run() == FALSE) {
      $result = $this->User_Model->getOneNotifikasi($id);
      // var_dump($result);
      $data['pesan'] = $result['pesan'];

      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/user/ubah_notifikasi', $data);
      $this->load->view('templates/footer');
    } else {
      $data = [
        'pesan' => $this->input->post('notifikasi'),
        'created_at' => time()
      ];

      if($this->User_Model->setNotifikasi($data)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
      } else {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>');
      }

      redirect('home/notifikasi');

    }
  }

  public function tambahNotifikasi() {
    $this->form_validation->set_rules('notifikasi', 'Notifiaksi', 'required', [
      'required' => 'Harus diisi ketika ingin menambahkan'
    ]);

    if($this->form_validation->run() == FALSE) {
      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('home/user/tambah_notifikasi');
      $this->load->view('templates/footer');
    } else {
      $data = [
        'pesan' => $this->input->post('notifikasi'),
        'created_at' => time()
      ];

      if($this->User_Model->setNotifikasi($data)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
      } else {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data gagal disimpan!</div>');
      }

      redirect('home/notifikasi');

    }
  }

  public function hapusNotifikasi($id) {
    if($this->User_Model->deleteNotifikasi($id)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
    } else {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data Gagal dihapus!</div>');
    }

    redirect('home/notifikasi');
  }
}
