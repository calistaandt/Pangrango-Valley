<main id="main" class="main">
  <div class="pagetitle">
    <h1>Form LOP</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dataLOP') ?>">Data LOP</a></li>
        <li class="breadcrumb-item active">Tambah Data LOP</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Form Tambah Data LOP</h5>
            <?= $this->session->flashdata('message'); ?>
            <form action="<?= site_url('admin/upload_excel'); ?>" method="post" enctype="multipart/form-data">
                <!-- Input untuk LOp_no -->
                <div class="row mb-3">
                    <label for="no_lop" class="col-sm-2 col-form-label">Nomor LOP</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="no_lop" name="no_lop" required>
                    </div>
                </div>
                <!-- Tanggal LOP tanpa waktu -->
                <div class="row mb-3">
                    <label for="tgl_lop" class="col-sm-2 col-form-label">Tanggal LOP</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="date" id="tgl_lop" name="tgl_lop" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <!-- Input untuk upload file Excel -->
                <div class="row mb-3">
                    <label for="excel_file" class="col-sm-2 col-form-label">File Upload</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="file" id="excel_file" name="excel_file" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="total" class="col-sm-2 col-form-label">Total</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" id="total" name="total" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
