<?php

namespace Modules\Results\Services;

interface ResultApiServiceInterface
{
    public function getAllResults();
    public function createResult(array $data);
    public function getResultById($id);
    public function updateResult($id, array $data);
    public function deleteResult($id);

    public function list(array $filters = []);

    public function find(int $id);
}
