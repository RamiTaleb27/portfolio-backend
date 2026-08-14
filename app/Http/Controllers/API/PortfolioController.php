<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;

class PortfolioController extends Controller {
    
    public function index() {
        return response()->json([
            'projects' => Project::where('status', 'published')->orderBy('sort_order')->get(),
            'skills' => Skill::orderBy('category')->orderBy('sort_order')->get(),
        ]);
    }
}