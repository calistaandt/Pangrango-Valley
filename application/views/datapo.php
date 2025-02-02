<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data PO Toko</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Data PO</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Daftar PO</h5>
              <a href="<?= site_url('admin/formPO'); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah PO
              </a>
            </div>

            <?php if ($this->session->flashdata('message')): ?>
              <?= $this->session->flashdata('message'); ?>
            <?php endif; ?>

            <?php
            usort($po_list, function ($a, $b) {
                return strtotime($b['tgl_po']) - strtotime($a['tgl_po']);
            });
            ?>

            <table class="table table-hover datatable text-center">
              <thead>
                <tr>
                  <th style="text-align: center;">No. PO</th>
                  <th style="text-align: center;">Toko</th>
                  <th style="text-align: center;">Tanggal PO</th>
                  <th style="text-align: center;">Tanggal Kirim</th>
                  <th style="text-align: center;">Total Harga</th>
                  <th style="text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($po_list as $po): ?>
                  <tr>
                    <td>
                      <a href="<?= site_url('admin/detail_po/' . $po['no_po']); ?>">
                        <?= htmlspecialchars($po['no_po'], ENT_QUOTES, 'UTF-8'); ?>
                      </a>
                    </td>
                    <td><?= htmlspecialchars($po['store_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= date('d/m/Y', strtotime($po['tgl_po'])); ?></td>
                    <td><?= date('d/m/Y', strtotime($po['tgl_kirim'])); ?></td>
                    <td>Rp <?= number_format($po['total'], 0, ',', '.'); ?></td>
                    <td>
                      <a href="<?= site_url('admin/update_po/' . $po['no_po']); ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="<?= site_url('admin/delete_po/' . $po['no_po']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
