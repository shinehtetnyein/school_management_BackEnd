<?php
namespace Modules\ClassRoom\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'classroom_id',
        'status',
    ];

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
