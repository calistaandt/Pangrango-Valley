<main id="main" class="main">
  <div class="pagetitle">
    <h1>Form Update Data LOP</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dataLOP') ?>">Data LOP</a></li>
        <li class="breadcrumb-item active">Update Data LOP</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Form Edit Data LOP</h5>
            <?= $this->session->flashdata('message'); ?>

            <form action="<?= site_url('admin/update_lop/' . $lop['no_lop']); ?>" method="post">
              <div class="row mb-3">
                <label for="no_lop" class="col-sm-2 col-form-label">No. LOP</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" id="no_lop" name="no_lop" value="<?= htmlspecialchars($lop['no_lop']); ?>">
                </div>
              </div>

              <div class="row mb-3">
                <label for="tgl_lop" class="col-sm-2 col-form-label">Tanggal LOP</label>
                <div class="col-sm-10">
                  <input class="form-control" type="date" id="tgl_lop" name="tgl_lop" value="<?= htmlspecialchars($lop['tgl_lop']); ?>">
                </div>
              </div>

              <div class="row mb-3">
                <label for="total" class="col-sm-2 col-form-label">Total</label>
                <div class="col-sm-10">
                  <input class="form-control" type="number" id="total" name="total" value="<?= htmlspecialchars($lop['total']); ?>">
                </div>
              </div>

              <div class="row mb-3">
                <label for="sisa" class="col-sm-2 col-form-label">Sisa</label>
                <div class="col-sm-10">
                  <input class="form-control" type="number" id="sisa" name="sisa" value="<?= htmlspecialchars($lop['sisa']); ?>">
                </div>
              </div>

              <!-- Tombol Submit -->
              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Update LOP</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
