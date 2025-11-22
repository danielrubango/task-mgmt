# Simple Laravel Task Management App

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
