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
          <a href="{{ route('patra.profile.index') }}" class="d-block">{{ Auth::user()->name ?? null}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{route('patra.patra.index')}}" class="nav-link @if(request()->routeIs('patra.patra.index')) active @endif">
              <i class='bx bx-tachometer col-3 bx-tada-hover' ></i>
              <p>patra</p>
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
              <a href="{{route('patra.menu.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.menu')) active @endif">
                <i class="bx bx-menu col-3 bx-tada-hover"></i>
                <p>
                  Menu
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('patra.banner.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.banner')) active @endif">
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
              <a href="{{route('patra.portofolio.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.portofolio')) active @endif">
                <col class="row">
                <i class="bx bx-news col-3 bx-tada-hover"></i>
                <p>
                  Portofolio
                </p>
                </col>
              </a>
            </li>

            @if(Auth::user()->hasRole([
                \App\Enums\RoleEnum::SuperAdmin,
                \App\Enums\RoleEnum::Admin,
            ]))

            <li class="nav-item">
              <a href="{{route('patra.galeri.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.galeri')) active @endif">
                <col class="row">
                <i class="bx bx-camera col-3 bx-tada-hover"></i>
                <p>
                  Galeri
                </p>
                </col>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('patra.layanan.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.layanan')) active @endif">
                <i class="bx bx-wrench col-3 bx-tada-hover"></i>
                <p>
                  Layanan
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('patra.tautan.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.tautan')) active @endif">
                <i class="bx bx-link col-3 bx-tada-hover"></i>
                <p>
                  Tautan
                </p>
              </a>
            </li>

            <!-- TAMBAHKAN INI -->
            <li class="nav-item">
            <a href="{{route('patra.paket.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.paket')) active @endif">
                <i class="bx bx-package col-3 bx-tada-hover"></i>
                <p>
                Paket Digital
                </p>
            </a>
            </li>
            <!-- END TAMBAHAN -->

            {{-- <li class="nav-item">
              <a href="{{route('patra.kalender.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.kalender')) active @endif">
                <i class="bx bx-calendar col-3 bx-tada-hover"></i>
                <p>
                  Kalender
                </p>
              </a>
            </li> --}}

            <li class="nav-item">
              <a href="{{route('patra.kontak.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.kontak')) active @endif">
                <i class="bx bxs-contact col-3 bx-tada-hover"></i>
                <p>
                  Pesan
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('patra.informasi.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.informasi')) active @endif">
                <i class="bx bx-download col-3 bx-tada-hover"></i>
                <p>
                  Informasi
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('patra.inovasi.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.inovasi')) active @endif">
                <i class="bx bxs-been-here col-3 bx-tada-hover"></i>
                  Inovasi
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('patra.blog.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.blog')) active @endif">
                <i class="bx bx-news col-3 bx-tada-hover"></i>
                <p>
                  Blog / Insight
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('patra.faq.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.faq')) active @endif">
                <i class="bx bx-help-circle col-3 bx-tada-hover"></i>
                <p>
                  FAQ
                </p>
              </a>
            </li>
<li class="nav-item">
    <a href="{{route('patra.invoice.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.invoice')) active @endif">
        <i class='bx bx-file col-3 bx-tada-hover'></i>
        <p>Invoice</p>
    </a>
</li>
            <li class="nav-item">

            {{-- <li class="nav-item">
              <a href="{{route('patra.so.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.so')) active @endif">
                <i class="bx bxs-vector col-3 bx-tada-hover"></i>
                <p>
                  Struktur Organisasi
                </p>
              </a>
            </li> --}}

            <li class="nav-item">
              <a href="{{route('patra.vm.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.vm')) active @endif">
                <i class='bx bx-poll col-3 bx-tada-hover'></i>
                <p>
                  Visi Misi
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('patra.team.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.team')) active @endif">
                <i class='bx bx-group col-3 bx-tada-hover'></i>
                <p>
                  Team
                </p>
              </a>
            </li>
            @endif

            @if(Auth::user()->hasRole([
                \App\Enums\RoleEnum::SuperAdmin,
            ]))
            <li class="nav-item">
              <a href="{{route('patra.users.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.user')) active @endif">
                <i class='bx bx-user col-3 bx-tada-hover'></i>
                <p>
                  User
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{route('patra.pengaturan.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.pengaturan')) active @endif">
                <i class="bx bx-cog col-3 bx-tada-hover"></i>
                <p>
                  Pengaturan
                </p>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a href="{{route('patra.user-activity.index')}}" class="nav-link @if(Str::startsWith(request()->route()->getName(), 'patra.log')) active @endif">
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
