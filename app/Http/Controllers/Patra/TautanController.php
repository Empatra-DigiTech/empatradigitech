<?php

namespace App\Http\Controllers\Patra;

use App\Http\Requests\Tautan\StoreRequest;
use App\Http\Requests\Tautan\UpdateRequest;
use App\Models\Tautan;
use Illuminate\Http\Request;

class TautanController extends SimpleCrudController
{
    protected string $modelClass = Tautan::class;
    protected string $route = 'patra.tautan.';
    protected string $viewPrefix = 'patra.pages.tautan.';

    protected function attributes(Request $request): array
    {
        return [
            'title' => $request->title,
            'url' => $request->url,
        ];
    }

    public function store(StoreRequest $request)
    {
        return $this->storeRecord($request);
    }

    public function update(UpdateRequest $request, $id)
    {
        return $this->updateRecord($request, $id);
    }
}
