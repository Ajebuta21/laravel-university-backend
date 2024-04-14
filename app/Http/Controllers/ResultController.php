<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reg_number' => 'required|string',
            'semester' => 'required|in:first,second',
            'course_id' => 'required|exists:courses,id',
            'score' => 'required|integer|min:0|max:100',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $course = Course::findOrFail($request->input('course_id'));
        $creditLoad = $course->credit_load;

        $grade = $this->calculateGrade($request->input('score'));

        $result = new Result([
            'reg_number' => $request->input('reg_number'),
            'semester' => $request->input('semester'),
            'course_id' => $request->input('course_id'),
            'score' => $request->input('score'),
            'grade' => $grade,
            'year' => $request->input('year'),
            'credit_load' => $creditLoad,
        ]);
        $result->save();

        return response()->json(['message' => 'Result created successfully', 'result' => $result], 201);
    }


    public function update(Request $request, $regNumber, $courseId, $year)
    {
        $result = Result::where('reg_number', $regNumber)
            ->where('course_id', $courseId)
            ->where('year', $year)
            ->firstOrFail();

        $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        $grade = $this->calculateGrade($request->input('score'));

        $result->score = $request->input('score');
        $result->grade = $grade;
        $result->save();

        return response()->json(['message' => 'Result updated successfully', 'result' => $result], 200);
    }

    public function getStudentTotalResult($regNumber, $year, $semester)
    {
        $studentResults = Result::with('course')
            ->where('reg_number', $regNumber)
            ->where('year', $year)
            ->where('semester', $semester)
            ->get();

        $totalScore = 0;
        $totalCreditLoad = 0;

        foreach ($studentResults as $result) {
            $totalScore += $result->credit_load * $this->gradeToGradePoint($result->grade);
            $totalCreditLoad += $result->credit_load;
        }

        $gpa = $totalCreditLoad > 0 ? round($totalScore / $totalCreditLoad, 2) : 0;

        return response()->json([
            'reg_number' => $regNumber,
            'year' => $year,
            'semester' => $semester,
            'results' => $studentResults,
            'total_credit_load' => $totalCreditLoad,
            'gpa' => $gpa,
        ], 200);
    }

    public function getStudentOverallGPA($regNumber)
    {
        $studentResults = Result::with('course')
            ->where('reg_number', $regNumber)
            ->get();

        $totalScore = 0;
        $totalCreditLoad = 0;

        foreach ($studentResults as $result) {
            $totalScore += $result->credit_load * $this->gradeToGradePoint($result->grade);
            $totalCreditLoad += $result->credit_load;
        }

        $gpa = $totalCreditLoad > 0 ? round($totalScore / $totalCreditLoad, 2) : 0;

        return response()->json([
            'reg_number' => $regNumber,
            'overall_gpa' => $gpa,
        ], 200);
    }

    private function calculateGrade($score)
    {
        if ($score >= 70) {
            return 'A';
        } elseif ($score >= 60) {
            return 'B';
        } elseif ($score >= 50) {
            return 'C';
        } elseif ($score >= 45) {
            return 'D';
        } else {
            return 'F';
        }
    }

    private function gradeToGradePoint($grade)
    {
        switch ($grade) {
            case 'A':
                return 4.0;
            case 'B':
                return 3.0;
            case 'C':
                return 2.0;
            case 'D':
                return 1.0;
            default:
                return 0.0;
        }
    }
}
