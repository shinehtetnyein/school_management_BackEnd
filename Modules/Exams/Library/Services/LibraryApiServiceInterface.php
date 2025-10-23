<?php

namespace Modules\Library\Services;

interface LibraryApiServiceInterface
{
    public function list(array $filters = []);

    public function create(array $data);
}
