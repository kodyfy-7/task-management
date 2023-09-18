<?php

namespace App\Http\Controllers\Api\V1\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Resources\TasksResource;
use App\Models\V1\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MarkTaskRequest;
use App\Services\TaskCacheService;

class MarkCompleteTaskController extends Controller
{
    private $taskCacheService;

    public function __construct(TaskCacheService $taskCacheService)
    {
        $this->taskCacheService = $taskCacheService;
    }

    /**
     * Update the specified resource in storage.
     */
    public function __invoke(MarkTaskRequest $request, Task $task)
    {
        // Check if task is related to authorized user
        if(Auth::user()->id !== $task->user_id) {
            return response()->error('You are not authorized to make this request', 403);
        }

        // If task is already complete, return message
        if($task->status == 'completed')
        {
            return response()->error('Task is already completed', 400);
        }

        $task->update($request->all());

        // Clear the cache for the user's tasks
        $this->taskCacheService->clearTasksCache(Auth::user()->id);

        return new TasksResource($task);
    }

    
}
