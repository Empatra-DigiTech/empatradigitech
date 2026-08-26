@extends('patra.layouts.master')
@section("title","Client / Partner ~ EMPATRA DIGITECH")
@section("title_breadcumb","Client / Partner")
@section("breadcumb","Client / Partner")
@section("content")
<div class="row">
    <div class="col-xl-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <a href="{{route('patra.client-logo.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</a>
                        <a href="{{route('patra.client-logo.index')}}" class="btn btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i>
                    Hanya tambahkan logo client/perusahaan yang benar-benar pernah menggunakan jasa Anda. 5-10 logo sudah cukup untuk membangun trust.
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>Urutan</th>
                                    <th>Logo</th>
                                    <th>Nama Client</th>
                                    <th>Website</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr>
                                        <td>{{ $row->urutan }}</td>
                                        <td>
                                            <img src="{{ asset('storage/'.$row->logo) }}" style="width:80px;height:50px;object-fit:contain;">
                                        </td>
                                        <td>{{ $row->nama_client }}</td>
                                        <td>
                                            @if($row->website_url)
                                                <a href="{{ $row->website_url }}" target="_blank" rel="noopener">{{ $row->website_url }}</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex mb-1">
                                                <a href="{{route('patra.client-logo.edit',$row->id)}}" class="btn btn-primary btn-sm mr-1"><i class="fa fa-edit"></i> Edit</a>
                                                <a href="#" class="btn btn-danger btn-sm mr-1 btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Data tidak ditemukan</td>
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
            if(confirm("Apakah anda yakin ingin menghapus data ini ?")){
                $("#frmDelete").attr("action", "{{ route('patra.client-logo.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection
