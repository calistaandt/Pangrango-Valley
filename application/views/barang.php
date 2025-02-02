<body>
<main id="main" class="main">
  <!-- Section Daftar Barang -->
  <div class="pagetitle">
    <h1>Data Barang</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Barang</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <!-- Header Card -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="card-title">Daftar Barang</h5>
              <a href="<?= site_url('admin/addbarang'); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Barang
              </a>
            </div>
            <?php if ($this->session->flashdata('message')): ?>
              <?= $this->session->flashdata('message'); ?>
            <?php endif; ?>

            <!-- Tabel Data Barang -->
            <div class="table-responsive">
              <table class="table table-hover datatable text-center">
                <thead>
                  <tr style="text-align:center;">
                    <th style="text-align:center;">No</th>
                    <th style="text-align:center;">Kode Barang</th>
                    <th style="text-align:center;">Nama Barang</th>
                    <th style="text-align:center;">Isi Karton</th>
                    <th style="text-align:center;">Satuan</th>
                    <th style="text-align:center;">Harga</th>
                    <th style="text-align:center;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($barang)): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($barang as $item): ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $item['kode_barang'] ?></td>
                        <td style="text-align:left;"><?= $item['nama_barang'] ?></td>
                        <td>1 x <?= $item['isi_karton'] ?></td>
                        <td><?= $item['satuan'] ?></td>
                        <td><?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td>
                          <a href="<?= site_url('admin/update_barang/' . $item['kode_barang']); ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <a href="<?= site_url('admin/delete_barang/' . $item['kode_barang']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            <i class="bi bi-trash"></i>
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="8" style="text-align: center;">Tidak Ada Data</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>
</body>
