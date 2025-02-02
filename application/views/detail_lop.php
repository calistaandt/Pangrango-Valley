<main id="main" class="main">
  <div class="pagetitle">
    <h1>Detail LOP</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dataLOP'); ?>">Data LOP</a></li>
        <li class="breadcrumb-item active">Detail LOP</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Nomor LOP: <?= $lop_details[0]['no_lop']; ?></h5>
            </div>
            <?php if ($this->session->flashdata('message')): ?>
              <?= $this->session->flashdata('message'); ?>
            <?php endif; ?>
            <table class="table table-hover datatable">
              <thead>
                <tr style="text-align: center;">
                  <th style="text-align:center;">No</th>
                  <th style="text-align:center;">Tanggal Faktur</th>
                  <th style="text-align:center;">Nomor Faktur</th>
                  <th style="text-align:center;">Total</th>
                  <th style="text-align:center;">Tanggal Bayar</th>
                  <th style="text-align:center;">Lokasi Barang</th>
                  <th style="text-align:center;">Tukar Faktur</th>
                  <th style="text-align:center;">Tanda Terima</th>
                  <th style="text-align:center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($lop_details)): ?>
                    <?php $i = 1; ?>
                    <?php foreach ($lop_details as $detail): ?>
                    <tr style="text-align:center;">
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($detail['tgl_invoice']); ?></td>
                        <td><?= htmlspecialchars($detail['no_invoice']); ?></td>
                        <td><?= number_format($detail['pembayaran'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($detail['tgl_pembayaran']); ?></td>
                        <td style="text-align:left;"><?= htmlspecialchars($detail['lokasi']); ?></td>
                        <td>
                            <form action="<?= base_url('admin/update_tanggal_tukar'); ?>" method="POST">
                                <input type="hidden" name="no_invoice" value="<?= htmlspecialchars($detail['no_invoice']); ?>">
                                <input type="hidden" name="no_lop" value="<?= htmlspecialchars($lop_details[0]['no_lop']); ?>">
                                <input type="date" name="tgl_tukar" value="<?= htmlspecialchars($detail['tgl_tukar']); ?>" class="form-control form-control-sm" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>
                            <form action="<?= base_url('admin/update_tanda_terima'); ?>" method="POST">
                                <input type="hidden" name="no_invoice" value="<?= htmlspecialchars($detail['no_invoice']); ?>">
                                <input type="hidden" name="no_lop" value="<?= htmlspecialchars($lop_details[0]['no_lop']); ?>">
                                <select 
                                    name="ttd_terima" 
                                    class="form-select form-select-sm 
                                        <?= $detail['ttd_terima'] === 'Ada' ? 'bg-success text-white' : 'bg-secondary text-white'; ?>" 
                                    onchange="this.form.submit()"
                                >
                                    <option 
                                        value="Ada" 
                                        <?= $detail['ttd_terima'] === 'Ada' ? 'selected' : ''; ?> 
                                        class="bg-success text-white"
                                    >
                                        Ada
                                    </option>
                                    <option 
                                        value="Tidak" 
                                        <?= $detail['ttd_terima'] === 'Tidak' ? 'selected' : ''; ?> 
                                        class="bg-secondary text-white"
                                    >
                                        Tidak
                                    </option>
                                </select>
                            </form>
                        </td>
                        <td>
                          <a href="<?= site_url('admin/update_detail_lop/' . $detail['no_lop'] . '/' . urlencode(str_replace('/', '-', $detail['no_invoice']))); ?>" class="btn btn-warning btn-sm">
                              <i class="bi bi-pencil"></i>
                          </a>
                          <a href="<?= site_url('admin/delete_detail_lop/' . $detail['no_lop'] . '/' . urlencode(str_replace('/', '-', $detail['no_invoice']))); ?>"  onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                          </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="8">Tidak ada detail untuk LOP ini.</td>
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
