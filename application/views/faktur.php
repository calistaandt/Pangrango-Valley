<style>
    @media print {
        body * {
            visibility: hidden;
        }
        footer * {
            visibility: hidden;
            background-color: none;
        }

        #factureTable, #factureTable * {
            visibility: visible;
            border: none; 
        }

        .row.isotope-container, .row.isotope-container * {
            visibility: visible;
            border: 1px solid black; 
        }

        #factureTable {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            margin: 0 0;
        }

        * {
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important;        
        }

        img {
            max-width: 120px;
            height: auto;
        }

        @page {
            margin: 0cm;
        }
    }
</style>


<main id="main" class="main" style="">
<div class="pagetitle">
    <h1>Faktur <?= $po_data['no_po']; ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dataPO'); ?>">Data PO</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/detail_po/' . $po_data['no_po']); ?>">Detail PO</a></li>
        <li class="breadcrumb-item active">Faktur <?= $po_data['no_po']; ?></li>
      </ol>
    </nav>
  </div>
    <section id="about" class="portfolio section">
        <div class="container" data-aos="fade-up">
        <div style="margin-bottom: 20px;">
            <a href="<?= site_url('admin/no_faktur/' . $po_data['no_po']); ?>" class="btn btn-info btn-sm" style="color:white;">Faktur</a>
            <button id="downloadExcel" style="font-size: small;" class="btn btn-success">Excel</button>
            <button id="downloadPDF" style="font-size: small;" class="btn btn-danger">PDF</button>
            <button id="printButton" style="font-size: small;" class="btn btn-primary">Print</button>
            <button id="sendEmail" style="font-size: small; color: white;" class="btn btn-warning">Kirim</button>
        </div>

            <!-- Tabel -->
            <div class="row isotope-container" data-aos="fade-up" data-aos-delay="200">
                <table class="table table-bordered" id="factureTable"  style="font-size:small;">
                    <tr>
                        <td style="text-align: left;" colspan="3">
                            <h4>PANGRANGO VALLEY</h4>
                            <p style="font-size:small;">JL PAKALONGAN NO 6A DS SINDANGJAYA <br> KEC CIPANAS KAB CIANJUR - JABAR <br> Phone: 081392320451 <br> EMAIL: pangrangovalley@gmail.com <br> Website: http//pangrango.id
                        </td>
                        <td style="text-align: center;">
                            <img id="logoImage" src="<?= base_url('assets/img/logo.jpeg'); ?>" alt="Logo Pangrango" style="width: 200px; height: auto;">
                        </td>
                        <td style="text-align: right; font-size:small;" colspan="4">
                            <h3 style="font-weight: bold;">INVOICE</h3>
                            <br>DATE# <?= $po_data['tgl_kirim']; ?>
                            <br>NO PO# <?= $po_data['no_po']; ?>
                            <br>NO INV# <?= $po_data['no_faktur']; ?>
                            <br>KODE SUPPL# J1001H
                        </td>
                    </tr>
                    <tr style="text-align: center; border: none;">
                        <th colspan="3" style="font-weight: bold; color: white; background-color: darkblue;">KEPADA:</th>
                        <th colspan="3" style="font-weight: bold; color: white; background-color: darkblue;">DIKIRIM</th>
                    </tr>
                    <tr>
                        <td colspan="3"><?= $po_data['store_name']; ?> - <?= $po_data['store_code']; ?> <br> <?= $po_data['store_address']; ?></td>
                        <td colspan="3"><?= $po_data['store_name']; ?> - <?= $po_data['store_code']; ?> <br> <?= $po_data['store_address']; ?></td>
                    </tr>
                    <tr style="text-align:center;">
                        <td>ITEM</td>
                        <td colspan="2">NAMA BARANG</td>
                        <td>UNIT</td>
                        <td>HARGA UNIT</td>
                        <td>JUMLAH</td>
                    </tr>
                    <?php foreach ($det_po_data as $index => $item): ?>
                    <tr>
                        <td><?= $item['kode_barang']; ?></td>
                        <td colspan="2"><?= $item['nama_barang']; ?></td>
                        <td style="text-align:center;"><?= $item['pesan']; ?></td>
                        <td>Rp <?= number_format($item['harga'], 2); ?></td>
                        <td>Rp <?= number_format($item['pesan'] * $item['harga'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="7"></td>
                    </tr>
                    <tr>
                        <td colspan="4" rowspan="5">
                            <p style="font-weight:bold;">DITERIMA</p>
                           <br><br><br><br><br><br> <p style="text-align:right;">STEMPEL</p>
                        </td>
                        <td>JUMLAH</td>
                        <td>Rp <?= number_format($total_harga, 2); ?></td>
                    </tr>
                    <tr>
                        <td>PAJAK</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ONGKIR</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>OTHER</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;">TOTAL</td>
                        <td>Rp <?= number_format($total_harga, 2); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
</main>

<script>
    document.getElementById('downloadExcel').addEventListener('click', function () {
        var table = document.getElementById('factureTable');
        if (!table) {
            console.error('Tabel tidak ditemukan!');
            return;
        }
        var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });

        workbook.Sheets["Sheet1"]["!cols"] = [
            { wpx: 150 }, 
            { wpx: 250 }, 
            { wpx: 200 }, 
            { wpx: 100 }, 
            { wpx: 120 },
            { wpx: 120 },
        ];

        XLSX.writeFile(workbook, 'Faktur_' + '<?= $po_data["no_po"]; ?>' + '.xlsx');
    });

    document.getElementById('downloadPDF').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        const logoImage = document.getElementById('logoImage');
        const logoUrl = logoImage.src;
        const img = new Image();
        img.src = logoUrl;

        img.onload = function () {
            const pdfWidth = pdf.internal.pageSize.width;
            pdf.addImage(img, 'PNG', pdfWidth - 60, 10, 40, 20); 

            const table = document.querySelector('.table');
            pdf.autoTable({
                html: table,
                theme: 'grid',
                headStyles: { fontStyle: 'normal', textColor: [0, 0, 0] }, 
                bodyStyles: { halign: 'center' }, 
                margin: { top: 40 },
            });

            pdf.save('FakturPenjualan' + '<?= $po_data['no_po']; ?>' + '.pdf');
        };
    });

    document.getElementById('printButton').addEventListener('click', function () {
        window.print();
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<script>
    document.getElementById('downloadPDF').addEventListener('click', function () {
        const { jsPDF } = window.jspdf; 
        const pdf = new jsPDF('p', 'pt', 'a4'); 
        const element = document.getElementById('factureTable'); 

        html2canvas(element, { scale: 2 }) 
            .then(canvas => {
                const imgData = canvas.toDataURL('image/png'); 
                const imgWidth = 595.28; 
                const pageHeight = 841.89; 
                const imgHeight = (canvas.height * imgWidth) / canvas.width; 
                let heightLeft = imgHeight;

                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft > 0) {
                    position -= pageHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                pdf.save('FakturPenjualan.pdf'); 
            })
            .catch(error => {
                console.error('Error generating PDF:', error); 
            });
    });
</script>

<script>
document.getElementById('sendEmail').addEventListener('click', function () {
    var element = document.getElementById('factureTable');

    if (!element) {
        alert('Faktur tidak ditemukan.');
        return;
    }

    var factureData = element.innerHTML;

    fetch('<?= site_url('admin/send_email'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            email: 'a.pangrangovalley@gmail.com',
            subject: 'Invoice <?= $po_data["no_po"]; ?>',
            message: 'Berikut adalah faktur Anda dalam format Excel.',
            factureData: factureData,
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Gagal menghubungi server');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Faktur berhasil dikirim ke email.');
        } else {
            alert('Gagal mengirim faktur. Pesan: ' + (data.message || 'Tidak diketahui.'));
        }
    })
    .catch(error => {
        console.error('Kesalahan:', error);
        alert('Terjadi kesalahan saat mengirim email.');
    });
});

</script>

