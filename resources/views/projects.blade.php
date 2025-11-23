<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Task Management App') }}</title>

        <!-- Styles -->
        @vite(['resources/css/app.css'])
    </head>
    <body>
        <x-flash-message />
        
        <header class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white">Task Management App</h1>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl px-4 pt-6">
                <!-- Add Project Form -->
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <div class="border-b border-gray-900/10 pb-4">
                        <h2 class="text-base/7 font-semibold text-gray-900">Add a new project</h2>
                        
                        <div class="mt-2">
                            <div class="mt-2">
                                <input id="title" type="text" name="title" class="block rounded-md bg-white w-full px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-500" placeholder="Enter project title" required />
                            </div>
                            <button type="submit" class="mt-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Create project</button>
                        </div>
                    </div>
                </form>

                <!-- Select Project Form -->
                <form action="" class="mt-4">
                    <div class="border-b border-gray-900/10 pb-4">
                        <h2 class="text-base/7 font-semibold text-gray-900">Select an existing project</h2>
                        <div class="mt-2 grid grid-cols-1">
                            <select id="projects" name="projects" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-500">
                                <option disabled {{ $project ? '' : 'selected' }}>Select a project</option>
                                @foreach ($projects as $project_item)
                                    <option value="{{ $project_item->id }}" {{ $project_item->id === $project?->id ? 'selected' : '' }}>{{ $project_item->title }}</option>
                                @endforeach
                            </select>

                            <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4">
                                <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mx-auto max-w-7xl px-4">
                @if($project)
                    <div class="bg-white shadow-sm rounded-lg">
                        <!-- Project Header -->
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 rounded-t-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <!-- Display Mode -->
                                    <div class="project-display-{{ $project->id }}">
                                        <h3 
                                            class="text-lg font-semibold text-gray-900 cursor-pointer hover:text-gray-600"
                                            onclick="toggleEditProject({{ $project->id }})"
                                        >
                                            {{ $project->title }}
                                        </h3>
                                        
                                        @if($project->description)
                                            <p class="mt-1 text-sm text-gray-600">{{ $project->description }}</p>
                                        @endif
                                    </div>

                                    <!-- Edit Form (Hidden by default) -->
                                    <form 
                                        action="{{ route('projects.update', $project) }}" 
                                        method="POST" 
                                        class="project-edit-{{ $project->id }} hidden"
                                    >
                                        @csrf
                                        @method('PUT')
                                        <div class="flex gap-2 items-center">
                                            <input 
                                                type="text" 
                                                name="title" 
                                                value="{{ $project->title }}"
                                                required
                                                placeholder="Project title"
                                                class="flex-1 rounded-md bg-white px-3 py-2 text-base font-semibold text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-600"
                                            />
                                            <button 
                                                type="submit" 
                                                class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500"
                                            >
                                                Save
                                            </button>
                                            <button 
                                                type="button" 
                                                onclick="toggleEditProject({{ $project->id }})"
                                                class="rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-400"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="flex items-center gap-3 ml-4">
                                    <!-- Project Stats -->
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            {{ $project->tasks->count() }} {{ Str::plural('task', $project->tasks->count()) }}
                                        </span>
                                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            {{ $project->completion_percentage }} complete
                                        </span>
                                    </div>

                                    <!-- Edit Icon -->
                                    <button 
                                        onclick="toggleEditProject({{ $project->id }})"
                                        class="text-gray-400 hover:text-gray-600 transition-colors"
                                        title="Edit project"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete Icon -->
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project and all its tasks?')">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Delete project"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Add Task Form -->
                        <div class="border-b border-gray-200 px-6 py-4">
                            <form action="{{ route('tasks.store', $project) }}" method="POST">
                                @csrf
                                <div class="flex gap-2">
                                    <input 
                                        type="text" 
                                        name="title" 
                                        placeholder="Add a new task..." 
                                        required
                                        class="flex-1 rounded-md bg-white px-3 py-2 text-sm text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-600"
                                    />
                                    <button 
                                        type="submit" 
                                        class="rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                                    >
                                        Add Task
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tasks List -->
                        <div 
                            id="sortable-tasks" 
                            data-project-id="{{ $project->id }}"
                            class="divide-y divide-gray-200"
                        >
                            @forelse($project->tasks as $task)
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors task-item" data-task-id="{{ $task->id }}">
                                    <div class="flex items-center gap-4">
                                        <!-- Drag Handle -->
                                        <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                            </svg>
                                        </div>

                                        <!-- Checkbox -->
                                        <form action="{{ route('tasks.complete', [$project, $task]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input 
                                                type="checkbox" 
                                                {{ $task->completed ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer"
                                            />
                                            <input type="hidden" name="completed" value="{{ $task->completed ? 1 : 0 }}">
                                        </form>

                                        <!-- Task Title -->
                                        <div class="flex-1">
                                            <div class="task-display-{{ $task->id }}">
                                                <span 
                                                    class="{{ $task->completed ? 'line-through text-gray-400' : 'text-gray-900' }} cursor-pointer hover:text-gray-600"
                                                    onclick="toggleEditTask({{ $task->id }})"
                                                >
                                                    {{ $task->title }}
                                                </span>
                                                
                                                @if($task->description)
                                                    <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>
                                                @endif
                                            </div>

                                            <!-- Edit Form (Hidden by default) -->
                                            <form 
                                                action="{{ route('tasks.update', [$project, $task]) }}" 
                                                method="POST" 
                                                class="task-edit-{{ $task->id }} hidden"
                                            >
                                                @csrf
                                                @method('PUT')
                                                <div class="flex gap-2">
                                                    <input 
                                                        type="text" 
                                                        name="title" 
                                                        value="{{ $task->title }}"
                                                        required
                                                        class="flex-1 rounded-md bg-white px-3 py-1.5 text-sm text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-600"
                                                    />
                                                    <button 
                                                        type="submit" 
                                                        class="rounded-md bg-green-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-green-500"
                                                    >
                                                        Save
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        onclick="toggleEditTask({{ $task->id }})"
                                                        class="rounded-md bg-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-400"
                                                    >
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Edit Icon -->
                                        <button 
                                            onclick="toggleEditTask({{ $task->id }})"
                                            class="text-gray-400 hover:text-gray-600 transition-colors"
                                            title="Edit task"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <form action="{{ route('tasks.destroy', [$project, $task]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="text-red-600 hover:text-red-800 transition-colors"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No tasks</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No project selected</h3>
                        <p class="mt-1 text-sm text-gray-500">Select a project above to view and manage its tasks.</p>
                    </div>
                @endif
            </div>
        </main>

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
    </body>
</html>
