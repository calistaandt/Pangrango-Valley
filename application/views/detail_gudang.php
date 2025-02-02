

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Detail Barang Gudang</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/datagudang') ?>">Data Gudang</a></li>
        <li class="breadcrumb-item active">Detail Barang</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              Detail Barang pada <?= (!empty($tanggal) && strtotime($tanggal)) ? date('d/m/Y', strtotime($tanggal)) : 'Tanggal Tidak Tersedia'; ?>
            </h5>

            <div class="mb-3">
              <button id="downloadExcel" class="btn btn-success btn-sm me-2">Excel</button>
              <button id="downloadPDF" class="btn btn-danger btn-sm me-2">PDF</button>
              <button id="printButton" class="btn btn-primary btn-sm me-2">Print</button>
              <button id="sendEmail" class="btn btn-warning btn-sm text-white">Kirim</button>
            </div>

            <div class="table-responsive">
              <table class="table table-hover" id="datimTable">
                <thead>
                  <tr class="text-center">
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Total</th>
                    <?php foreach ($stores as $kode): ?>
                      <th><?= htmlspecialchars($kode) ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($detail_barang as $data): ?>
                    <tr>
                      <td class="text-center"><?= $no++; ?></td>
                      <td><?= htmlspecialchars($data['nama_barang']); ?></td>
                      <td class="text-center"><?= htmlspecialchars($data['total_pesan']); ?></td>
                      <?php foreach ($stores as $kode): ?>
                        <td class="text-center"><?= isset($data[$kode]) ? htmlspecialchars($data[$kode]) : 0; ?></td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
document.getElementById('downloadExcel')?.addEventListener('click', function () {
    var table = document.getElementById('datimTable');
    if (!table) return alert('Tabel tidak ditemukan!');

    let poNumber = "<?= isset($po_data['no_po']) ? $po_data['no_po'] : 'Unknown' ?>";
    var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    XLSX.writeFile(workbook, 'Datim_' + poNumber + '.xlsx');
});

document.getElementById('downloadPDF')?.addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p', 'pt', 'a4');
    const element = document.getElementById('datimTable');

    html2canvas(element, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        pdf.addImage(imgData, 'PNG', 10, 10, 570, (canvas.height * 570) / canvas.width);
        pdf.save('Datim.pdf');
    });
});

document.getElementById('printButton')?.addEventListener('click', function () {
    window.print();
});
document.getElementById('sendEmail')?.addEventListener('click', async function () {
    var element = document.getElementById('datimTable');
    if (!element) {
        alert('Tabel tidak ditemukan.');
        return;
    }

    let factureData = element.innerHTML.replace(/<script.*?>.*?<\/script>/g, ""); // Hindari XSS

    try {
        let response = await fetch('<?= site_url('admin/send_datim'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                email: 'gudang.pangrangovalley@gmail.com',
                subject: 'Data Timbangan <?= isset($po_data["no_po"]) ? $po_data["no_po"] : "Unknown" ?>',
                message: 'Berikut adalah data timbangan.',
                factureData: factureData, // Jangan di-encode, biarkan HTML tetap utuh
            }),
        });

        let data = await response.json();
        alert(data.success ? 'Datim berhasil dikirim ke email.' : `Gagal mengirim Datim: ${data.message}`);
    } catch (error) {
        console.error('Kesalahan:', error);
        alert('Terjadi kesalahan saat mengirim email.');
    }
});

</script>