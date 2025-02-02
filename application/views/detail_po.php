<main id="main" class="main">
  <div class="pagetitle">
    <h1>Detail PO</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dataPO'); ?>">Data PO</a></li>
        <li class="breadcrumb-item active">Detail PO</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Detail PO: <?= $po_details[0]['no_po']; ?></h5>
              <a href="<?= site_url('admin/faktur/' . $po_details[0]['no_po']); ?>" class="btn btn-info btn-sm" style="color:white;">Faktur</a>
            </div>

            <table class="table table-hover datatable">
              <thead>
                <tr style="text-align: center;">
                  <th style="text-align: center;">Nama Barang</th>
                  <th style="text-align: center;">Jumlah Pesan</th>
                  <th style="text-align: center;">Isi Karton</th>
                  <th style="text-align: center;">Jumlah Satuan</th>
                  <th style="text-align: center;">Harga Per Karton</th>
                  <th style="text-align: center;">Total Harga</th>
                  <th style="text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($po_details as $barang): ?>
                <tr style="text-align: center;">
                  <td style="text-align: right;"><?= $barang['nama_barang']; ?></td>
                  <td><?= $barang['pesan']; ?></td>
                  <td><?= $barang['isi_karton']; ?></td>
                  <td><?= $barang['jumlah_satuan']; ?> <?= $barang['satuan']; ?></td>
                  <td><?= number_format($barang['harga'], 0, ',', '.'); ?></td>
                  <td><?= number_format($barang['total_harga'], 0, ',', '.'); ?></td>
                  <td>
                    <a href="<?= site_url('admin/update_detail_po/' . $barang['no_po'] . '/' . $barang['kode_barang']); ?>" class="btn btn-warning btn-sm">
                          <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= site_url('admin/delete_detail_po/' . $barang['kode_barang']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                          <i class="bi bi-trash"></i>
                    </a>
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
