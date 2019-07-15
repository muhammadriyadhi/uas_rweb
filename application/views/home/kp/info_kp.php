    <!-- Begin Page Content -->
    <div class="container-fluid">

      <!-- Page Heading -->
      <h1 class="h3 mb-4 text-gray-800">Informasi KP</h1>

      <div class="row">
        <div class="col-xl-8 col-lg-7 mx-auto text-center">
          <?= $this->session->flashdata('pesan'); ?>
        </div>
      </div>

      <!-- Content Row -->
      <div class="row">
        <div class="col-xl-8 col-lg-7 mx-auto">
        <?php foreach($notifikasi as $notif): ?>
          <div class="alert alert-info" role="alert">
            <p><?= $notif['pesan'] ?></p>
          </div>
        <?php endforeach; ?>
        </div>
      </div>

    </div>
    <!-- /.container-fluid -->