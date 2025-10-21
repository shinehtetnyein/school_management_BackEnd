<?php

namespace Modules\AcademicYears\Services;

interface AcademicYearApiServiceInterface
{
    public function list(array $filters = []);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function find(int $id);

    public function getCurrent();

    public function setCurrent(int $id);

    public function getStatistics(int $id);
}
