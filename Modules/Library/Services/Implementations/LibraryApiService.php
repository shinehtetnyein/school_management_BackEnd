<?php

namespace Modules\Library\Services\Implementations;

use Modules\Library\Services\LibraryApiServiceInterface;
use Modules\Library\Models\Book;

class LibraryApiService implements LibraryApiServiceInterface
{
    public function list(array $filters = [])
    {
        return Book::query()->paginate(15);
    }

    public function create(array $data)
    {
        return Book::create($data);
    }
}
