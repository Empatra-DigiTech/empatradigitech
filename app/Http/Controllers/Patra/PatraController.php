<?php

namespace App\Http\Controllers\Patra;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatraController extends Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = "patra.pages.patra.";
    }

    public function index(Request $request)
    {
        $range = DashboardService::normalizeRange($request->query('range'));

        $data = (new DashboardService(Auth::user()))->build($range);

        return view($this->view . "index", $data);
    }
}
