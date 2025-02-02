<main id="main" class="main">
    <div class="pagetitle">
        <h1>Form LOP</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/detail_lop/' . $detail['no_lop']); ?>">Detail LOP</a></li>
                <li class="breadcrumb-item active">Update Detail LOP</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Edit Detail LOP</h5>

                        <!-- Flash message -->
                        <?= $this->session->flashdata('message'); ?>

                        <!-- Form untuk update detail LOP -->
                        <form action="<?= site_url('admin/update_detail_lop/' . $detail['no_lop'] . '/' . urlencode(str_replace('/', '-', $detail['no_invoice']))); ?>" method="post">
                            <div class="row mb-3">
                                <label for="no_invoice" class="col-sm-2 col-form-label">Nomor Faktur</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" id="no_invoice" name="no_invoice" 
                                        value="<?= set_value('no_invoice', htmlspecialchars($detail['no_invoice'])); ?>">
                                    <?= form_error('no_invoice', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tgl_invoice" class="col-sm-2 col-form-label">Tanggal Faktur</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="date" id="tgl_invoice" name="tgl_invoice" 
                                        value="<?= set_value('tgl_invoice', htmlspecialchars($detail['tgl_invoice'])); ?>">
                                    <?= form_error('tgl_invoice', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pembayaran" class="col-sm-2 col-form-label">Total</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="number" id="pembayaran" name="pembayaran" 
                                        value="<?= set_value('pembayaran', htmlspecialchars($detail['pembayaran'])); ?>">
                                    <?= form_error('pembayaran', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tgl_pembayaran" class="col-sm-2 col-form-label">Tanggal Pembayaran</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="date" id="tgl_pembayaran" name="tgl_pembayaran" 
                                        value="<?= set_value('tgl_pembayaran', htmlspecialchars($detail['tgl_pembayaran'])); ?>">
                                    <?= form_error('tgl_pembayaran', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="lokasi" class="col-sm-2 col-form-label">Lokasi Barang</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" id="lokasi" name="lokasi" 
                                        value="<?= set_value('lokasi', htmlspecialchars($detail['lokasi'])); ?>">
                                    <?= form_error('lokasi', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Update LOP</button>
                                </div>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
