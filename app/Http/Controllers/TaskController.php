<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations\Delete;

/**
 * @OA\Info(
 *     title="Tasks API",
 *     version="1.0.0",
 *     description="API for managing tasks",
 *     @OA\Contact(
 *         email="toni.fernandez@cirvianum.cat"
 *     )
 * )
 * 
 * * @OA\Server(
 *     url="http://localhost",
 *     description="Local development server"
 * )
 */

 



class TaskController extends Controller
{
/**
     * Tasks List
     * @OA\Get (
     *     path="/api/tasks",
     *     tags={"tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="descriptioon",
     *                         type="string",
     *                         example="Non placeat illum ex dolorem sint fugit natus."
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $tasks = Task::all();
            return response()->json([
                'status' => true,
                'tasks' => $tasks,
                'httpCode' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'httpCode' => 500
            ]);
        }
    }

    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
     /**
     * Crea uan tasca
     * @OA\Put (
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *  
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      )
     * )
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'errorCode' => 422
            ]);
        }

        try {
            $task = Task::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Task created successfully',
                'httpCode' => 201
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'httpCode' => 500
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        if (is_null($task)) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found',
                'errorCode' => 404
            ]);
        }

        try {
            return response()->json([
                'status' => true,
                'task' => $task,
                'httpCode' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'httpCode' => 500
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $task = Task::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errorCode' => 404
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'errorCode' => 422
            ]);
        }


        try {
            $task = Task::find($id);
            $task->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Task updated successfully',
                'httpCode' => 201
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'httpCode' => 500
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Task::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errorCode' => 404
            ]);
        }

        try {
            Task::destroy($id);
            return response()->json([
                'status' => true,
                'message' => 'Task deleted successfully',
                'httpCode' => 200
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'httpCode' => 500
            ]);
        }
    }
}
