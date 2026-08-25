@extends('patra.layouts.master')
@section("title","Kalkulator Fitur ~ EMPATRA DIGITECH")
@section("title_breadcumb","Kalkulator Fitur Tambahan")
@section("breadcumb","Kalkulator Fitur Tambahan")
@section("content")
<div class="row">
    <div class="col-xl-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <a href="{{route('patra.calculator-feature.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th>Urutan</th>
                            <th>Nama Fitur</th>
                            <th>Harga Tambahan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @forelse ($table as $row)
                            <tr>
                                <td>{{ $row->urutan }}</td>
                                <td>{{ $row->nama_fitur }}</td>
                                <td>Rp {{ number_format($row->harga_tambahan,0,',','.') }}</td>
                                <td>
                                    @if($row->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex mb-1">
                                        <a href="{{route('patra.calculator-feature.edit',$row->id)}}" class="btn btn-primary btn-sm mr-1"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm mr-1 btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">Data tidak ditemukan</td></tr>
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
            if(confirm("Apakah anda yakin ingin menghapus data ini ?")){
                $("#frmDelete").attr("action", "{{ route('patra.calculator-feature.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection
