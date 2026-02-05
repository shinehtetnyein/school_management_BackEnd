<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'grade', 'room_number', 'capacity', 'sections'
    ];

    protected $casts = [
        'sections' => 'array'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
