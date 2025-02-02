<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pangrango | <?= $title; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url('assets/img/logo.jpeg'); ?>" rel="icon">
  <link href="<?= base_url('assets/img/apple-touch-icon.png'); ?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/boxicons/css/boxicons.min.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/quill/quill.snow.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/quill/quill.bubble.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/remixicon/remixicon.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/simple-datatables/style.css'); ?>" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= base_url('assets/css/style.css'); ?>" rel="stylesheet">

</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="<?= base_url(); ?>" class="logo d-flex align-items-center w-auto">
                  <img src="<?= base_url('assets/img/logo.jpeg'); ?>" alt="">
                  <span class="d-none d-lg-block" style="font-size:25px;">Pangrango</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Buat Akun</h5>
                  </div>
                  <?php if (!empty($pesan)): ?>
                      <?= $pesan; ?>
                  <?php endif; ?>

                    <!-- Form Start -->
                    <?= form_open('login/registrasi', ['class' => 'row g-3 needs-validation', 'novalidate' => true]); ?>

                    <div class="col-12">
                        <label for="nama" class="form-label">Nama</label>
                        <input 
                            type="text" 
                            name="nama" 
                            class="form-control" 
                            id="nama" 
                            required>
                        <div class="invalid-feedback">Kolom nama belum diisi!</div>
                    </div>

                    <div class="col-12">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                            <input 
                                type="text" 
                                name="username" 
                                class="form-control" 
                                id="username" 
                                required>
                            <div class="invalid-feedback">Kolom username belum diisi!</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="password1" class="form-label">Password</label>
                        <input 
                            type="password" 
                            name="password1" 
                            class="form-control" 
                            id="password1" 
                            required>
                        <div class="invalid-feedback">Kolom password belum diisi!</div>
                    </div>

                    <div class="col-12">
                        <label for="password2" class="form-label">Konfirmasi Password</label>
                        <input 
                            type="password" 
                            name="password2" 
                            class="form-control" 
                            id="password2" 
                            required>
                        <div class="invalid-feedback">Kolom password belum diisi!</div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">Buat Akun</button>
                    </div>

                    <div class="col-12" style="text-align:center;">
                        <p class="small mb-0">
                            Sudah Punya Akun? 
                            <a href="<?= base_url('login/index'); ?>">Login</a>
                        </p>
                    </div>

                    <?= form_close(); ?>
                    <!-- Form End -->

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/simple-datatables/simple-datatables.js'); ?>"></script>
  <script src="<?= base_url('assets/js/main.js'); ?>"></script>

</body>

</html>
