<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TaskCacheService
{
    public function clearTasksCache($userId)
    {
        Cache::forget('tasks:' . $userId . ':all:asc');
        Cache::forget('tasks:' . $userId . ':all:desc');
        Cache::forget('tasks:' . $userId . ':completed:asc');
        Cache::forget('tasks:' . $userId . ':completed:desc');
        Cache::forget('tasks:' . $userId . ':incomplete:asc');
        Cache::forget('tasks:' . $userId . ':incomplete:desc');
    }
}
