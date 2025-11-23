<?php

namespace App\Models;

use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    /**
     * Get the tasks for the project.
     * Completed tasks first, then ordered by priority.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class)
            ->orderByRaw("
                CASE 
                    WHEN completed = 1 THEN 0 
                    ELSE 1 
                END ASC
            ")
            ->orderByRaw("
                CASE 
                    WHEN completed = 0 THEN priority 
                    ELSE updated_at 
                END ASC
            ")
            ->orderBy('updated_at', 'ASC');
    }

    /**
     * Get completed tasks for the project.
     * 
     * @param bool $completed
     */
    public function completedTasks(bool $completed = true)
    {
        return $this->tasks()
            ->where('completed', $completed);
    }

    /**
     * Get the completion percentage of tasks for the project.
     */
    public function getCompletionPercentageAttribute()
    {
        $tasks_count = $this->tasks()->count();
        $completed_tasks_count = $this->tasks()->where('completed', true)->count();

        // to prevent division by zero, if there are no tasks, return 0
        if ($tasks_count == 0) {
            return 0;
        }

        $completion_percentage = $completed_tasks_count / $tasks_count * 100;

        return Number::percentage($completion_percentage);
    }
}
