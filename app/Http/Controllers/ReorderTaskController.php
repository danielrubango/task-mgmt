<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\ReorderTaskRequest;

class ReorderTaskController extends Controller
{
    /**
     * Update the priority of all tasks for a specific project based on their new order.
     */
    public function __invoke(ReorderTaskRequest $request)
    {
        $task_ids = $request->validated('task_ids');

        $project = Project::find($request->route('project'));

        // Get only non-completed task IDs from the project
        $tasks_to_update = $project->completedTasks(false)
            ->pluck('id')
            ->toArray();

        $updated_task_ids = [];

        foreach ($task_ids as $priority => $task_id) {
            // Skip completed tasks
            if (!in_array($task_id, $tasks_to_update)) {
                continue;
            }

            $updated_task_ids[] = [
                'id' => $task_id,
                'priority' => $priority + 1,
            ];
        }

        if (!empty($updated_task_ids)) {
            Task::updateBatch($updated_task_ids);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tasks reordered successfully'
        ]);
    }
}
