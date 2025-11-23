<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    /**
     * Store a newly created project task.
     */
    public function store(TaskRequest $request, Project $project)
    {
        $project->tasks()->create($request->validated());
        
        return redirect()
            ->back()
            ->with('success', 'Task created successfully!');
    }

    public function update(TaskRequest $request, Project $project, Task $task)
    {
        $task->update($request->validated());
        
        return redirect()
            ->back()
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();
        
        return redirect()
            ->route('projects.index', $project)
            ->with('success', 'Task deleted successfully!');
    }
}
