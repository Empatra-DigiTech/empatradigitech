<?php

namespace App\Http\Controllers\Patra;
use App\Http\Controllers\Controller;
use App\Models\VM;

use Illuminate\Support\Facades\Log;
use App\Http\Requests\VM\UpdateRequest;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;

class VMController extends Controller
{
    protected $vm;
    protected $route = 'patra.pages.vm.';

    public function __construct(){
        $this->route = "patra.vm.";
        $this->view = "patra.pages.vm.";
        $this->vm = new VM();
    }

    public function index()
    {
        $result = $this->vm->first();

        $data = [
            'result' => $result,
        ];

        return view($this->view.'index', $data);
    }

    public function update(UpdateRequest $request)
    {
        try {
            $update = $this->vm->first();

            // If no record exists, create one
            if (!$update) {
                $update = $this->vm->create([
                    'visi' => '',
                    'misi' => '',
                ]);
            }

            $visi = $request->visi;
            $misi = $request->misi;
            $image = $request->file("image");

            if ($image) {
                $upload = UploadHelper::upload_file($image, 'visimisi', ['jpeg', 'jpg', 'png', 'gif']);

                if ($upload["IsError"] == TRUE) {
                    throw new \Error($upload["Message"]);
                }

                $imagePath = $upload["Path"];
            } else {
                $imagePath = $update->image;
            }

            $update->update([
                'image' => $imagePath,
                'visi' => $visi,
                'misi' => $misi,
            ]);

            alert()->html('Berhasil', 'Visi & Misi berhasil diperbarui', 'success');

            return redirect()->route($this->route.'index');

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());

            alert()->error('Gagal', $e->getMessage());
            return redirect()->route($this->route."index")->withInput();
        }
    }
}
