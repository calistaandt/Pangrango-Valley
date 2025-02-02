<main id="main" class="main">
  <div class="pagetitle">
    <h1>Form Update Barang</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/barang') ?>">Data Barang</a></li>
        <li class="breadcrumb-item active">Update <?= $barang['nama_barang']; ?></li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Update Data Barang</h5>
            <form action="<?= site_url('admin/update_barang/' . $barang['kode_barang']) ?>" method="post">
              <div class="row mb-3">
                  <label for="kode_barang" class="col-sm-2 col-form-label">Kode Barang</label>
                  <div class="col-sm-10">
                      <input type="text" name="kode_barang" id="kode_barang" class="form-control" value="<?= set_value('kode_barang', $barang['kode_barang']) ?>">
                      <?= form_error('kode_barang', '<small class="text-danger">', '</small>') ?>
                  </div>
              </div>
              <div class="row mb-3">
                  <label for="nama_barang" class="col-sm-2 col-form-label">Nama Barang</label>
                  <div class="col-sm-10">
                      <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?= set_value('nama_barang', $barang['nama_barang']) ?>">
                      <?= form_error('nama_barang', '<small class="text-danger">', '</small>') ?>
                  </div>
              </div>
              <div class="row mb-3">
                  <label for="isi_karton" class="col-sm-2 col-form-label">Isi Karton</label>
                  <div class="col-sm-10">
                      <input type="number" name="isi_karton" id="isi_karton" class="form-control" value="<?= set_value('isi_karton', $barang['isi_karton']) ?>">
                      <?= form_error('isi_karton', '<small class="text-danger">', '</small>') ?>
                  </div>
              </div>
              <div class="row mb-3">
                  <label for="satuan" class="col-sm-2 col-form-label">Jumlah Satuan</label>
                  <div class="col-sm-10">
                      <select name="satuan" id="satuan" class="form-control">
                          <option value="KG" <?= set_value('satuan', $barang['satuan']) == 'KG' ? 'selected' : '' ?>>KG</option>
                          <option value="EA" <?= set_value('satuan', $barang['satuan']) == 'EA' ? 'selected' : '' ?>>EA</option>
                      </select>
                      <?= form_error('satuan', '<small class="text-danger">', '</small>') ?>
                  </div>
              </div>
              <div class="row mb-3">
                  <label for="harga" class="col-sm-2 col-form-label">Harga Satuan</label>
                  <div class="col-sm-10">
                      <input type="text" name="harga" id="harga" class="form-control" value="<?= set_value('harga', $barang['harga']) ?>">
                      <?= form_error('harga', '<small class="text-danger">', '</small>') ?>
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-sm-10 offset-sm-2">
                      <button type="submit" class="btn btn-primary">Update Barang</button>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
