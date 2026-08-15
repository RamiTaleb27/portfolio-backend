<?php

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;



Route::post('/token-login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('portfolio')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
    ]);
});


// CUSTOM API AUTH (returns JSON, no redirects)
Route::middleware(['web'])->group(function () {
    
    Route::post('/auth/login', function (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $request->session()->regenerate();

        return response()->json([
            'user' => Auth::user(),
            'message' => 'Login successful'
        ]);
    });

    Route::post('/auth/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out']);
    })->middleware('auth');

});

// Public portfolio data
Route::get('/portfolio', [PortfolioController::class, 'index']);

// Public contact form
Route::post('/messages', [MessageController::class, 'store']);

// Protected routes (require Sanctum auth)
Route::middleware(['auth:sanctum'])->group(function () {
    
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
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
});