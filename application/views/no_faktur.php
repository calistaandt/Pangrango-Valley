<main id="main" class="main">
  <div class="pagetitle">
    <h1>Form No. Faktur</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dataPO'); ?>">Data PO</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/detail_po/' . $po['no_po']); ?>">Detail PO</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/faktur/' . $po['no_po']); ?>">Faktur</a></li>
        <li class="breadcrumb-item active">No. Faktur</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Masukkan No. Faktur dengan Benar!</h5>
            <form action="<?= site_url('admin/no_faktur/' . $po['no_po']) ?>" method="post">
              <div class="row mb-3">
                <label for="no_faktur" class="col-sm-2 col-form-label">No. Faktur</label>
                <div class="col-sm-10">
                    <input type="text" name="no_faktur" id="no_faktur" class="form-control" value="<?= isset($po['no_faktur']) ? $po['no_faktur'] : '' ?>" required>
                    <?= form_error('no_faktur', '<small class="text-danger">', '</small>') ?>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
