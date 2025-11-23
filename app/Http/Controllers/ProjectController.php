<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    public function index(?Project $project = null)
    {
        $projects = Project::all();
        
        return view('projects', [
            'projects' => $projects,
            'project' => $project?->load('tasks'),
        ]);
    }

    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->validated());

        return redirect()
            ->route('projects.index', $project)
            ->with('success', 'Project created successfully!');
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect()
            ->route('projects.index', $project)
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
