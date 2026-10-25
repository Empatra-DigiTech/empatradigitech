<?php

namespace App\Http\Controllers\Patra;

use App\Http\Requests\Kalender\StoreRequest;
use App\Http\Requests\Kalender\UpdateRequest;
use App\Models\Kalender;
use Illuminate\Http\Request;

class KalenderController extends SimpleCrudController
{
    protected string $modelClass = Kalender::class;
    protected string $route = 'patra.kalender.';
    protected string $viewPrefix = 'patra.pages.kalender.';

    protected function attributes(Request $request): array
    {
        return [
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
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
