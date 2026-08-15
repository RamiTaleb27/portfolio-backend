<?php

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// ============================================
// PUBLIC ROUTES
// ============================================

// Simple token login (NO Sanctum needed!)
Route::post('/token-login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Generate token and save to remember_token column
    $token = Str::random(60);
    $user->remember_token = $token;
    $user->save();

    return response()->json([
        'token' => $token,
        'user' => $user->only(['id', 'name', 'email']),
    ]);
});

// Public portfolio data
Route::get('/portfolio', [PortfolioController::class, 'index']);

// Public contact form
Route::post('/messages', [MessageController::class, 'store']);


// ============================================
// PROTECTED ROUTES (Custom Token Auth)
// ============================================

Route::middleware([\App\Http\Middleware\TokenAuth::class])->group(function () {
    
    // User info
    Route::get('/user', function (Request $request) {
        return Auth::user();
    });

    // Token logout
    Route::post('/logout', function (Request $request) {
        $user = Auth::user();
        if ($user) {
            $user->remember_token = null;
            $user->save();
        }
        return response()->json(['message' => 'Logged out']);
    });

    // Projects CRUD
    Route::apiResource('projects', ProjectController::class);

    // Skills CRUD
    Route::apiResource('skills', SkillController::class);

    // Messages admin
    Route::get('/messages', [MessageController::class, 'index']);
    Route::get('/messages/{message}', [MessageController::class, 'show']);
    Route::patch('/messages/{message}', [MessageController::class, 'update']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);

}); // ← MAKE SURE THIS CLOSING }); IS THERE!