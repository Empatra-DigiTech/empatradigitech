@extends('home.layouts.master')

@section("title", "Cek Data Kendaraan | BALAI UJI KIR MALANG KOTA")

@section("css")
<link href="assets/css/inovasi/style.css" rel="stylesheet">
@endsection

@section("content")
<div class="inov-bg"></div>

<div class="container mt-4" style="margin-top: 80px">
    <h1 class="text-center mb-4">Cek Data Kendaraan</h1>
    <div class="gallery">
        <div class="container mt-5">
            {{-- Form Search --}}
            <form method="GET" action="{{ route('home.inovasi.index') }}" class="d-flex gap-2 mb-4 justify-content-center">
                <input type="text" name="search" class="form-control w-100 search-input" placeholder="Cari No. Uji..." value="{{ request('search') }}">
                <button class="btn btn-primary fw-bold" type="submit">CARI</button>
            </form>

            @if(request('search'))
                <h2 class="mb-4 text-center">Data Kendaraan</h2>
                <div class="tabel-wrapper">
                <table class="table table-bordered table-striped mt-3">
                    <thead>
                        <tr>
                            <th>No. Uji</th>
                            <th>Nama Pemilik</th>
                            <th>Alamat</th>
                            <th>NoPol.</th>
                            <th>No. Rangka</th>
                            <th>Tanggal Terakhir Uji</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rfid as $row)
                            <tr>
                                <td>{{ $row->pengujian->first()->nouji }}</td>
                                <td>{{ $row->pengujian->first()->nama }}</td> 
                                <td>{{ $row->pengujian->first()->alamat }}</td> 
                                <td>{{ $row->pengujian->first()->noregistrasikendaraan }}</td> 
                                <td>{{ $row->pengujian->first()->norangka }}</td> 
                                <td>{{ $row->formatted_tgluji }}</td> 
                                <td>
                                    @if($row->vcode)
                                        <a href="https://ujiberkala-dstj.kemenhub.go.id/qr/v1/rfid/{{ $row->vcode }}" target="_blank" class="btn btn-sm btn-info">
                                            Lihat QR
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data kendaraan ditemukan untuk: <strong>{{ request('search') }}</strong></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                </div>

                <h2 class="mb-4 text-center">Ukuran Kendaraan</h2>
                <div class="tabel-wrapper">
                    <table class="table table-bordered table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Panjang Kendaraan</th>
                                <th>Lebar Kendaraan</th>
                                <th>Tinggi Kendaraan</th>
                                <th>Panjang Bak / Tangki</th>
                                <th>Lebar Bak / Tangki</th>
                                <th>Tinggi Bak / Tangki</th>
                                <th>Julur Depan</th>
                                <th>Julur Belakang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rfid as $row)
                                <tr>
                                    <td>{{ $row->panjangkendaraan ?? '-' }}</td>
                                    <td>{{ $row->lebarkendaraan ?? '-' }}</td>
                                    <td>{{ $row->tinggikendaraan ?? '-' }}</td>
                                    <td>{{ $row->panjangbakatautangki ?? '-' }}</td>
                                    <td>{{ $row->lebarbakatautangki ?? '-' }}</td>
                                    <td>{{ $row->tinggibakatautangki ?? '-' }}</td>
                                    <td>{{ $row->julurdepan ?? '-' }}</td>
                                    <td>{{ $row->julurbelakang ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data kendaraan ditemukan untuk: <strong>{{ request('search') }}</strong></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h2 class="mb-4 text-center">Foto Kendaraan</h2>
                <div class="tabel-wrapper">
                    <table class="table table-bordered table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Gambar Depan</th>
                                <th>Gambar Belakang</th>
                                <th>Gambar Kanan</th>
                                <th>Gambar Kiri</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rfid as $row)
                                <tr>
                                    <td><img src="{{ route('image.show', ['id' => $row->idx, 'jenis' => 'depan']) }}" width="100"></td>
                                    <td><img src="{{ route('image.show', ['id' => $row->idx, 'jenis' => 'belakang']) }}" width="100"></td>
                                    <td><img src="{{ route('image.show', ['id' => $row->idx, 'jenis' => 'kanan']) }}" width="100"></td>
                                    <td><img src="{{ route('image.show', ['id' => $row->idx, 'jenis' => 'kiri']) }}" width="100"></td>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data kendaraan ditemukan untuk: <strong>{{ request('search') }}</strong></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
