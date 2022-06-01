<?php

use App\Http\Controllers\API\AttachmentController;
use App\Http\Controllers\API\BoardController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\ProjectElementComponentVersionController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WorkTimeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/project-versions/version-acceptance', [ProjectElementComponentVersionController::class, 'updateAcceptance'])->name('version.acceptance')->middleware('api');
Route::post('/get/comments', [CommentController::class, 'comments'])->name('version.comments')->middleware('api');
Route::post('/get/edlcomments', [CommentController::class, 'edlcomments'])->name('version.edlcomments')->middleware('api');
Route::post('/comment/store', [CommentController::class, 'store'])->name('comment.store')->middleware('api');
Route::put('/comment/update', [CommentController::class, 'update'])->name('comment.update')->middleware('api');
Route::post('/comment/delete', [CommentController::class, 'destroy'])->name('comment.delete')->middleware('api');

Route::post('/get/all-work-times', [WorkTimeController::class, 'getAllWorkTimes'])->name('project.all-work-times')->middleware('api');
Route::post('/get/work-times-month', [WorkTimeController::class, 'getWorkTimesPerMonth'])->name('project.work-times-month')->middleware('api');

Route::post('/attachment/store', [AttachmentController::class, 'store'])->name('attachment.store')->middleware('api');
Route::post('/attachment/pin', [AttachmentController::class, 'pin'])->name('attachment.pin')->middleware('api');
Route::post('/get/attachments', [AttachmentController::class, 'getAttachments'])->name('attachments.get')->middleware('api');

Route::post('/get/users', [UserController::class, 'getUsers'])->name('users.get')->middleware('api');

Route::post('/get/pdf', [ProjectElementComponentVersionController::class, 'getPDF'])->name('version.get_pdf')->middleware('api');

Route::post('/tasks/change', [TaskController::class, 'change'])->name('tasks.change')->middleware('api');
Route::post('/tasks-by-id', [TaskController::class, 'tasksById'])->name('tasks.get-by-id')->middleware('api');
Route::post('/tasks/change-order', [TaskController::class, 'changeOrder'])->name('tasks.change-order')->middleware('api');

Route::post('/project-board/change-project-status', [BoardController::class, 'changeStatus'])->name('board.change-status')->middleware('api');
Route::post('/project-board/change-order', [BoardController::class, 'changeOrder'])->name('board.change-order')->middleware('api');
Route::post('/project-board/filter', [BoardController::class, 'filter'])->name('board.filter')->middleware('api');

Route::post('/client-board/change-order', [BoardController::class, 'clientProjectsChangeOrder'])->name('client_board.projects-change-order')->middleware('api');
Route::post('/client-board/clients-change-order', [BoardController::class, 'clientsChangeOrder'])->name('client_board.clients-change-order')->middleware('api');
Route::post('/client-board/filter', [BoardController::class, 'clientFilter'])->name('client_board.filter')->middleware('api');
