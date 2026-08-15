<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;

class PortfolioController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::orderBy('sort_order')->get();
        $skills = Skill::orderBy('sort_order')->get();

        return response()->json([
            'projects' => $projects,
            'skills' => $skills,
        ]);
    }
}