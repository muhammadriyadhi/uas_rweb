<?php

class User_Model extends CI_Model
{
  public function getUsers()
  {
    return $this->db->get('user')->result_array();
  }

  public function addUser($data)
  {
    return $this->db->insert('user', $data);
  }

  public function activate($id)
  {
    $user = $this->db->get_where('user', ['id' => $id])->row_array();

    if ($user['is_active'] == 1) {
      $this->db->set('is_active', 0);
    } else {
      $this->db->set('is_active', 1);
    }

    $this->db->where('id', $id);

    return $this->db->update('user');
  }

  public function getSkema()
  {
    return $this->db->get('skema_kp')->result_array();
  }

  public function getDosen()
  {
    return $this->db->get('dosen')->result_array();
  }

  public function daftarKP($user, $kp)
  {
    $this->db->insert('kp', $kp);

    $query = "select * from kp order by id desc limit 1";
    $id_kp = $this->db->query($query)->row_array();

    $user_id = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

    $user['user_id'] = $user_id['id'];
    $user['kode_kp'] = $id_kp['id'];
    $user['created_at'] = time();

    return $this->db->insert('mahasiswa', $user);
  }
}
