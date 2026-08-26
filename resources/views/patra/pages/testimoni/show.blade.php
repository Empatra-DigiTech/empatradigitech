@extends('patra.layouts.master')
@section("title","Testimoni ~ EMPATRA DIGITECH")
@section("title_breadcumb","Testimoni")
@section('breadcumb', 'Testimoni')
@section('breadcumb_child', 'Detail')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">

                    @if($result->foto)
                    <div class="row mb-3">
                        <div class="col-md-3">Foto</div>
                        <div class="col-md-8">
                            : <img src="{{ asset('storage/'.$result->foto) }}" style="width:100px;height:100px;object-fit:cover;border-radius:50%;">
                        </div>
                    </div>
                    @endif

                    <div class="row mb-2">
                        <div class="col-md-3">Nama Client</div>
                        <div class="col-md-8">: {{ $result->nama_client }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">Jabatan</div>
                        <div class="col-md-8">: {{ $result->jabatan ?? '-' }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">Perusahaan</div>
                        <div class="col-md-8">: {{ $result->perusahaan ?? '-' }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">Rating</div>
                        <div class="col-md-8">
                            :
                            @for($i=1;$i<=5;$i++)
                                <i class="fa fa-star {{ $i <= $result->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">Testimoni</div>
                        <div class="col-md-8">: {{ $result->testimoni }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">Status</div>
                        <div class="col-md-8">
                            :
                            @if($result->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('patra.testimoni.index') }}" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
