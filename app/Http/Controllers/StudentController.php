<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return response()->json(['students' => $students], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_of_birth' => 'required|date',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:students',
            'phone_number' => 'required|string',
            'faculty' => 'required|string',
            'department' => 'required|string',
        ]);

        $regNumber = 'FUN/' . date('Y') . '/' . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT);

        $student = new Student([
            'reg_number' => $regNumber,
            'date_of_birth' => $request->input('date_of_birth'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'faculty' => $request->input('faculty'),
            'department' => $request->input('department'),
        ]);
        $student->save();

        return response()->json(['message' => 'Student created successfully', 'student' => $student], 201);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'date_of_birth' => 'required|date',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone_number' => 'required|string',
            'faculty' => 'required|string',
            'department' => 'required|string',
        ]);

        $student->date_of_birth = $request->input('date_of_birth');
        $student->first_name = $request->input('first_name');
        $student->last_name = $request->input('last_name');
        $student->email = $request->input('email');
        $student->phone_number = $request->input('phone_number');
        $student->faculty = $request->input('faculty');
        $student->department = $request->input('department');
        $student->save();

        return response()->json(['message' => 'Student updated successfully', 'student' => $student], 200);
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json(['message' => 'Student deleted successfully'], 200);
    }
}

