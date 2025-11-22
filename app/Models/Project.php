<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * Get the tasks for the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('priority', 'asc');
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

        return $completed_tasks_count / $tasks_count * 100;
    }
}
