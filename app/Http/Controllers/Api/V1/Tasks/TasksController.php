<?php

namespace App\Http\Controllers\Api\V1\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Resources\TasksResource;
use App\Models\V1\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Cache;
use App\Services\TaskCacheService;

class TasksController extends Controller
{
    private $taskCacheService;

    public function __construct(TaskCacheService $taskCacheService)
    {
        $this->taskCacheService = $taskCacheService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Default values for filters and sorting
        $status = $request->input('status', 'all'); // 'all' means no filter by default
        $sort = $request->input('sort', 'asc'); // 'asc' means ascending order by default

        $query = Task::where('user_id', Auth::user()->id);

        // Apply status filter
        if ($status === 'completed') {
            $query->where('status', 'completed');
        } elseif ($status === 'incomplete') {
            $query->where('status', 'completed');
        }

        // Apply sorting
        if ($sort === 'asc') {
            $query->orderBy('due_date');
        } elseif ($sort === 'desc') {
            $query->orderByDesc('due_date');
        }

        $cacheKey = 'tasks:' . Auth::user()->id . ':' . $status . ':' . $sort;
        // Try to retrieve tasks from cache
        $tasks = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($query) {
            // Paginate the query
            return $query->paginate(25);
        });

        return TasksResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $request->validated();

        $task = Task::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => 'incomplete'
        ]);
       
        // Clear the cache for the user's tasks
        $this->taskCacheService->clearTasksCache(Auth::user()->id);

        return new TasksResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        // Show task related to authorized user
        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : new TasksResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        // Check if task is related to authorized user
        if(Auth::user()->id !== $task->user_id) {
            return response()->error('You are not authorized to make this request', 403);
        }

        $task->update($request->all());

        // Clear the cache for the user's tasks
        $this->taskCacheService->clearTasksCache(Auth::user()->id);

        return new TasksResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Delete task related to authorized user
        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : $task->delete();

        // Clear the cache for the user's tasks
        $this->taskCacheService->clearTasksCache(Auth::user()->id);
    }

    private function isNotAuthorized($task)
    {
        // Check authorization
        if(Auth::user()->id !== $task->user_id) {
            return response()->error('You are not authorized to make this request', 403);
        }
    }
}
