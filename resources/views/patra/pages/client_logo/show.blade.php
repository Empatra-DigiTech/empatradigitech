@extends('patra.layouts.master')
@section("title","Client / Partner ~ EMPATRA DIGITECH")
@section("title_breadcumb","Client / Partner")
@section('breadcumb', 'Client / Partner')
@section('breadcumb_child', 'Detail')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-3">Logo</div>
                        <div class="col-md-8">
                            : <img src="{{ asset('storage/'.$result->logo) }}" style="width:120px;height:70px;object-fit:contain;">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">Nama Client</div>
                        <div class="col-md-8">: {{ $result->nama_client }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">Website</div>
                        <div class="col-md-8">: {{ $result->website_url ?? '-' }}</div>
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

                    <a href="{{ route('patra.client-logo.index') }}" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
