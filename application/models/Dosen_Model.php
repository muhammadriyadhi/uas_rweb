<?php

class Dosen_Model extends CI_Model
{
  public function getMahasiswa()
  {
    $niy = $this->session->userdata('id');

    $user = $this->db->get_where('dosen', ['user_Id' => $niy])->row_array();

    $query = "select DISTINCT mahasiswa.nim, mahasiswa.nama, kp.judul from kp join mahasiswa on kp.id = mahasiswa.kode_kp join dosen on kp.dosen_niy = dosen.niy where dosen.user_Id = $user[user_Id]";

    return $this->db->query($query)->result_array();
  }
}
