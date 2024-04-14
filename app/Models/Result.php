<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'reg_number',
        'semester',
        'course_id',
        'score',
        'grade',
        'year',
        'credit_load'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_number', 'reg_number');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
