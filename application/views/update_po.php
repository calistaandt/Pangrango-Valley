<main id="main" class="main">
  <div class="pagetitle">
    <h1>Form Update Data PO</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dataPO') ?>">Data PO</a></li>
        <li class="breadcrumb-item active">Update Data PO</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Form Edit Data PO</h5>
            <?= $this->session->flashdata('message'); ?>

            <form action="<?= site_url('admin/update_po/' . $po['no_po']); ?>" method="post" enctype="multipart/form-data">
              
              <div class="row mb-3">
                <label for="no_po" class="col-sm-2 col-form-label">No. PO</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" id="no_po" name="no_po" value="<?= $po['no_po']; ?>" readonly>
                </div>
              </div>

              <div class="row mb-3">
                <label for="store_code" class="col-sm-2 col-form-label">Toko</label>
                <div class="col-sm-10">
                  <select class="form-control" id="store_code" name="store_code" required>
                    <option value="">Pilih Toko</option>
                    <?php foreach ($toko as $t): ?>
                      <option value="<?= $t['store_code']; ?>" <?= $po['store_code'] == $t['store_code'] ? 'selected' : ''; ?>>
                        <?= $t['store_name']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label for="tgl_po" class="col-sm-2 col-form-label">Tanggal PO</label>
                <div class="col-sm-10">
                  <input class="form-control" type="date" id="tgl_po" name="tgl_po" value="<?= date('Y-m-d', strtotime($po['tgl_po'])); ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <label for="tgl_kirim" class="col-sm-2 col-form-label">Tanggal Kirim</label>
                <div class="col-sm-10">
                  <input class="form-control" type="date" id="tgl_kirim" name="tgl_kirim" value="<?= date('Y-m-d', strtotime($po['tgl_kirim'])); ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <label for="total" class="col-sm-2 col-form-label">Total Harga PO</label>
                <div class="col-sm-10">
                  <input class="form-control" type="number" id="total" name="total" value="<?= $po['total']; ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Update PO</button>
                </div>
              </div>

            </form>
            
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
