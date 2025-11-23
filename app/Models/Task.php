<?php

namespace App\Models;

use App\Traits\BulkUpdatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;
    use BulkUpdatable;

    protected $fillable = [
        'title',
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
