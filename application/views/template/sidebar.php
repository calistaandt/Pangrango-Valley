<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('admin') ?>">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-shop-window"></i><span>Toko</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?= base_url('admin/toko') ?>">
              <i class="bi bi-circle"></i><span>Data Toko</span>
            </a>
          </li>
        </ul>
      </li>
    <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#forms-barang" data-bs-toggle="collapse" href="#">
            <i class="bi bi-bag-plus"></i><span>Barang</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-barang" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="<?= base_url('admin/barang') ?>">
                <i class="bi bi-circle"></i><span>Data Barang</span>
              </a>
            </li>
          </ul>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#cart-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-cart"></i><span>PO</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="cart-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?= base_url('admin/dataPO') ?>">
              <i class="bi bi-circle"></i><span>Data PO</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('admin/datagudang') ?>">
              <i class="bi bi-circle"></i><span>Data Timbangan</span>
            </a>
          </li>
        </ul>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>LOP</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?= base_url('admin/dataLOP') ?>">
              <i class="bi bi-circle"></i><span>Data LOP</span>
            </a>
          </li>
        </ul>
    </li>
      
</ul>

</aside><!-- End Sidebar-->
