<main id="main" class="main">
  <div class="pagetitle">
    <h1>Form PO</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/detail_po/' . $detail['no_po']); ?>">Detail PO</a></li>
        <li class="breadcrumb-item active">Update Detail PO</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Form Edit Detail PO</h5>

            <?= $this->session->flashdata('message'); ?>

            <form action="<?= site_url('admin/update_detail_po/' . $detail['no_po'] . '/' . $detail['kode_barang']); ?>" 
                method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="kode_barang" class="col-sm-2 col-form-label">Kode Barang</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="kode_barang" name="kode_barang" 
                            value="<?= isset($detail['kode_barang']) ? $detail['kode_barang'] : ''; ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="nama_barang" class="col-sm-2 col-form-label">Nama Barang</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="nama_barang" name="nama_barang" 
                            value="<?= isset($detail['nama_barang']) ? $detail['nama_barang'] : ''; ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pesan" class="col-sm-2 col-form-label">Jumlah Pesan</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" id="pesan" name="pesan" 
                            value="<?= isset($detail['pesan']) ? $detail['pesan'] : ''; ?>">
                        <?= form_error('pesan', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" id="harga" name="harga" 
                            value="<?= isset($detail['harga']) ? $detail['harga'] : ''; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="total_harga" class="col-sm-2 col-form-label">Total Harga</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="total_harga" name="total_harga" 
                            value="<?= isset($detail['pesan'], $detail['harga']) ? $detail['pesan'] * $detail['harga'] : ''; ?>" readonly>
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
