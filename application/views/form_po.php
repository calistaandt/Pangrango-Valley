<main id="main" class="main">
    <div class="pagetitle">
        <h1>Form PO</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dataPO') ?>">Data PO</a></li>
                <li class="breadcrumb-item active">Tambah Data PO</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Tambah Data PO</h5>
                        <?= $this->session->flashdata('message'); ?>
                        <form action="<?= site_url('admin/save_po'); ?>" method="post">
                            <!-- Form Header -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">No. PO</label>
                                <div class="col-sm-10">
                                    <input type="text" name="no_po" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Toko</label>
                                <div class="col-sm-10">
                                    <select name="store_code" class="form-control" required>
                                        <option value="">Pilih Toko</option>
                                        <?php foreach ($toko as $t): ?>
                                            <option value="<?= $t['store_code']; ?>"><?= $t['store_code']; ?> - <?= $t['store_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tanggal PO</label>
                                <div class="col-sm-5">
                                    <input type="date" name="tgl_po" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                                </div>
                                <label class="col-sm-2 col-form-label">Tanggal Kirim</label>
                                <div class="col-sm-3">
                                    <input type="date" name="tgl_kirim" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                                </div>
                            </div>

                            <!-- Tabel Barang -->
                            <table id="product_table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="product_row">
                                    <td>
                                    <select name="kode_barang[]" class="form-control nama_barang select2" required>
                                        <option value="">Pilih Barang</option>
                                        <?php foreach ($barang as $b): ?>
                                            <option value="<?= $b['kode_barang']; ?>" data-satuan="<?= $b['satuan']; ?>" data-harga="<?= $b['harga']; ?>">
                                                <?= $b['nama_barang']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    </td>
                                    <td><input type="number" name="pesan[]" class="form-control qty" required></td>
                                    <td><input type="text" name="satuan[]" class="form-control satuan" readonly></td>
                                    <td><input type="number" name="harga[]" class="form-control harga"></td>
                                    <td><input type="number" name="total_harga[]" class="form-control total_harga" readonly></td>
                                    <td><button type="button" class="btn btn-danger remove_row"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                            <button type="button" id="add_product" class="btn btn-primary">Tambah Barang</button>

                            <div class="row mt-3">
                                <label class="col-sm-2 col-form-label">Total Harga</label>
                                <div class="col-sm-10">
                                    <input type="number" name="total" id="total" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-success">Simpan PO</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateRowValues(row, isDropdownChange = false) {
        const selected = row.querySelector('.nama_barang').selectedOptions[0];
        const satuan = selected.getAttribute('data-satuan') || '';
        const defaultHarga = parseFloat(selected.getAttribute('data-harga')) || 0;

        const qtyInput = row.querySelector('.qty');
        const hargaInput = row.querySelector('.harga');
        const totalHargaInput = row.querySelector('.total_harga');

        const qty = parseFloat(qtyInput.value) || 0;

        row.querySelector('.satuan').value = satuan;

        if (isDropdownChange) {
            hargaInput.value = defaultHarga;
        }

        const harga = parseFloat(hargaInput.value) || 0;
        totalHargaInput.value = (harga * qty).toFixed(0);

        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.product_row').forEach(row => {
            total += parseFloat(row.querySelector('.total_harga').value) || 0;
        });
        document.getElementById('total').value = total.toFixed(0);
    }

    function attachEvents(row) {
        row.querySelector('.nama_barang').addEventListener('change', () => updateRowValues(row, true));
        row.querySelector('.qty').addEventListener('input', () => updateRowValues(row));
        row.querySelector('.harga').addEventListener('input', () => updateRowValues(row));
        row.querySelector('.remove_row').addEventListener('click', () => {
            row.remove();
            updateTotal();
        });
    }

    document.getElementById('add_product').addEventListener('click', () => {
        const newRow = document.querySelector('.product_row').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelector('.nama_barang').value = '';
        document.querySelector('#product_table tbody').appendChild(newRow);
        attachEvents(newRow);
    });

    document.querySelectorAll('.product_row').forEach(row => attachEvents(row));
});

</script>
