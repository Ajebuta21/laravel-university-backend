<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LecturerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:lecturers,email',
            'number' => 'required|string',
            'department' => 'required|string',
            'password' => 'required|string|min:6',
            'token' => 'required',
        ]);

        $token = '5312';

        if ($request->input("token") !== $token) {
            return response()->json(['message' => "Invalid token."], 400);
        }

        $lecturer = new Lecturer([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'number' => $request->input('number'),
            'department' => $request->input('department'),
            'password' => Hash::make($request->input('password')),
        ]);
        $lecturer->save();

        return response()->json(['message' => 'Lecturer created successfully', 'lecturer' => $lecturer], 201);
    }

    public function index()
    {
        $lecturers = Lecturer::all();
        return response()->json(['lecturers' => $lecturers], 200);
    }

    public function getById($id)
    {
        $lecturer = Lecturer::find($id);

        if (!$lecturer) {
            return response()->json(['message' => 'Lecturer not found'], 404);
        }

        return response()->json(['lecturer' => $lecturer], 200);
    }

    public function editBasicInfo(Request $request, $id)
    {
        $lecturer = Lecturer::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:lecturers,email,' . $id,
            'number' => 'required|string',
            'department' => 'required|string',
        ]);

        $lecturer->first_name = $request->input('first_name');
        $lecturer->last_name = $request->input('last_name');
        $lecturer->email = $request->input('email');
        $lecturer->number = $request->input('number');
        $lecturer->department = $request->input('department');
        $lecturer->save();

        return response()->json(['message' => 'Lecturer information updated successfully', 'lecturer' => $lecturer], 200);
    }

    public function editPassword(Request $request, $id)
    {
        $lecturer = Lecturer::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $lecturer->password = Hash::make($request->input('password'));
        $lecturer->save();

        return response()->json(['message' => 'Lecturer password updated successfully', 'lecturer' => $lecturer], 200);
    }
}
