<?php

namespace Modules\Library\Services\Implementations;

use Modules\Library\Services\LibraryApiServiceInterface;
use Modules\Library\Models\Book;

class LibraryApiService implements LibraryApiServiceInterface
{
    public function list(array $filters = [])
    {
        $books = Book::query()->orderBy('id', 'asc')->get()->map(function($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'created_at' => $book->created_at,
                'updated_at' => $book->updated_at,
            ];
        })->toArray();

        return [
            'total_count' => count($books),
            'books' => $books
        ];
    }

    public function create(array $data)
    {
        return Book::create($data);
    }
}
