<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function storeCourse(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|unique:courses',
            'course_name' => 'required|string',
            'credit_load' => 'required|number',
            'department' => 'required|string',
        ]);

        $course = new Course([
            'course_code' => $request->input('course_code'),
            'course_name' => $request->input('course_name'),
            'credit_load' => $request->input('credit_load'),
            'department' => $request->input('department'),
        ]);
        $course->save();

        return response()->json(['message' => 'Course created successfully', 'course' => $course], 201);
    }
}
