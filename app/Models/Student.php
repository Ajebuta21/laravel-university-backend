<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'reg_number',
        'date_of_birth',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'faculty',
        'department',
    ];

    public function results()
    {
        return $this->hasMany(Result::class, 'reg_number', 'reg_number');
    }

}
