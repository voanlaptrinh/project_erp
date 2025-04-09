<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Clients\HomeController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/', [LoginController::class, 'showform'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('post_login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// Route::get('/admin/projects', [ProjectController::class, 'index'])->name('admin.project.index');

Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');



    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
});
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::post('/upload-image', [UploadController::class, 'uploadImage'])->name('upload-image');
    Route::post('/delete-image', [UploadController::class, 'deleteImage'])->name('delete-image');
    Route::prefix('/projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('admin.projects.index');
        Route::get('/create', [ProjectController::class, 'create'])->name('admin.projects.create');
        Route::post('/store', [ProjectController::class, 'store'])->name('admin.projects.store');
        Route::get('/show/{project}', [ProjectController::class, 'show'])->name('admin.projects.show');
        Route::get('/edit/{project}', [ProjectController::class, 'edit'])->name('admin.projects.edit');
        Route::put('/update/{project}', [ProjectController::class, 'update'])->name('admin.projects.update');
        Route::delete('/destroy/{alias}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
    });

    Route::prefix('/tasks')->group(function () {
        Route::get('/{project:alias?}', [TaskController::class, 'index'])->name('admin.projects.tasks');
        Route::get('/create/{project:alias}', [TaskController::class, 'create'])->name('admin.tasks.create');
        Route::post('/store/{project:alias}', [TaskController::class, 'store'])->name('admin.tasks.store');
        Route::get('/{project}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('admin.projects.tasks.edit');
        Route::get('/{project}/tasks/{task}/show', [TaskController::class, 'show'])->name('admin.projects.tasks.show');
        Route::put('/{project}/tasks/{task}', [TaskController::class, 'update'])->name('admin.projects.tasks.update');
        Route::delete('/{project}/tasks/{task}', [TaskController::class, 'destroy'])->name('admin.projects.tasks.destroy');
    });




    // Các route khác cho Sprint, Task, Resource và Issue (Bạn có thể chỉnh sửa để phù hợp với phân quyền)
    // Route::resource('sprints', SprintController::class);
    // Route::resource('tasks', TaskController::class);
    // Route::resource('resources', ResourceController::class);
    // Route::resource('issues', IssueController::class);
});
