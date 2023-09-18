<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data = null, string $message = null, int $status_code = 200) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => $message,
            ], $status_code);
        });

        Response::macro('error', function ($error, $status_code) {
            return response()->json([
                'success' => false,
                'error' => $error,
            ], $status_code);
        });
    }
}
