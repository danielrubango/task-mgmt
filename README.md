# Simple Laravel Task Management App for testing purposes

## Summary
This is a simple task management app built with Laravel 12, MySQL, and Tailwind CSS (no Livewire, Inertia.js, Vue.js or React.js). Without authentication, it allows users to create, update, delete, and list projects and tasks.

Tasks belongs to a projects. Projects can have multiple tasks. Tasks are ordered by priority and completion status. When all tasks in a project are completed, the project is marked automatically as completed.

## Features
- Project Management
  - Manage Projects
  - Add tasks to Project
- Task Management
  - Manage Tasks
  - Mark Task as Completed
  - Order Tasks by Priority (drag and drop, sortable)

## Technologies
- Laravel 12
- MySQL
- Tailwind CSS

## Setup
- Clone the repository `git clone https://github.com/danielrubango/task-mgmt.git` or download the zip file and extract it
- CD into the project `cd task-mgmt`
- Create a .env file `cp .env.example .env` and update the .env file with your database credentials ou use sqlite for quick setup (run `touch database/database.sqlite`)
- Run `composer install`
- Run `npm install`
- Run `npm run build`
- Run `php artisan migrate`
- Run `php artisan db:seed` to add sample data for testing purposes
- Run `php artisan serve`

## Usage
- Open `http://localhost:8000` in your browser

## How to use
- Click on a project from the dropdown list to select it
- Click on a project or task title from the list to edit it or click on the edit button to edit it, escape to cancel
- Click on the delete button to delete a project or task
- Click on the complete radio button to complete a task
- Click and hold the drag handle to reorder tasks

## Improvements
- Error handling when managing projects and tasks
- Reset the form to the original values when canceling an edit, if there were any changes, ask for confirmation
- Click everywhere on a task row to edit it
- Manage description of a project and a task
- Disable drag and drop for a completed task and hide the drag handle
- Hide the task row when dragging and show a placeholder
- Add a modal to confirm deletion
- Add order column to tasks table and define the priority of a task as High, Medium, Low
- Disable edit of completed tasks
- Add authentication
- Add rate limiting for API requests
- Create blade components for icons and buttons