<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});
Route::get('setlocale/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return redirect()->back();
})->name('setlocale');
Route::group(['middleware' => 'locale'], function() {
    Auth::routes();
});

Route::get('/logout', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {

    Route::post('/upload', [App\Http\Controllers\UploadController::class, 'upload']);
    Route::group(['middleware' => 'admin'], function () {
        //USERS
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users.index')->middleware('disable.cache');
            Route::get('/search/{searchTerm?}', [App\Http\Controllers\UserController::class, 'index'])->name('users.search');
            Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
            Route::post('/', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
            Route::get('/edit/{user}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
            Route::put('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
            Route::delete('/delete', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
            Route::get('/filter/', [App\Http\Controllers\UserController::class, 'index'])->name('users.filter')->middleware('disable.cache');
        });

        //CLIENTS
        Route::group(['prefix' => 'clients'], function () {
            Route::get('/', [App\Http\Controllers\ClientController::class, 'index'])->name('clients.index');
            Route::get('/search/{searchTerm?}', [App\Http\Controllers\ClientController::class, 'index'])->name('clients.search');
            Route::get('/show/{client}', [App\Http\Controllers\ClientController::class, 'show'])->name('clients.show');
            Route::get('/create', [App\Http\Controllers\ClientController::class, 'create'])->name('clients.create');
            Route::post('/', [App\Http\Controllers\ClientController::class, 'store'])->name('clients.store');
            Route::get('/edit/{client}', [App\Http\Controllers\ClientController::class, 'edit'])->name('clients.edit');
            Route::put('/{client}', [App\Http\Controllers\ClientController::class, 'update'])->name('clients.update');
            Route::delete('/delete', [App\Http\Controllers\ClientController::class, 'destroy'])->name('clients.destroy');
        });

        Route::group(['prefix' => 'project-statuses'], function () {
            Route::get('/', [App\Http\Controllers\ProjectStatusController::class, 'index'])->name('project_statuses.index');
            Route::get('/create', [App\Http\Controllers\ProjectStatusController::class, 'create'])->name('project_statuses.create');
            Route::post('/store', [App\Http\Controllers\ProjectStatusController::class, 'store'])->name('project_statuses.store');
            Route::post('/changeOrder', [App\Http\Controllers\ProjectStatusController::class, 'changeOrder'])->name('project_statuses.change_order');
            Route::get('/edit/{projectStatus}', [App\Http\Controllers\ProjectStatusController::class, 'edit'])->name('project_statuses.edit');
            Route::put('/{projectStatus}', [App\Http\Controllers\ProjectStatusController::class, 'update'])->name('project_statuses.update');
            Route::delete('/delete', [App\Http\Controllers\ProjectStatusController::class, 'destroy'])->name('project_statuses.destroy');
        });


        Route::group(['prefix' => 'event-types'], function () {
            Route::get('/', [App\Http\Controllers\EventTypeController::class, 'index'])->name('event_types.index');
            Route::get('/create', [App\Http\Controllers\EventTypeController::class, 'create'])->name('event_types.create');
            Route::post('/', [App\Http\Controllers\EventTypeController::class, 'store'])->name('event_types.store');
            Route::post('/changeOrder', [App\Http\Controllers\EventTypeController::class, 'changeOrder'])->name('event_types.change_order');
            Route::get('/edit/{eventType}', [App\Http\Controllers\EventTypeController::class, 'edit'])->name('event_types.edit');
            Route::put('/{eventType}', [App\Http\Controllers\EventTypeController::class, 'update'])->name('event_types.update');
            Route::delete('/delete', [App\Http\Controllers\EventTypeController::class, 'destroy'])->name('event_types.destroy');
        });

        Route::group(['prefix' => 'work-time-types'], function () {
            Route::get('/', [App\Http\Controllers\WorkTimeTypeController::class, 'index'])->name('work_time_types.index');
            Route::get('/create', [App\Http\Controllers\WorkTimeTypeController::class, 'create'])->name('work_time_types.create');
            Route::post('/', [App\Http\Controllers\WorkTimeTypeController::class, 'store'])->name('work_time_types.store');
            Route::post('/changeOrder', [App\Http\Controllers\WorkTimeTypeController::class, 'changeOrder'])->name('work_time_types.change_order');
            Route::get('/edit/{workTimeType}', [App\Http\Controllers\WorkTimeTypeController::class, 'edit'])->name('work_time_types.edit');
            Route::put('/{workTimeType}', [App\Http\Controllers\WorkTimeTypeController::class, 'update'])->name('work_time_types.update');
            Route::delete('/delete', [App\Http\Controllers\WorkTimeTypeController::class, 'destroy'])->name('work_time_types.destroy');
        });

        Route::group(['prefix' => 'projects'], function () {
            Route::delete('/delete', [App\Http\Controllers\ProjectController::class, 'destroy'])->name('projects.destroy');
            Route::post('/archive/{project}', [App\Http\Controllers\ProjectController::class, 'archive'])->name('projects.archive');
            Route::post('/unarchive/{project}', [App\Http\Controllers\ProjectController::class, 'unarchive'])->name('projects.unarchive');
        });
    });

    Route::group(['middleware' => 'admin.worker'], function () {
        Route::group(['prefix' => 'work-times'], function () {
            Route::get('/', [App\Http\Controllers\WorkTimeController::class, 'index'])->name('work_times.index');
            Route::post('/', [App\Http\Controllers\WorkTimeController::class, 'store'])->name('work_times.store');
            Route::put('/{workTime}', [App\Http\Controllers\WorkTimeController::class, 'update'])->name('work_times.update');
            Route::delete('/delete', [App\Http\Controllers\WorkTimeController::class, 'destroy'])->name('work_times.destroy');
        });
        Route::group(['prefix' => 'project-elements'], function () {
            Route::get('/create/{project}', [App\Http\Controllers\ProjectElementController::class, 'create'])->name('project_elements.create');
            Route::post('/', [App\Http\Controllers\ProjectElementController::class, 'store'])->name('project_elements.store');
            Route::get('/{projectElement}', [App\Http\Controllers\ProjectElementController::class, 'edit'])->name('project_elements.edit');
            Route::put('/{projectElement}', [App\Http\Controllers\ProjectElementController::class, 'update'])->name('project_elements.update');
            Route::delete('/deleted', [App\Http\Controllers\ProjectElementController::class, 'destroy'])->name('project_elements.destroy');
            Route::post('/changeOrder', [App\Http\Controllers\ProjectElementController::class, 'changeOrder'])->name('project_elements.change_order');
        });

        Route::group(['prefix' => 'project-versions'], function () {
            Route::get('/create/{projectElementComponent}', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'create'])->name('project_element_component_versions.create');
            Route::post('/', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'store'])->name('project_element_component_versions.store');
            Route::get('/edit/{projectElementComponentVersion}', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'edit'])->name('project_element_component_versions.edit');
            Route::put('/{projectElementComponentVersion}', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'update'])->name('project_element_component_versions.update');
            Route::delete('/deleted', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'destroy'])->name('project_element_component_versions.destroy');
            Route::get('/change-version/{projectElementComponentVersion}/{status}', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'changeStatus'])->name('project_element_component_versions.change_status');
            Route::post('/store/video', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'storeVideo'])->name('project_element_component_versions.store_video');
            Route::post('/store/pdf', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'storePDF'])->name('project_element_component_versions.store_pdf');
            Route::post('/video-by-link', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'storeVideoByLink'])->name('project_element_component_versions.store_video_by_link');
        });

        Route::group(['prefix' => 'tasks'], function () {
            Route::post('/', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
            Route::put('/update/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
            Route::delete('/delete', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
        });

        Route::group(['prefix' => 'board'], function () {
            Route::get('/', [App\Http\Controllers\BoardController::class, 'index'])->name('board.index');
            Route::get('/client', [App\Http\Controllers\BoardController::class, 'clientBoard'])->name('board.client');
        });

        Route::group(['prefix' => 'calendar'], function () {
            Route::get('/', [App\Http\Controllers\EventController::class, 'index'])->name('calendar.index')->middleware('disable.cache');

            Route::post('/get-project-members', [App\Http\Controllers\EventController::class, 'getProjectMembers'])->name('calendar.getProjectsMembers');
            Route::group(['prefix' => 'events'], function () {
                Route::post('/store', [App\Http\Controllers\EventController::class, 'store'])->name('events.store');
                Route::post('/update/{event}', [App\Http\Controllers\EventController::class, 'update'])->name('events.update');
                Route::delete('/delete', [App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy');
            });
        });
        Route::group(['prefix' => 'projects'], function () {
            Route::get('/create', [App\Http\Controllers\ProjectController::class, 'create'])->name('projects.create');
            Route::post('/', [App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
            Route::get('/edit/{project}', [App\Http\Controllers\ProjectController::class, 'edit'])->name('projects.edit');
            Route::put('/{project}', [App\Http\Controllers\ProjectController::class, 'update'])->name('projects.update');
            Route::get('/archived', [App\Http\Controllers\ProjectController::class, 'archivedProjects'])->name('projects.archived')->middleware('disable.cache');
            Route::get('/archived/filter/', [App\Http\Controllers\ProjectController::class, 'archivedProjects'])->name('projects.archived_filter')->middleware('disable.cache');
            Route::get('/change-priority/{project}', [App\Http\Controllers\ProjectController::class, 'changePriority'])->name('projects.change_priority');
        });
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update/{user}', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    });

    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index')->middleware('disable.cache');
        Route::get('/search/{searchTerm?}', [App\Http\Controllers\ProjectController::class, 'index'])->name('projects.search')->middleware('disable.cache');
        Route::get('/filter/', [App\Http\Controllers\ProjectController::class, 'index'])->name('projects.filter')->middleware('disable.cache');
    });
});

Route::group(['middleware' => 'project.access'], function () {
    Route::get('/project-versions/show/{projectElementComponentVersion}', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'show'])->name('project_element_component_versions.show')->middleware('project.version.access');
    Route::get('/projects/show/{project}/', [App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');
});

Route::get('/projects/show-project/{hashedUrl}', [App\Http\Controllers\ProjectController::class, 'showByUrl'])->name('projects.show_by_url')->middleware('project.url.access');
Route::get('/projects/show-project/{projectElementComponentVersion}/{hashedUrl}/', [App\Http\Controllers\ProjectElementComponentVersionController::class, 'show'])->name('project_element_component_versions.show_by_url')->middleware('project.url.access');
Route::post('/comments/store', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store')->middleware('project.url.access');

Route::get('/login/project/{hashedUrl}/{projectElementComponentVersion?}', [App\Http\Controllers\Auth\LoginToProjectController::class, 'loginToProject'])->name('project.login');
Route::get('/login/project/{projectElementComponentVersion}', [App\Http\Controllers\Auth\LoginToProjectController::class, 'loginToProjectVersion'])->name('version.login');
Route::post('/login-by-name', [App\Http\Controllers\Auth\LoginToProjectController::class, 'loginByName'])->name('login.name');

Route::get('/youtube/', function () {
    return view('youtube');
});
