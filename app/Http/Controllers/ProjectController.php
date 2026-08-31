<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('projekte.index', [
            'projekte' => Project::orderBy('position')->withCount('posts')->get(),
        ]);
    }

    public function show(Project $project): View
    {
        $project->load(['posts' => fn ($q) => $q->published()->latest('published_at')]);

        return view('projekte.show', compact('project'));
    }
}
