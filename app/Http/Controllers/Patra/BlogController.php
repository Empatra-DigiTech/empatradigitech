<?php

namespace App\Http\Controllers\Patra;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Requests\Blog\StoreRequest;
use App\Http\Requests\Blog\UpdateRequest;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Auth;

class BlogController extends Controller
{
    protected $blog;
    protected $route = 'patra.pages.blog.';

    public function __construct(){
        $this->route = "patra.blog.";
        $this->view = "patra.pages.blog.";
        $this->blog = new Blog();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $table = $this->blog;

        if(!empty($search)){
            $table = $table->where(function($query2) use($search){
                $query2->where("title","like","%".$search."%");
            });
        }
        $table = $table->orderBy("created_at","DESC");
        $table = $table->paginate(10)->withQueryString();

        $data = [
            'table' => $table,
        ];

        return view($this->view."index",$data);
    }

    public function create()
    {
        return view($this->view."create");
    }

    protected function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 1;

        while ($this->blog->where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function store(StoreRequest $request)
    {
        try {
            $title = $request->title;
            $kategori = $request->kategori;
            $excerpt = $request->excerpt;
            $image = $request->file("image");

            if($image){
                $upload = UploadHelper::upload_file($image,'images',['jpeg','jpg','png','gif']);

                if($upload["IsError"] == TRUE){
                    throw new \Error($upload["Message"]);
                }

                $image = $upload["Path"];
                $this->blog->create([
                    'title' => $title,
                    'slug' => $this->generateUniqueSlug($title),
                    'kategori' => $kategori,
                    'excerpt' => $excerpt,
                    'blog-trixFields' => $request->input('blog-trixFields'),
                    'image' => $image,
                    'is_published' => $request->has('is_published'),
                    'creator' => Auth::user()->name,
                ]);
            }
            alert()->html('Berhasil','Data berhasil ditambahkan','success');
            return redirect()->route($this->route."index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal',$e->getMessage());
            return redirect()->route($this->route."create")->withInput();
        }
    }

    public function show($id)
    {
        $result = $this->blog;
        $result = $result->where('id',$id);
        $result = $result->first();

        if(!$result){
            alert()->error('Gagal',"Data tidak ditemukan");
            return redirect()->route($this->route."index");
        }

        $data = [
            'result' => $result,
        ];

        return view($this->view."show",$data);
    }

    public function edit($id)
    {
        $result = $this->blog;
        $result = $result->where('id',$id);
        $result = $result->first();

        if(!$result){
            alert()->error('Gagal',"Data tidak ditemukan");
            return redirect()->route($this->route."index");
        }

        $data = [
            'result' => $result,
        ];

        return view($this->view."edit",$data);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $result = $this->blog;
            $result = $result->where('id',$id);
            $result = $result->first();

            if(!$result){
                throw new \Error("Data tidak ditemukan");
            }

            $title = $request->title;
            $kategori = $request->kategori;
            $excerpt = $request->excerpt;
            $image = $request->file("image");

            if($image){
                $upload = UploadHelper::upload_file($image,'blog',['jpeg','jpg','png','gif']);

                if($upload["IsError"] == TRUE){
                    throw new \Error($upload["Message"]);
                }

                $image = $upload["Path"];
            }
            else{
                $image = $result->image;
            }

            // Regenerate slug only when the title actually changes
            $slug = $result->slug;
            if ($result->title !== $title) {
                $slug = $this->generateUniqueSlug($title);
            }

            $result->update([
                'title' => $title,
                'slug' => $slug,
                'kategori' => $kategori,
                'excerpt' => $excerpt,
                'blog-trixFields' => $request->input('blog-trixFields'),
                'image' => $image,
                'is_published' => $request->has('is_published'),
            ]);

            alert()->html('Berhasil','Data berhasil diubah','success');
            return redirect()->route($this->route."index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal',$e->getMessage());
            return redirect()->route($this->route."edit",$id)->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->blog;
            $result = $result->where('id',$id);
            $result = $result->first();

            $result->delete();

            alert()->html('Berhasil','Data berhasil dihapus','success');
            return redirect()->route($this->route."index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal',$e->getMessage());
            return redirect()->route($this->route."index");
        }
    }
}
