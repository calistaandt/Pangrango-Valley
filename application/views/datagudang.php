<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data Gudang</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Gudang</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Daftar Barang Per Hari</h5>

            <table class="table table-hover datatable">
              <thead>
                <tr>
                  <th style="text-align:center;">No</th>
                  <th style="text-align:center;">Tanggal</th>
                  <th style="text-align:center;">Total Barang</th>
                  <th style="text-align:center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($barang_per_hari as $data): ?>
                  <tr style="text-align:center;">
                    <td><?= $no++; ?></td>
                    <td><?= date('d/m/Y', strtotime($data['tanggal'])); ?></td>
                    <td><?= $data['total_barang']; ?></td>
                    <td>
                      <a href="<?= site_url('admin/detail_gudang/' . $data['tanggal']); ?>" class="btn btn-info" style="color:white;">Detail</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
