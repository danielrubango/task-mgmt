<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'priority',
        'completed',
    ];

    protected $casts = [
        'priority' => 'integer',
        'completed' => 'boolean',
    ];

    /**
     * Get the project that the task belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected static function booted(): void
    {
        // When a task is created, set its priority to the end of the project's tasks
        static::creating(function (Task $task) {
            $task->priority = $task->project->tasks()->count();
        });
    }
}
