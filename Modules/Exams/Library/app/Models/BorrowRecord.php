<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    protected $table = 'borrow_records';

    protected $fillable = ['user_id', 'book_id', 'borrowed_at', 'due_at', 'returned_at', 'status'];
}
