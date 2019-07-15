    <!-- Begin Page Content -->
    <div class="container-fluid">

      <!-- Page Heading -->
      <h1 class="h3 mb-4 text-gray-800">Manajemen KP</h1>

      <div class="row">
        <div class="col-xl-8 col-lg-7 mx-auto">
          <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Notifikasi</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
              <form action="<?= base_url('home/tambahNotifikasi') ?>" method="post">
                <div class="form-group">
                  <label for="notifikasi">Berita Notifikasi</label>
                  <textarea class="form-control" name="notifikasi" id="notifikasi""><?= $pesan; ?></textarea>
                  <?= form_error('notifikasi', '<small class="text-danger">', '</small>'); ?>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ubah berita</button>
              </form>
            </div>
          </div>

        </div>

      </div>
      <!-- /.container-fluid -->