@extends('patra.layouts.master')
@section("title","Visi Misi ~ EMPATRA DIGITECH")
@section("title_breadcumb","Visi Misi")
@section("breadcumb","Visi Misi")
@section("content")
<div class="container">
    <div class="row">
        <div class="col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <form action="{{ route('patra.vm.update') }}" method="post" autocomplete="off" onsubmit="return confirm('Apakah anda yakin ingin mengirim data ini?')" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")

                        <div class="row mb-3">
                            <div class="col-lg-12">

                                {{-- Banner Image Section --}}
                                <div class="form-group row mb-5">
                                    <label class="col-md-2 col-form-label">Banner Image</label>
                                    <div class="col-md-10">
                                        @if($result && $result->image)
                                            <img src="{{ asset('storage/'.$result->image) }}" style="width: 100%; max-width: 800px; height: auto; border-radius: 8px; margin-bottom: 15px;">
                                        @else
                                            <p class="text-muted">Belum ada gambar</p>
                                        @endif
                                        <input class="form-control mt-3" type="file" name="image" accept="image/*">
                                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                                    </div>
                                </div>

                                {{-- Visi Section --}}
                                <div class="form-group row mb-5">
                                    <label class="col-md-2 col-form-label">Visi <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="visi" rows="5" placeholder="Masukkan visi perusahaan..." required>{{ old('visi', $result->visi ?? '') }}</textarea>
                                        <small class="form-text text-muted">Tuliskan visi perusahaan Anda</small>
                                    </div>
                                </div>

                                {{-- Misi Section --}}
                                <div class="form-group row mb-5">
                                    <label class="col-md-2 col-form-label">Misi <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="misi" rows="10" placeholder="Masukkan misi perusahaan (pisahkan setiap poin dengan enter/baris baru)..." required>{{ old('misi', $result->misi ?? '') }}</textarea>
                                        <small class="form-text text-muted">
                                            <strong>Petunjuk:</strong> Tulis setiap poin misi di baris baru. Contoh:<br>
                                            Poin misi 1<br>
                                            Poin misi 2<br>
                                            Poin misi 3
                                        </small>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 12px;
    }

    .form-control:focus {
        border-color: #0f3c5d;
        box-shadow: 0 0 0 0.2rem rgba(15, 60, 93, 0.15);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-text.text-muted {
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>
@endsection
