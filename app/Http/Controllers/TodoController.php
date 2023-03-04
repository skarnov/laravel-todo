<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Todo;

class TodoController extends Controller
{
    public function saveTodo(Request $request)
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
            $ifExists = Todo::where('name', $request->input('name'))->first();
            if ($ifExists) :
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'Table Unique Error',
                    'errors' => array('Already Saved!'),
                ], 422);
            else :
                $todos = new Todo;
                $todos->name = $request->input('name');
                return $todos->save();
            endif;
        }
    }

    public function manageTodo()
    {
        return Todo::orderByDesc('id')->get();
    }

    public function selectTodo($id)
    {
        return Todo::find($id);
    }

    public function updateTodo(Request $request)
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
            $ifExists = Todo::where('name', $request->input('name'))->where('id', '!=', $request->input('id'))->first();
            if ($ifExists) :
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'Table Unique Error',
                    'errors' => array('Already Saved!'),
                ], 422);
            else :
                $todos = Todo::find($request->input('id'));
                $todos->name = $request->input('name');
                return $todos->save();
            endif;
        }
    }

    public function deleteTodo($id)
    {
        $ifExists = Todo::where('id', $id)->first();
        if ($ifExists) :
            Todo::where('id', $id)->delete();
            return response()->json([
                'msg'    => 'Deleted',
            ], 200);
        else :
            return response()->json([
                'status' => 'error',
                'msg'    => 'Invalid todo',
            ], 422);
        endif;
    }
}
