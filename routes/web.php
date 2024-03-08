<?php

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;

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

Route::get('/', fn() => redirect()->route('tasks.index'));

Route::get('/tasks', fn() => view('index', ['tasks' => Task::latest()->get()]))->name('tasks.index');

Route::view('/tasks/create', 'create');

Route::get('/tasks/{task}/edit', fn(Task $task) => view('edit', ['task' => $task]) )->name('tasks.edit');

Route::get('/tasks/{id}', fn($id) =>  view('show', ['task' => Task::findOrFail($id)]))->name('tasks.show');

Route::post('/tasks', function(Request $request) {
    $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'long_description' => 'required'
    ]);

    $task = new Task;
    $task->title = $data['title'];
    $task->description = $data['description'];
    $task->long_description = $data['long_description'];

    $task->save();

    return redirect()->route('tasks.show',[$task->id])->with('success', 'Task created successfully!');
})->name('tasks.store');

Route::put('/tasks/{id}', function($id, Request $request) {
    $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'long_description' => 'required'
    ]);

    $task = Task::findOrFail($id);
    $task->title = $data['title'];
    $task->description = $data['description'];
    $task->long_description = $data['long_description'];

    $task->save();

    return redirect()->route('tasks.show',[$task->id])->with('success', 'Task updated successfully!');
})->name('tasks.update');
