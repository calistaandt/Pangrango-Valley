<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data LOP</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Data LOP</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Daftar LOP</h5>
              <a href="<?= site_url('admin/formLOP'); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah LOP
              </a>
            </div>

            <?php if ($this->session->flashdata('message')): ?>
              <?= $this->session->flashdata('message'); ?>
            <?php endif; ?>

            <table class="table table-hover datatable text-center">
              <thead>
                <tr>
                  <th style="text-align: center;">No</th>
                  <th style="text-align: center;">Nomor LOP</th>
                  <th style="text-align: center;">Tanggal LOP</th>
                  <th style="text-align: center;">Jatuh Tempo</th>
                  <th style="text-align: center;">Total Tagihan</th>
                  <th style="text-align: center;">Pembayaran</th>
                  <th style="text-align: center;">Sisa</th>
                  <th style="text-align: center;">Faktur Belum Bayar</th>
                  <th style="text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($lop)): ?>
                    <?php $i = 1; ?>
                    <?php foreach ($lop as $item): ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++; ?></td>
                        <td style="text-align: center;">
                          <a href="<?= site_url('admin/detail_lop/' . $item['no_lop']); ?>">
                            <?= htmlspecialchars($item['no_lop']); ?>
                          </a>
                        </td>
                        <td style="text-align: center;"><?= htmlspecialchars($item['tgl_lop']); ?></td>
                        <td style="text-align: center;"><?= $item['tempo']; ?></td>
                        <td style="text-align: center;">Rp <?= number_format($item['total'], 0, ',', '.'); ?></td>
                        <td style="text-align: center;">
                            <a href="<?= site_url('admin/pembayaran/' . $item['no_lop']); ?>" class="btn btn-link">
                                <?= number_format($item['pembayaran'], 0, ',', '.'); ?>
                            </a>
                        </td>
                        <td style="text-align: center;">
                          Rp <span id="sisa-<?= $item['no_lop']; ?>"><?= number_format($item['sisa'], 0, ',', '.'); ?></span>
                        </td>
                        <td style="text-align: center;">
                            <?php if (!empty($item['faktur_belum_bayar'])): ?>
                                <?= implode(', ', $item['faktur_belum_bayar']); ?>
                            <?php else: ?>
                                <span>Tidak ada</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                          <a href="<?= site_url('admin/update_lop/' . $item['no_lop']); ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <a href="<?= site_url('admin/delete_lop/' . $item['no_lop']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            <i class="bi bi-trash"></i>
                          </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                      <td colspan="7">Tidak ada data LOP.</td>
                    </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
