<?php

namespace App\Http\Controllers\Patra;

use App\Exceptions\UserFacingException;
use App\Helpers\ErrorHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

/**
 * Basis CRUD admin untuk modul SEDERHANA: satu model, tanpa upload berkas, tanpa aturan bisnis khusus.
 *
 * Yang sama untuk semua modul ada di sini (daftar + pencarian + paginasi, tampil, ubah, hapus, alert, log,
 * pengalihan saat gagal). Yang berbeda per modul tetap ditulis di controller turunan:
 *  - model, prefix route, prefix view;
 *  - kolom yang disimpan (`attributes()`);
 *  - FormRequest validasi, sehingga `store()` dan `update()` tetap dideklarasikan di controller turunan
 *    (Laravel memvalidasi lewat tipe parameter) lalu memanggil `storeRecord()` / `updateRecord()`.
 *
 * Jangan memakai kelas ini untuk modul yang punya upload, relasi, transaksi, atau aturan bisnis sendiri
 * (Blog, Portofolio, Finance, User, ...): menyamarkan aturan itu di balik abstraksi lebih berbahaya daripada duplikasinya.
 *
 * Invalidasi cache publik tidak ditangani di sini: sudah otomatis lewat event model (lihat App\Support\PublicCache).
 */
abstract class SimpleCrudController extends Controller
{
    /** @var class-string<Model> */
    protected string $modelClass;

    /** Prefix nama route, mis. "patra.tautan." */
    protected string $route;

    /** Prefix nama view, mis. "patra.pages.tautan." */
    protected string $viewPrefix;

    /** Kolom yang dicari lewat ?search= */
    protected array $searchColumns = ['title'];

    protected int $perPage = 10;

    /** Kolom hasil input yang disimpan saat tambah/ubah. */
    abstract protected function attributes(Request $request): array;

    public function __construct()
    {
        // Controller lain memakai $this->view; nama itu dipertahankan agar view/skrip lama tidak terpengaruh.
        $this->view = $this->viewPrefix;
        Paginator::useBootstrap();
    }

    protected function newQuery()
    {
        return (new $this->modelClass)->newQuery();
    }

    protected function find($id): ?Model
    {
        return $this->newQuery()->where('id', $id)->first();
    }

    public function index(Request $request)
    {
        $query = $this->newQuery();
        $search = $request->search;

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchColumns as $i => $column) {
                    $q->{$i === 0 ? 'where' : 'orWhere'}($column, 'like', '%' . $search . '%');
                }
            });
        }

        return view($this->view . 'index', [
            'table' => $query->orderBy('created_at', 'DESC')->paginate($this->perPage),
        ]);
    }

    public function create()
    {
        return view($this->view . 'create');
    }

    public function show($id)
    {
        return $this->showView('show', $id);
    }

    public function edit($id)
    {
        return $this->showView('edit', $id);
    }

    private function showView(string $page, $id)
    {
        $result = $this->find($id);

        if (! $result) {
            alert()->error('Gagal', 'Data tidak ditemukan');
            return redirect()->route($this->route . 'index');
        }

        return view($this->view . $page, ['result' => $result]);
    }

    protected function storeRecord(Request $request)
    {
        try {
            $this->newQuery()->create($this->attributes($request));

            alert()->html('Berhasil', 'Data berhasil ditambahkan', 'success');
            return redirect()->route($this->route . 'index');
        } catch (\Throwable $e) {
            return $this->fail($e, $this->route . 'create');
        }
    }

    protected function updateRecord(Request $request, $id)
    {
        try {
            $result = $this->find($id);

            if (! $result) {
                throw new UserFacingException('Data tidak ditemukan');
            }

            $result->update($this->attributes($request));

            alert()->html('Berhasil', 'Data berhasil diubah', 'success');
            return redirect()->route($this->route . 'index');
        } catch (\Throwable $e) {
            return $this->fail($e, $this->route . 'edit', [$id]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->find($id);

            if (! $result) {
                throw new UserFacingException('Data tidak ditemukan');
            }

            $result->delete();

            alert()->html('Berhasil', 'Data berhasil dihapus', 'success');
            return redirect()->route($this->route . 'index');
        } catch (\Throwable $e) {
            return $this->fail($e, $this->route . 'index', [], false);
        }
    }

    private function fail(\Throwable $e, string $route, array $params = [], bool $withInput = true)
    {
        Log::error($e->getMessage(), ['exception' => $e]);
        alert()->error('Gagal', ErrorHelper::userMessage($e));

        $redirect = redirect()->route($route, $params);

        return $withInput ? $redirect->withInput() : $redirect;
    }
}
