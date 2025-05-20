<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function analytics()
    {
        $views = Analytic::select('entity_type', DB::raw('count(*) as count'))
            ->where('action_type', 'view')
            ->groupBy('entity_type')
            ->get();

        return view('admin.analytics', compact('views'));
    }

}