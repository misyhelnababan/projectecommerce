   <!-- Sidebar -->
   <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">TOKO SHOPPING <sup>ANIME STORE</sup></div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a class="nav-link" href="{{route('dashboard.index')}}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Home</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">
<!-- Nav Item - Charts -->
<li class="nav-item">
    <a class="nav-link" href="{{route('dashboard.products.index')}}">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Produk</span></a>
</li>

<!-- Heading -->
<div class="sidebar-heading">
  Kontak Kami
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class=""></i>
        <span>Kontak Kami</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Kontak kami disini</h6>
            <a class="collapse-item" href="{{ route('kontakkami') }}">Kontak kami</a>
        </div>
    </div>
</li>

<!-- Nav Item - Utilities Collapse Menu -->
<!-- Nav Item - Charts -->
<li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">Kategori</a>
    </li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Menu Lain : 
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link" href="{{ route('transaksi.index') }}">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Transaksi</span>
    </a>
</li>




<!-- Nav Item - Tables -->
<li class="nav-item">
    <a class="nav-link" href="{{ route('pelanggan.index') }}">
        <i class="fas fa-fw fa-users"></i>
        <span>Pelanggan</span>
    </a>
</li>


<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->