@extends('patra.layouts.master')
@section("title","Invoice ~ EMPATRA DIGITECH")
@section("title_breadcumb","Invoice")
@section('breadcumb', 'Invoice')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <a href="{{ route('patra.invoice.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Buat Invoice Baru
                        </a>
                        <a href="#" class="btn btn-success btn-filter">
                            <i class="fa fa-filter"></i> Filter
                        </a>
                        <a href="{{route('patra.invoice.index')}}" class="btn btn-warning">
                            <i class="fa fa-refresh"></i> Refresh
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 15%">No. Invoice</th>
                                        <th style="width: 20%">Klien</th>
                                        <th style="width: 12%">Tanggal</th>
                                        <th style="width: 12%">Jatuh Tempo</th>
                                        <th style="width: 13%">Total</th>
                                        <th style="width: 13%">Sisa</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td><strong>{{$row->invoice_number}}</strong></td>
                                        <td>{{$row->bill_to_name}}</td>
                                        <td>{{ Carbon\Carbon::parse($row->invoice_date)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($row->due_date)
                                                {{ Carbon\Carbon::parse($row->due_date)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $row->formatCurrency($row->total) }}</td>
                                        <td>
                                            @if($row->balance_due > 0)
                                                <span class="badge badge-warning">{{ $row->formatCurrency($row->balance_due) }}</span>
                                            @else
                                                <span class="badge badge-success">Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{route('patra.invoice.show',$row->id)}}"
                                                   class="btn btn-sm btn-info" title="Lihat">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{route('patra.invoice.edit',$row->id)}}"
                                                   class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{route('patra.invoice.download',$row->id)}}"
                                                   class="btn btn-sm btn-success" title="Download PDF">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-danger btn-delete"
                                                   data-id="{{$row->id}}" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada invoice</td>
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

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Invoice</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="get" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Cari</label>
                        <input type="text" class="form-control"
                               placeholder="No. Invoice atau Nama Klien"
                               value="{{request()->get('search')}}" name="search">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
</form>
@endsection

@section("script")
<script>
    $(function(){
        $(document).on("click",".btn-filter",function(e){
            e.preventDefault();
            $("#modalFilter").modal("show");
        });

        $(document).on("click",".btn-delete",function(){
            let id = $(this).data("id");
            if(confirm("Apakah anda yakin ingin menghapus invoice ini?")){
                $("#frmDelete").attr("action", "{{ route('patra.invoice.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection
