<?php

namespace Modules\Exams\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $table = 'exam_submissions';

    protected $fillable = [
        'exam_id',
        'student_id',
        'answers',
        'file',
        'marks',
        'grade',
        'remarks',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'submitted_at' => 'datetime',
        'marks' => 'float',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(\Modules\Users\User\App\Models\User::class, 'student_id');
    }
}
