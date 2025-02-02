<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">

          <!-- Sales Card -->
          <div class="col-lg-4 col-md-6">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>
                  <li><a class="dropdown-item" href="?filter=today">Hari Ini</a></li>
                  <li><a class="dropdown-item" href="?filter=this_month">Bulan Ini</a></li>
                  <li><a class="dropdown-item" href="?filter=this_year">Tahun Ini</a></li>
                </ul>
              </div>

              <div class="card-body">
                <h5 class="card-title">Produk Terjual <span>| <?= isset($filter_label) ? $filter_label : 'Hari Ini'; ?></span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-bar-chart"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= isset($sales_data) ? number_format($sales_data, 0, ',', '.') : 0; ?></h6>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Sales Card -->

          <!-- Revenue Card -->
          <div class="col-lg-4 col-md-6">
            <div class="card info-card revenue-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>
                  <li><a class="dropdown-item" href="?filter=today">Hari Ini</a></li>
                  <li><a class="dropdown-item" href="?filter=this_month">Bulan Ini</a></li>
                  <li><a class="dropdown-item" href="?filter=this_year">Tahun Ini</a></li>
                </ul>
              </div>

              <div class="card-body">
                <h5 class="card-title">Pendapatan <span>| <?= isset($filter_label) ? $filter_label : 'Hari Ini'; ?></span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-cash"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= isset($revenue_data) ? number_format($revenue_data, 0, ',', '.') : 0; ?></h6>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Revenue Card -->

          <!-- Customers Card -->
          <div class="col-lg-4 col-md-6">
            <div class="card info-card customers-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>
                  <li><a class="dropdown-item" href="?filter=today">Hari Ini</a></li>
                  <li><a class="dropdown-item" href="?filter=this_month">Bulan Ini</a></li>
                  <li><a class="dropdown-item" href="?filter=this_year">Tahun Ini</a></li>
                </ul>
              </div>

              <div class="card-body">
                <h5 class="card-title">Pemesanan <span>| <?= isset($filter_label) ? $filter_label : 'Hari Ini'; ?></span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-basket"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= isset($customers_data) ? number_format($customers_data, 0, ',', '.') : 0; ?></h6>
                  </div>
                </div>

              </div>
            </div>
          </div><!-- End Customers Card -->

          <!-- Top Selling -->
          <div class="col-12">
            <div class="card top-selling overflow-auto">
              <div class="card-body pb-0">
                <h5 class="card-title">Penjualan Tertinggi <span>| <?= isset($filter_label) ? $filter_label : 'Hari Ini'; ?></span></h5>
                <table class="table table-borderless">
                  <thead>
                    <tr>
                      <th scope="col">Produk</th>
                      <th scope="col">Harga</th>
                      <th scope="col">Terjual</th>
                      <th scope="col">Pendapatan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($top_selling) && is_array($top_selling)): ?>
                      <?php foreach ($top_selling as $item): ?>
                        <tr>
                          <td><?= isset($item['nama_barang']) ? $item['nama_barang'] : ''; ?></td>
                          <td>Rp <?= isset($item['harga']) ? number_format($item['harga'], 0, ',', '.') : '0'; ?></td>
                          <td><?= isset($item['total_pesan']) ? number_format($item['total_pesan'], 0, ',', '.') : '0'; ?></td>
                          <td>Rp <?= isset($item['total_revenue']) ? number_format($item['total_revenue'], 0, ',', '.') : '0'; ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4">Tidak ada data.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div><!-- End Top Selling -->
        </div>
      </div><!-- End Left side columns -->

    </div>
  </section>

</main><!-- End #main -->
