@extends('patra.layouts.master')
@section("title","Pengaturan ~ EMPATRA DIGITECH")
@section("title_breadcumb","Pengaturan")
@section('breadcumb')
    Pengaturan
@endsection
@section("content")
<div class="container">
    <div class="row">
        <div class="col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <form action="{{route('patra.pengaturan.update')}}" method="post" autocomplete="off" onsubmit="confirm('Apakah anda yakin ingin mengirim data ini?')" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Nama Website <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="website_name" placeholder="Nama Website" value="{{old('website_name',$result->website_name)}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Maps Website <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="website_maps" placeholder="Kata Kunci Website" value="{{old('website_maps',$result->website_maps)}}" required>
                                        <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Copy-an iframe html dari gmaps</i></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Motto Website <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea rows="3" class="form-control" name="website_motto">{{old('website_motto',$result->website_motto)}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">No.HP Website <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="website_phone" placeholder="No.HP Website" value="{{old('website_phone',$result->website_phone)}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Alamat Website <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="website_address" placeholder="Alamat Website" value="{{old('website_address',$result->website_address)}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Email Website <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="website_email" placeholder="Email Website" value="{{old('website_email',$result->website_email)}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Logo Website</label>
                                    <div class="col-md-10">
                                        @if(!empty($result->website_logo))
                                        <div class="mb-2">
                                            <img src="{{asset('storage/'.$result->website_logo)}}" style="width: 80px;height:80px;">
                                        </div>
                                        @endif
                                        <input type="file" class="form-control" name="website_logo">
                                        <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Kosongkan jika tidak diubah</i></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Angka Pencapaian (ditampilkan di halaman utama)</h5>
                        <p class="text-muted" style="margin-top:-10px;"><small>Isi dengan data yang benar-benar sesuai kondisi bisnis Anda. Kosongkan field yang belum ingin ditampilkan.</small></p>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">Projects Completed</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="stat_projects" placeholder="cth. 20+" value="{{old('stat_projects',$result->stat_projects)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">Happy Clients</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="stat_clients" placeholder="cth. 15+" value="{{old('stat_clients',$result->stat_clients)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">Industries Served</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="stat_industries" placeholder="cth. 5+" value="{{old('stat_industries',$result->stat_industries)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">Years Experience</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="stat_years_experience" placeholder="cth. 3+" value="{{old('stat_years_experience',$result->stat_years_experience)}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <a href="{{route('patra.pengaturan.index')}}" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
