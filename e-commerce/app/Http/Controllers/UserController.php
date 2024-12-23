<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

      
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']), 
        ]);

        return response()->json(['message' => 'User created successfully']);
    }
    public function index()
{
    $users = User::all();
    return response()->json($users);
}
public function update(Request $request, $id)
{
   
    $validatedData = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|unique:users,email,' . $id,
    ]);

  
    $user = User::findOrFail($id);

    
    $user->update($validatedData);

 
    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
}
public function destroy($id)
{
    User::destroy($id);
    return response()->json(['message' => 'User deleted successfully']);
}
}
