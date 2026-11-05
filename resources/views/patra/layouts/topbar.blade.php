<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Buka atau tutup menu samping" title="Buka atau tutup menu samping"><i class="fas fa-bars" aria-hidden="true"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button" aria-label="Cari" title="Cari">
          <i class="fas fa-search" aria-hidden="true"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit" aria-label="Cari" title="Cari">
                  <i class="fas fa-search" aria-hidden="true"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search" aria-label="Tutup pencarian" title="Tutup pencarian">
                  <i class="fas fa-times" aria-hidden="true"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button" aria-label="Tampilkan layar penuh" title="Tampilkan layar penuh">
          <i class="fas fa-expand-arrows-alt" aria-hidden="true"></i>
        </a>
      </li>
      <li class="nav-item">
        {{-- Logout lewat POST + CSRF supaya tidak bisa dipicu dari situs/gambar lain. --}}
        <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="nav-link btn btn-link" aria-label="Keluar" title="Keluar" style="border:0;background:none;">
            <i class="fa fa-sign-out-alt" aria-hidden="true"></i>
          </button>
        </form>
      </li>
    </ul>
</nav>
<!-- /.navbar -->