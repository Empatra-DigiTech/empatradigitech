@extends('patra.layouts.master')
@section("title","FAQ ~ EMPATRA DIGITECH")
@section("title_breadcumb","FAQ")
@section('breadcumb', 'FAQ')
@section('breadcumb_child', 'Show')
@section('content')
    <div class="container">
        <div class="row mx-1">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-2">Pertanyaan</div>
                            <div class="col-md-8">: {{ $result->question }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Jawaban</div>
                            <div class="col-md-8 text-justify">: {{ $result->answer }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Kategori</div>
                            <div class="col-md-8">: {{ $result->kategori ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Status</div>
                            <div class="col-md-8">
                                : @if($result->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                  @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                  @endif
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="{{ route('patra.faq.index') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="{{ route('patra.faq.edit', $result->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
