<?php

namespace Modules\Results\Services;

interface ResultApiServiceInterface
{
    // Standardized CRUD methods
    public function list(array $filters = []);

    public function create(array $data);

    public function show(int $id);

    public function update(int $id, array $data);

    public function delete(int $id);

    // Backwards-compatible aliases
    public function find(int $id);
}
