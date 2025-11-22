<?php
namespace Modules\ClassRoom\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Users\User\App\Models\User;
use Modules\TimeTable\app\Models\TimeTable;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'classroom_id',
        'status',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    /**
     * Students assigned to this section
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'section_student', 'section_id', 'user_id')->withTimestamps();
    }

    /**
     * Timetable entries for this section
     */
    public function timetables(): HasMany
    {
        return $this->hasMany(TimeTable::class, 'section_id');
    }
}
