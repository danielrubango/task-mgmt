<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\CompleteTaskRequest;
use App\Models\Project;

class CompleteTaskController extends Controller
{
    public function __invoke(CompleteTaskRequest $request, Project $project, Task $task)
    {
        $completed = !$request->validated('completed');
        $task->update(['completed' => $completed]);

        $message = $completed ? 'Task marked as completed!' : 'Task marked as incomplete!';

        return redirect()
            ->route('projects.index', $project)
            ->with('success', $message);
    }
}
