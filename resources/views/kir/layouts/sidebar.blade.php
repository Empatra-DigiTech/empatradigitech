<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #09121D;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link d-flex align-items-center justify-content-center">
      <img src="{{URL::to('/')}}/assets/img/favicon.png" alt="AdminLTE Logo" class="brand-image">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="@if(!empty(Auth::user()->avatar)) {{asset('storage/'.Auth::user()->avatar)}} @else https://avatars.dicebear.com/api/initials/{{ Auth::user()->name  ?? null}}.svg?margin=10 @endif" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ route('kir.profile.index') }}" class="d-block">{{ Auth::user()->name ?? null}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{route('kir.kir.index')}}" class="nav-link @if(request()->routeIs('kir.kir.index')) active @endif">
              <i class='bx bx-tachometer col-3 bx-tada-hover' ></i>
              <p>Kir</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('home.home.index')}}" class="nav-link">
              <i class='bx bxl-chrome col-3 bx-tada-hover' ></i>
              <p>Buka Landingpage </p>
            </a>
          </li>

          <li class="nav-header">MENU ADMIN</li>

            @if(Auth::user()->hasRole([
                  \App\Enums\RoleEnum::SuperAdmin,
                  \App\Enums\RoleEnum::Admin,
            ]))
            <li class="nav-item">
              <a href="{{route('kir.menu.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.menu')) active @endif">
                <i class="bx bx-menu col-3 bx-tada-hover"></i>
                <p>
                  Menu
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.banner.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.banner')) active @endif">
                <col class="row">
                <i class="bx bx-images col-3 bx-tada-hover"></i>
                <p>
                  Banner
                </p>
                </col>
              </a>
            </li>
            @endif

            <li class="nav-item">
              <a href="{{route('kir.berita.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.berita')) active @endif">
                <col class="row">
                <i class="bx bx-news col-3 bx-tada-hover"></i>
                <p>
                  Berita
                </p>
                </col>
              </a>
            </li>

            @if(Auth::user()->hasRole([
                \App\Enums\RoleEnum::SuperAdmin,
                \App\Enums\RoleEnum::Admin,
            ]))

            <li class="nav-item">
              <a href="{{route('kir.galeri.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.galeri')) active @endif">
                <col class="row">
                <i class="bx bx-camera col-3 bx-tada-hover"></i>
                <p>
                  Galeri
                </p>
                </col>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.layanan.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.layanan')) active @endif">
                <i class="bx bx-wrench col-3 bx-tada-hover"></i>
                <p>
                  Layanan
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.tautan.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.tautan')) active @endif">
                <i class="bx bx-link col-3 bx-tada-hover"></i>
                <p>
                  Tautan
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.kalender.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.kalender')) active @endif">
                <i class="bx bx-calendar col-3 bx-tada-hover"></i>
                <p>
                  Kalender
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.kontak.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.kontak')) active @endif">
                <i class="bx bxs-contact col-3 bx-tada-hover"></i>
                <p>
                  Pesan
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.informasi.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.informasi')) active @endif">
                <i class="bx bx-download col-3 bx-tada-hover"></i>
                <p>
                  Informasi
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.inovasi.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.inovasi')) active @endif">
                <i class="bx bxs-been-here col-3 bx-tada-hover"></i>
                  Inovasi
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.so.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.so')) active @endif">
                <i class="bx bxs-vector col-3 bx-tada-hover"></i>
                <p>
                  Struktur Organisasi
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.vm.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.vm')) active @endif">
                <i class='bx bx-poll col-3 bx-tada-hover'></i>
                <p>
                  Visi Misi
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.pejabat.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.pejabat')) active @endif">
                <i class='bx bx-group col-3 bx-tada-hover'></i>
                <p>
                  Pejabat
                </p>
              </a>
            </li>
            @endif

            @if(Auth::user()->hasRole([
                \App\Enums\RoleEnum::SuperAdmin,
            ]))
            <li class="nav-item">
              <a href="{{route('kir.users.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.user')) active @endif">
                <i class='bx bx-user col-3 bx-tada-hover'></i>
                <p>
                  User
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('kir.pengaturan.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.pengaturan')) active @endif">
                <i class="bx bx-cog col-3 bx-tada-hover"></i>
                <p>
                  Pengaturan
                </p>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a href="{{route('kir.user-activity.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'kir.log')) active @endif">
                <i class="bx bx-history col-3 bx-tada-hover"></i>
                <p>
                  Log
                </p>
              </a>
            </li> --}}
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
