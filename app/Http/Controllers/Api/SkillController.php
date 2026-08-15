<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller {
    
    public function index() {
        return Skill::orderBy('category')->orderBy('sort_order')->get();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'color' => 'required|string|max:50',
            'sort_order' => 'integer',
        ]);

        $skill = Skill::create($validated);
        return response()->json($skill, 201);
    }

    public function show(Skill $skill) {
        return $skill;
    }

    public function update(Request $request, Skill $skill) {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:50',
            'sort_order' => 'sometimes|integer',
        ]);

        $skill->update($validated);
        return response()->json($skill);
    }

    public function destroy(Skill $skill) {
        $skill->delete();
        return response()->noContent();
    }
}