# Task Management API

This is a simple Task Management API built using Laravel. It allows users to manage tasks and perform various actions via HTTP requests.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)
- [Task Management](#task-management)
- [Filters and Sorting](#filters-and-sorting)
- [User Profile](#user-profile)
- [Technical Details](#technical-details)

## Features

- User Registration and Authentication
- Task Management (Create, Retrieve, Update, Delete, Mark as Completed)
- Task Filtering and Sorting
- User Profile Management

## Requirements

- PHP 8+
- Composer
- Laravel 10.x
- Database (e.g., MySQL)
- Laravel Sanctum (for authentication)

## Installation

1. **Clone the repository:**

   ```shell
   git clone https://github.com/yourusername/task-management.git

2. **Install PHP Dependencies:**
   ```shell
   composer install

3. **Create a .env file by copying the example:**
   cp .env.example .env

4. **Configure your database connection in the .env file:**
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

5. **Generate an application key:**
   php artisan key:generate

6. **Run database migrations and seeders:**
   php artisan migrate --seed

7. **Start the development server:**
   php artisan serve

   The API should now be accessible at http://localhost:8000.

## API Endpoints
 The API provides the following endpoints:

- Authentication
1. POST /api/v1/auth/login: Log in with valid credentials.
   ```json
   {
      "email": "your-email@example.com",
      "password": "your-password"
   }

   Example: curl -X POST http://localhost:8000/api/v1/auth/login -H "Accept: application/json" -d "email=your-email@example.com&password=your-password"

   ```json
   {
      "success": true,
      "data": {
         "user": {
            "id": 1,
            "name": "Name",
            "email": "your-email@example.com",
            "email_verified_at": null,
            "created_at": "2023-09-18T05:45:48.000000Z",
            "updated_at": "2023-09-18T05:45:48.000000Z"
         },
         "token": "5|HCuIeDi6ueeuLNuunBQITcKWPo9YrpaDyqwct63o2099784a"
      },
      "message": null
   }


2. POST /api/v1/auth/register: Register a new user.
   ```json
   {
    "email": "your-email@example.com",
    "password": "your-password",
    "password_confirmation": "your-password",
    "name": "Name"
   }

   Example: curl -X POST http://localhost:8000/api/v1/auth/register -H "Accept: application/json" -d "email=your-email@example.com&password=your-password&password_confirmation=password&name=name"

   ```json
   {
      "success": true,
      "data": {
         "user": {
            "id": 1,
            "name": "Name",
            "email": "your-email@example.com",
            "email_verified_at": null,
            "created_at": "2023-09-18T05:45:48.000000Z",
            "updated_at": "2023-09-18T05:45:48.000000Z"
         },
         "token": "5|HCuIeDi6ueeuLNuunBQITcKWPo9YrpaDyqwct63o2099784a"
      },
      "message": null
   }

- Task Management
1. GET /api/v1/tasks: Retrieve a list of tasks.
   To retrieve a list of tasks, make a GET request to `/api/v1/tasks`. You can also filter tasks by status (completed or incomplete) and sort them by due date (ascending or descending).

   Example:
   ```shell
   # Retrieve all tasks
   curl -X GET http://localhost:8000/api/v1/tasks -H "Authorization: Bearer your-access-token"

   # Retrieve completed tasks
   curl -X GET http://localhost:8000/api/v1/tasks?status=completed -H "Authorization: Bearer your-access-token"

   # Retrieve incomplete tasks sorted by due date in ascending order
   curl -X GET http://localhost:8000/api/v1/tasks?status=incomplete&sort=asc -H "Authorization: Bearer your-access-token"

2. GET /api/v1/tasks/{id}: Retrieve details of a specific task.
   To retrieve details of a specific task, make a GET request to /api/v1/tasks/{id}, where {id} is the task's ID.

   ```shell
   curl -X GET http://localhost:8000/api/v1/tasks/1 -H "Authorization: Bearer your-access-token"

3. POST /api/v1/tasks: Create a new task.
   ```json
   {
      "title": "task 569",
      "description": "Description of task 569",
      "due_date": "2023-10-01 00:00:00"
   }

   Example:
   ```shell
   curl -X POST http://localhost:8000/api/v1/tasks -H "Content-Type: application/json, Authorization: Bearer your-access-token" -d "{"title": "New Task","description": "Description of the task","due_date": "2023-12-31 23:59:59"}'

   ```json
   {
      "data": {
         "id": "7",
         "attributes": {
            "title": "task 569",
            "description": "Description of task 569",
            "due_date": "2023-10-01 00:00:00",
            "status": "incomplete",
            "created_at": "2023-09-18T07:08:05.000000Z",
            "updated_at": "2023-09-18T07:08:05.000000Z"
         },
         "relationships": {
            "id": "1",
            "User name": "Name",
            "User email": "your-email@example.com"
         }
      }
   }
4. PUT /api/v1/tasks/{id}: Update task details.
   ```json
   {
      "title": "task 569",
      "description": "Description of task 569",
      "due_date": "2023-10-01 00:00:00"
   }

   Example: 
   ```shell
   curl -X PUT http://localhost:8000/api/v1/tasks/1 -H "Content-Type: application/json, Authorization: Bearer your-access-token" -d "{"title": "New Task","description": "Description of the task","due_date": "2023-12-31 23:59:59"}'

   ```json
   {
      "data": {
         "id": "7",
         "attributes": {
            "title": "task 569",
            "description": "Description of task 569",
            "due_date": "2023-10-01 00:00:00",
            "status": "incomplete",
            "created_at": "2023-09-18T07:08:05.000000Z",
            "updated_at": "2023-09-18T07:08:05.000000Z"
         },
         "relationships": {
            "id": "1",
            "User name": "Name",
            "User email": "your-email@example.com"
         }
      }
   }
5. DELETE /api/v1/tasks/{id}: Delete a task.
   ```shell
   curl -X DELETE http://localhost:8000/api/v1/tasks/1 -H "Content-Type: application/json, Authorization: Bearer your-access-token"

- Task Status
1. PUT /api/v1/tasks/status/{id}: Mark a task as completed.
   ```json
   {
      "status": "completed"
   }

   ```shell
   curl -X PUT http://localhost:8000/api/v1/tasks/status/1 -H "Content-Type: application/json, Authorization: Bearer your-access-token" -d '{"title": "Updated Task","description": "Updated description","due_date": "2023-12-31 23:59:59"}'

- User Profile
1. GET /api/v1/auth/logout: Log out the current user.
   ```shell
   curl -X PUT http://localhost:8000/api/v1/auth/logout -H "Content-Type: application/json, Authorization: Bearer your-access-token"
2. GET /api/v1/auth/user: Retrieve the user's profile information.
   ```shell
   curl -X PUT http://localhost:8000/api/v1/profile -H "Content-Type: application/json, Authorization: Bearer your-access-token"
3. PUT /api/v1/auth/user: Update the user's profile (name and email).
   ```shell
   curl -X PUT http://localhost:8000/api/v1/profile -H "Content-Type: application/json, Authorization: Bearer your-access-token" -d "{"name": "name","password": "password","password_confirmation": "password"}'

## Technical Details
Built with Laravel 10.x
Uses Laravel Sanctum for authentication
Implements API versioning (e.g., /api/v1/)