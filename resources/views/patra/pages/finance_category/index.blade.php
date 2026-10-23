@extends('patra.layouts.master')
@section("title","Kategori Keuangan ~ EMPATRA DIGITECH")
@section("title_breadcumb","Kategori Keuangan")
@section("breadcumb","Buku Kas")
@section("breadcumb_child","Kategori")
@section("content")

<div class="row">
    <div class="col-xl-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-8">
                        <a href="{{route('patra.finance-category.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kategori</a>
                        <a href="{{route('patra.finance.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali ke Buku Kas</a>
                    </div>
                    <div class="col-lg-4">
                        <form method="GET" action="{{ route('patra.finance-category.index') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari kategori..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" aria-label="Terapkan filter pencarian" title="Terapkan filter pencarian"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th>Nama</th>
                            <th>Induk</th>
                            <th>Jenis</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @forelse ($table as $row)
                            <tr>
                                <td>{{ $row->parent_id ? '— ' : '' }}<strong>{{ $row->name }}</strong></td>
                                <td>{{ $row->parent->name ?? '-' }}</td>
                                <td>
                                    @if($row->type === 'masuk')
                                        <span class="badge badge-success">Masuk</span>
                                    @else
                                        <span class="badge badge-danger">Keluar</span>
                                    @endif
                                </td>
                                <td>{{ $row->urutan }}</td>
                                <td>
                                    @if($row->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex mb-1">
                                        <a href="{{route('patra.finance-category.edit',$row->id)}}" class="btn btn-primary btn-sm mr-1" aria-label="Ubah kategori" title="Ubah kategori"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="#" class="btn btn-danger btn-sm mr-1 btn-delete" data-id="{{$row->id}}" aria-label="Hapus kategori" title="Hapus kategori" role="button"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada kategori</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {!!$table->links()!!}
            </div>
        </div>
    </div>
</div>

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id"/>
</form>

@endsection

@section("script")
<script>
    $(function(){
        $(document).on("click",".btn-delete",function(){
            let id = $(this).data("id");
            if(confirm("Apakah anda yakin ingin menghapus kategori ini?")){
                $("#frmDelete").attr("action", "{{ route('patra.finance-category.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection
