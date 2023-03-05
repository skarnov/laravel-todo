<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;

class TaskController extends Controller
{
    public function saveTask(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'   => 'required',
            ],
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Form Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        } else {
            $ifExists = Task::where('name', $request->input('name'))->first();
            if ($ifExists) :
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'Table Unique Error',
                    'errors' => array('Already Saved!'),
                ], 422);
            else :
                $tasks = new Task;
                $tasks->fk_todo_id = $request->input('fk_todo_id');
                $tasks->name = $request->input('name');
                $tasks->created_by = auth()->user()->id;
                return $tasks->save();
            endif;
        }
    }

    public function manageTask()
    {
        return Task::leftJoin('todos', 'todos.id', '=', 'tasks.fk_todo_id')
            ->leftJoin('users', 'users.id', '=', 'tasks.created_by')
            ->select('tasks.id', 'tasks.name AS task_name', 'users.name AS user_name')
            ->orderByDesc('tasks.id')
            ->get();
    }

    public function selectTask($id)
    {
        return Task::find($id);
    }

    public function updateTask(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'   => 'required',
            ],
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Form Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        } else {
            $ifExists = Task::where('name', $request->input('name'))->where('id', '!=', $request->input('id'))->first();
            if ($ifExists) :
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'Table Unique Error',
                    'errors' => array('Already Saved!'),
                ], 422);
            else :
                $tasks = Task::find($request->input('id'));
                $tasks->fk_todo_id = $request->input('fk_todo_id');
                $tasks->name = $request->input('name');
                return $tasks->save();
            endif;
        }
    }

    public function deleteTask($id)
    {
        $ifExists = Task::where('id', $id)->first();
        if ($ifExists) :
            Task::where('id', $id)->delete();
            return response()->json([
                'msg'    => 'Deleted',
            ], 200);
        else :
            return response()->json([
                'status' => 'error',
                'msg'    => 'Invalid task',
            ], 422);
        endif;
    }
}