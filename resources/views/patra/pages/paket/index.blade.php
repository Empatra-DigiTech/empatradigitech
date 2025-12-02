@extends('patra.layouts.master')
@section('title', 'Paket')

@section('css')
<style>
    .badge-website {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .badge-app {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .recommended-star {
        color: #ffc107;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Paket Layanan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('patra.patra.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Paket</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Paket</h3>
                            <div class="card-tools">
                                <a href="{{ route('patra.paket.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Paket
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('patra.paket.index') }}" method="GET" class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="search" class="form-control"
                                               placeholder="Cari nama paket..."
                                               value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="tipe" class="form-control">
                                            <option value="">-- Semua Tipe --</option>
                                            <option value="website" {{ request('tipe') == 'website' ? 'selected' : '' }}>
                                                Website
                                            </option>
                                            <option value="app" {{ request('tipe') == 'app' ? 'selected' : '' }}>
                                                Aplikasi
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Tipe</th>
                                            <th>Nama Paket</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Urutan</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($table as $index => $row)
                                        <tr>
                                            <td>{{ $table->firstItem() + $index }}</td>
                                            <td>
                                                <span class="badge {{ $row->tipe == 'website' ? 'badge-website' : 'badge-app' }}">
                                                    <i class="bx {{ $row->tipe == 'website' ? 'bx-laptop' : 'bx-mobile-alt' }}"></i>
                                                    {{ ucfirst($row->tipe) }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $row->nama_paket }}</strong>
                                                @if($row->is_recommended)
                                                    <i class="fas fa-star recommended-star"></i>
                                                @endif
                                                @if($row->tagline)
                                                    <br><small class="text-muted">{{ $row->tagline }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $row->formatted_harga }}</strong>
                                                <br><small class="text-muted">{{ $row->periode }}</small>
                                            </td>
                                            <td>
                                                @if($row->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>{{ $row->urutan }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('patra.paket.show', $row->id) }}"
                                                       class="btn btn-sm btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('patra.paket.edit', $row->id) }}"
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('patra.paket.destroy', $row->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')"
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data paket</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer clearfix">
                            {{ $table->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
