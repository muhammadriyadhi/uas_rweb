    <!-- Begin Page Content -->
    <div class="container-fluid">

      <!-- Page Heading -->
      <h1 class="h3 mb-4 text-gray-800">Notifikasi</h1>

      <div class="row">
        <div class="text-center col-10 mx-auto">
          <?= $this->session->flashdata('pesan'); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-10 col-lg-10 mx-auto py-2">
          <a href="<?= base_url('home/tambahNotifikasi'); ?>" class="btn btn-primary float-right">Tambah Notifikasi</a>
        </div>
        <div class="col-xl-10 col-lg-10 mx-auto">
          <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Notifikasi</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Pesan</th>
                    <th scope="col">Dibuat</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i = 1; ?>
                <?php foreach($notifikasi as $notif): ?>
                  <tr>
                    <th scope="col"><?= $i++; ?></th>
                    <td width="50%"><?= $notif['pesan']; ?></td>
                    <td><?= date('d M Y', $notif['created_at']); ?></td>
                    <td>
                      <a href="<?= base_url('home/editNotifikasi/').$notif['id']; ?>" class="badge badge-primary">Edit</a>
                      <a href="<?= base_url('home/hapusNotifikasi/').$notif['id']; ?>" class="badge badge-danger">Hapus</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>

      </div>
      <!-- /.container-fluid -->