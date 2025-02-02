<main id="main" class="main">
  <div class="pagetitle">
    <h1>Pembayaran LOP: <?= htmlspecialchars(isset($lop['no_lop']) ? $lop['no_lop'] : ''); ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/datalop') ?>">Data LOP</a></li>
        <li class="breadcrumb-item active">Pembayaran</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Daftar Invoice</h5>

            <form method="post" action="<?= site_url('admin/proses_pembayaran'); ?>">
              <input type="hidden" name="no_lop" value="<?= htmlspecialchars(isset($lop['no_lop']) ? $lop['no_lop'] : ''); ?>">

              <div id="invoice-list">
                <?php if (!empty($invoices)): ?>
                  <?php 
                  $chunks = array_chunk($invoices, 4);
                  foreach ($chunks as $chunk): ?>
                    <div class="row">
                      <?php foreach ($chunk as $invoice): ?>
                        <div class="col-md-3">
                          <div class="form-check">
                            <input 
                              class="form-check-input invoice-check" 
                              type="checkbox" 
                              value="<?= htmlspecialchars(isset($invoice['pembayaran']) ? $invoice['pembayaran'] : 0); ?>" 
                              name="invoice[<?= htmlspecialchars(isset($invoice['no_invoice']) ? $invoice['no_invoice'] : ''); ?>]" 
                              id="invoice-<?= htmlspecialchars(isset($invoice['no_invoice']) ? $invoice['no_invoice'] : ''); ?>"
                              <?= isset($invoice['status']) && strtolower($invoice['status']) === 'bayar' ? 'checked' : ''; ?> 
                            >
                            <label class="form-check-label" for="invoice-<?= htmlspecialchars(isset($invoice['no_invoice']) ? $invoice['no_invoice'] : ''); ?>">
                              <?= htmlspecialchars(isset($invoice['no_invoice']) ? $invoice['no_invoice'] : ''); ?> 
                              (<?= number_format(isset($invoice['pembayaran']) ? $invoice['pembayaran'] : 0, 0, ',', '.'); ?>)
                            </label>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p>Tidak ada invoice untuk LOP ini.</p>
                <?php endif; ?>
              </div>

              <br><br>
              <p><strong>Total Pembayaran:</strong> Rp <span id="total-pembayaran">0</span></p>
              <br>
              <p><strong>Sisa Tagihan:</strong> Rp 
                <span id="sisa-tagihan"><?= number_format(isset($lop['sisa']) ? $lop['sisa'] : 0, 0, ',', '.'); ?></span>
              </p>

              <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.invoice-check');
    const totalPembayaranEl = document.getElementById('total-pembayaran');
    const sisaTagihanEl = document.getElementById('sisa-tagihan');
    const totalTagihan = <?= isset($lop['total']) ? json_encode(floatval($lop['total'])) : 0; ?>;

    const updateTotals = () => {
        let totalPembayaran = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                totalPembayaran += parseFloat(cb.value || 0);
            }
        });

        const sisa = totalTagihan - totalPembayaran;

        totalPembayaranEl.textContent = totalPembayaran.toLocaleString('id-ID');
        sisaTagihanEl.textContent = sisa.toLocaleString('id-ID');
    };

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            updateTotals();
        });
    });

    updateTotals();
});
</script>
