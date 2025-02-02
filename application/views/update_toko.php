<main id="main" class="main">
  <div class="pagetitle">
    <h1>Form Update Barang</h1>
    <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/toko') ?>">Data Toko</a></li>
        <li class="breadcrumb-item active">Update <?= $toko['store_name']; ?></li>
    </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Update Data Barang</h5>
            <form action="<?= site_url('admin/update_toko/' . $toko['store_code']) ?>" method="post">
              <div class="row mb-3">
                  <label for="store_code" class="col-sm-2 col-form-label">Kode Toko</label>
                  <div class="col-sm-10">
                      <input type="text" name="store_code" id="store_code" class="form-control" value="<?= set_value('store_code', $toko['store_code']) ?>">
                      <?= form_error('store_code', '<small class="text-danger">', '</small>') ?>
                  </div>
              </div>
              <div class="row mb-3">
                  <label for="store_name" class="col-sm-2 col-form-label">Nama Toko</label>
                  <div class="col-sm-10">
                      <input type="text" name="store_name" id="store_name" class="form-control" value="<?= set_value('store_name', $toko['store_name']) ?>">
                      <?= form_error('store_name', '<small class="text-danger">', '</small>') ?>
                  </div>
              </div>
              <div class="row mb-3">
                  <label for="kode" class="col-sm-2 col-form-label">Inisial Toko</label>
                  <div class="col-sm-10">
                      <input type="text" name="kode" id="kode" class="form-control" value="<?= set_value('kode', $toko['kode']) ?>">
                      <?= form_error('kode', '<small class="text-danger">', '</small>') ?>
                  </div>
              </div>
              <div class="row mb-3">
                  <label for="store_address" class="col-sm-2 col-form-label">Alamat Toko</label>
                  <div class="col-sm-10">
                    <textarea name="store_address" id="store_address" class="form-control"><?= set_value('store_address', $toko['store_address']) ?></textarea>
                    <?= form_error('store_address', '<small class="text-danger">', '</small>') ?>
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-sm-10 offset-sm-2">
                      <button type="submit" class="btn btn-primary">Update Toko</button>
                  </div>
              </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
