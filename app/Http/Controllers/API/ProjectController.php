<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
   public function index()
{
    return Project::orderBy('sort_order')->get()->map(function ($project) {
        if ($project->image) {
            $project->image_url = asset('storage/' . $project->image);
        } else {
            $project->image_url = null;
        }
        return $project;
    });
}

   public function store(Request $request)
{
    \Log::info('Store called', $request->all());
    \Log::info('Has file?', ['hasFile' => $request->hasFile('image')]);
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'tagline' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|file|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        'featured' => 'nullable',
        'status' => 'required|in:draft,published',
        'tags' => 'nullable|string',
        'live_url' => 'nullable|string',
        'github_url' => 'nullable|string',
    ]);

    $validated['slug'] = Str::slug($validated['name']);
    
    if (!empty($validated['tags'])) {
        $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
    } else {
        $validated['tags'] = [];
    }

    $validated['featured'] = $request->has('featured') && in_array($request->input('featured'), ['on', '1', 'true', 1, true], true);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('projects', 'public');
        $validated['image'] = $path;
        \Log::info('Image saved to: ' . $path);
    } else {
        $validated['image'] = null;
        \Log::info('No image uploaded');
    }

    $project = Project::create($validated);
    return response()->json($project, 201);
}

    public function show(Project $project)
    {
        return $project;
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'tagline' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'nullable|file|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'featured' => 'nullable',
            'status' => 'sometimes|in:draft,published',
            'tags' => 'nullable|string',
            'live_url' => 'nullable|string',
            'github_url' => 'nullable|string',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle tags
        if (isset($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        // Handle featured
        if ($request->has('featured')) {
            $validated['featured'] = in_array($request->input('featured'), ['on', '1', 'true', 1, true], true);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $path = $request->file('image')->store('projects', 'public');
            $validated['image'] = $path;
        }

        $project->update($validated);
        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();
        return response()->noContent();
    }
}