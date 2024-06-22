<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
     /**
     * Display a listing of the resource.
     */

     
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $users = User::with('roles')->paginate($perPage);
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
{
    try {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        foreach ($validated['roles'] as $roleId) {
            $role = Role::findOrFail($roleId);
            $user->assignRole($role); // Asignar el rol al usuario
        }

        return response()->json([
            'message' => 'Usuario creado con éxito.'
        ], 201); 

    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422); 

    } catch (\Exception $e) {
        Log::error('Error al crear el usuario: ' . $e->getMessage(), [
            'request_data' => $request->all(),
            'exception_message' => $e->getMessage(),
            'exception_trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'message' => 'An error occurred',
            'error' => $e->getMessage()
        ], 500); 
    }
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
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

public function update(UpdateUserRequest $request, $id)
{
    try {
        $validated = $request->validated();

        $user = User::findOrFail($id);
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->roles()->sync($validated['roles']);

        $user->save();

        return response()->json(['message' => 'Usuario actualizado exitosamente']);
    
    } catch (\Exception $e) {
        Log::error('Error al actualizar el usuario: ' . $e->getMessage(), [
            'user_id' => $id,
            'request_data' => $request->all(),
            'exception_message' => $e->getMessage(),
            'exception_trace' => $e->getTraceAsString(),
        ]);
        return response()->json([
            'message' => 'Error al actualizar el usuario',
            'error' => $e->getMessage()
        ], 500); 
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function auth(AuthUserRequest $request)  
    {
        if($request->validated()) {
            $user = User::whereEmail($request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'error' => 'Credenciales incorrectas.'
                ]);
            }else {
                return response()->json([
                    'user' => $user,
                    'message' => 'Bienvenido, ' . $user->name . '.',
                    'currentToken' => $user->createToken('new_user')->plainTextToken
                ]);
            }
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    }
}
