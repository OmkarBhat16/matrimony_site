<?php

use App\Http\Middleware\LogApiRequests;
use Illuminate\Support\Facades\Route;

Route::middleware([LogApiRequests::class])->group(function (): void {
    // Add API routes here. They will all be logged by LogApiRequests.
});
