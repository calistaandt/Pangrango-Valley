<body>
<main id="main" class="main">
  <!-- Section Daftar Toko -->
  <div class="pagetitle">
    <h1>Data Toko</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Toko</li>
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
              <h5 class="card-title">Daftar Toko</h5>
              <a href="<?= site_url('admin/addtoko'); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Toko
              </a>
            </div>
            <?php if ($this->session->flashdata('message')): ?>
              <?= $this->session->flashdata('message'); ?>
            <?php endif; ?>

            <!-- Tabel Data Toko -->
            <div class="table-responsive">
              <table class="table table-hover datatable">
                <thead style="text-align: center;">
                  <tr>
                    <th>No</th>
                    <th>Kode Toko</th>
                    <th>Nama Toko</th>
                    <th>Inisial Toko</th>
                    <th>Alamat Toko</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($toko)): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($toko as $item): ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $item['store_code'] ?></td>
                        <td><?= $item['store_name'] ?></td>
                        <td><?= $item['kode'] ?></td>
                        <td><?= $item['store_address'] ?></td>
                        <td>
                          <a href="<?= site_url('admin/update_toko/' . $item['store_code']); ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <a href="<?= site_url('admin/delete_toko/' . $item['store_code']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            <i class="bi bi-trash"></i>
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" style="text-align: center;">No data available</td>
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
