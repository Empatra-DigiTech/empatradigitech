@extends('patra.layouts.master')
@section("title","Portofolio ~ EMPATRA DIGITECH")
@section("title_breadcumb","Portofolio")
@section('breadcumb', 'Portofolio')
@section('breadcumb_child', 'Show')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Judul
                            </div>
                            <div class="col-md-8">
                                : {{ $result->title }}
                            </div>
                        </div>

                        @if($result->klien)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Klien
                            </div>
                            <div class="col-md-8">
                                : {{ $result->klien }}
                            </div>
                        </div>
                        @endif

                        @if($result->industry)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Industri
                            </div>
                            <div class="col-md-8">
                                : {{ $result->industry }}
                            </div>
                        </div>
                        @endif

                        @if($result->layanan)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Layanan
                            </div>
                            <div class="col-md-8">
                                : {{ $result->layanan }}
                            </div>
                        </div>
                        @endif

                        @if($result->brand)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Brand
                            </div>
                            <div class="col-md-8">
                                : {{ $result->brand }}
                            </div>
                        </div>
                        @endif

                        <div class="row mb-2">
                            <div class="col-md-2">
                                Deskripsi
                            </div>
                            <div class="col-md-8 text-justify">
                                <style>
                                    img {
                                        max-width: 100%;
                                        height: auto;
                                    }
                                </style>
                                {!! $result->trixRichText->first()->content !!}
                            </div>
                        </div>

                        @if($result->tantangan)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Tantangan
                            </div>
                            <div class="col-md-8 text-justify">
                                : {{ $result->tantangan }}
                            </div>
                        </div>
                        @endif

                        @if($result->solusi)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Solusi
                            </div>
                            <div class="col-md-8 text-justify">
                                : {{ $result->solusi }}
                            </div>
                        </div>
                        @endif

                        @if($result->fitur)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Fitur
                            </div>
                            <div class="col-md-8 text-justify">
                                : {{ $result->fitur }}
                            </div>
                        </div>
                        @endif

                        <div class="row mb-2">
                            <div class="col-md-2">
                                Tanggal
                            </div>
                            <div class="col-md-8">
                                : {{ Carbon\Carbon::parse($result->date)->translatedFormat('l,d F Y') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">
                                Image
                            </div>
                            <div class="col-md-8">
                                : <img src="{{ asset('storage/' . $result->image) }}" style="width: 200px;height:200px;">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">
                                Tanggal Diperbarui
                            </div>
                            <div class="col-md-8">
                                : {{ date('d-m-Y H:i:s', strtotime($result->updated_at)) }}
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="{{ route('patra.portofolio.index') }}" class="btn btn-warning btn-sm"><i
                                    class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="{{ route('patra.portofolio.edit', $result->id) }}" class="btn btn-primary btn-sm"><i
                                    class="fa fa-edit"></i> Edit</a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-trash"></i> Hapus</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <form id="frmDelete" method="POST">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $(document).on("click", ".btn-delete", function() {
                if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                    $("#frmDelete").attr("action", "{{ route('patra.portofolio.destroy', '_id_') }}"
                        .replace("_id_", '{{ $result->id }}'));
                    $("#frmDelete").submit();
                }
            })
        })
    </script>
@endsection
