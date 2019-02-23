<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Repositories\TaskRepository;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    /**
     * TaskRepository instance
     *
     * @var TaskRepository
     */
    protected $tasks;

    /**
     * 新しいコントローラインスタンスの生成
     *
     * @return void
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');

        $this->tasks = $tasks;
    }

    /**
     * ユーザーの全タスクをリスト表示
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(TaskRequest $request)
    {
        return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }

    /**
     * 新タスク作成
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(TaskRequest $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|',
        ]);

        $request->user()->tasks()->create([
            'name' => $request->name,
        ]);

        return redirect('/tasks');
    }

    /**
     * 指定タスクの削除
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return Response
     */
    public function destroy(TaskRequest $request, Task $task)
    {
//        $this->authorize('destroy', $task);

        $task->delete();

        return redirect('/tasks');
    }
}
