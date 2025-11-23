<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReorderTaskController;
use App\Http\Controllers\CompleteTaskController;

// Redirect root to projects index
Route::redirect('/', '/projects');

// Show Projects routes
Route::get('/projects/{project?}', [ProjectController::class, 'index'])->name('projects.index');

// Add a project
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

// Delete a project
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

// Update a project
Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

// Add a task to a project
Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Delete a task from a project
Route::delete('/projects/{project}/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

// Update a task from a project
Route::put('/projects/{project}/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

// Mark a task as completed
Route::put('/projects/{project}/tasks/{task}/complete', CompleteTaskController::class)->name('tasks.complete');

// Reorder tasks
Route::post('/projects/{project}/tasks/reorder', ReorderTaskController::class)->name('tasks.reorder');