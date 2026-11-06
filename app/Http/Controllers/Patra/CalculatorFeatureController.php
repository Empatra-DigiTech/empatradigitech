<?php

namespace App\Http\Controllers\Patra;
use App\Http\Controllers\Controller;
use App\Models\CalculatorFeature;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\ValidationException;

class CalculatorFeatureController extends Controller
{
    protected $feature;
    protected $route = 'patra.calculator-feature.';

    public function __construct(){
        $this->route = "patra.calculator-feature.";
        $this->view = "patra.pages.calculator-feature.";
        $this->feature = new CalculatorFeature();
        Paginator::useBootstrap();
    }

    private function rules(): array
    {
        return [
            'nama_fitur' => 'required|string|max:255',
            'harga_tambahan' => 'required|numeric|min:0|max:9999999999999',
            'deskripsi' => 'nullable|string|max:5000',
            'urutan' => 'nullable|integer|min:0|max:1000000',
        ];
    }

    /** Tampilkan pesan validasi apa adanya (bukan "kesalahan server") lalu kembali ke form. */
    private function validationFailed(ValidationException $e, string $url)
    {
        alert()->html('Gagal', implode('<br>', array_map('e', $e->validator->errors()->all())), 'error');

        return redirect($url)->withInput();
    }

    public function index(Request $request)
    {
        $table = $this->feature->orderBy("urutan","ASC")->orderBy("created_at","DESC")->paginate(15);
        return view($this->view."index",['table' => $table]);
    }

    public function create()
    {
        return view($this->view."create");
    }

    public function store(Request $request)
    {
        try {
            $request->validate($this->rules());

            $this->feature->create([
                'nama_fitur' => $request->nama_fitur,
                'harga_tambahan' => $request->harga_tambahan,
                'deskripsi' => $request->deskripsi,
                'urutan' => $request->urutan ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            alert()->html('Berhasil','Data berhasil ditambahkan','success');
            return redirect()->route($this->route."index");

        } catch (ValidationException $e) {
            return $this->validationFailed($e, route($this->route."create"));
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal',\App\Helpers\ErrorHelper::userMessage($e));
            return redirect()->route($this->route."create")->withInput();
        }
    }

    public function edit($id)
    {
        $result = $this->feature->where('id',$id)->first();
        if(!$result){
            alert()->error('Gagal',"Data tidak ditemukan");
            return redirect()->route($this->route."index");
        }
        return view($this->view."edit",['result' => $result]);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate($this->rules());

            $result = $this->feature->where('id',$id)->first();
            if(!$result){
                throw new \App\Exceptions\UserFacingException("Data tidak ditemukan");
            }

            $result->update([
                'nama_fitur' => $request->nama_fitur,
                'harga_tambahan' => $request->harga_tambahan,
                'deskripsi' => $request->deskripsi,
                'urutan' => $request->urutan ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            alert()->html('Berhasil','Data berhasil diubah','success');
            return redirect()->route($this->route."index");

        } catch (ValidationException $e) {
            return $this->validationFailed($e, route($this->route."edit", $id));
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal',\App\Helpers\ErrorHelper::userMessage($e));
            return redirect()->route($this->route."edit",$id)->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->feature->where('id',$id)->first();
            if(!$result){
                throw new \App\Exceptions\UserFacingException("Data tidak ditemukan");
            }
            $result->delete();
            alert()->html('Berhasil','Data berhasil dihapus','success');
            return redirect()->route($this->route."index");
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal',\App\Helpers\ErrorHelper::userMessage($e));
            return redirect()->route($this->route."index");
        }
    }
}
