@extends('patra.layouts.master')
@section("title","Blog ~ EMPATRA DIGITECH")
@section("title_breadcumb","Blog")
@section('breadcumb', 'Blog')
@section('breadcumb_child', 'Show')
@section('content')
    <div class="container">
        <div class="row mx-1">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Judul
                            </div>
                            <div class="col-md-5">
                                : {{ $result->title }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Slug (URL)
                            </div>
                            <div class="col-md-5">
                                : /blog/{{ $result->slug }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Kategori
                            </div>
                            <div class="col-md-5">
                                : {{ $result->kategori }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Status
                            </div>
                            <div class="col-md-5">
                                : @if($result->is_published)
                                    <span class="badge badge-success">Published</span>
                                  @else
                                    <span class="badge badge-secondary">Draft</span>
                                  @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Ringkasan
                            </div>
                            <div class="col-md-8 text-justify">
                                : {{ $result->excerpt }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                Isi Artikel
                            </div>
                            <div class="col-md-8">

                                <style>
                                    img {
                                        max-width: 60%;
                                        height: auto;
                                    }
                                </style>
                                : {!! $result->renderTrix('content') !!}
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
                            <a href="{{ route('patra.blog.index') }}" class="btn btn-warning btn-sm"><i
                                    class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="{{ route('patra.blog.edit', $result->id) }}" class="btn btn-primary btn-sm"><i
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
    @endsection

    @section('script')
        <script>
            $(function() {

                $(document).on("click", ".btn-delete", function() {
                    if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                        $("#frmDelete").attr("action", "{{ route('patra.blog.destroy', '_id_') }}"
                            .replace("_id_", '{{ $result->id }}'));
                        $("#frmDelete").submit();
                    }
                })
            })
        </script>
    </div>
@endsection
