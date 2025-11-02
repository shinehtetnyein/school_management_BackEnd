<?php

namespace Modules\Users\User\Services;

interface UserApiServiceInterface
{
    public function get($id, array $relations = []);

    public function getAll(
        array $relations = [],
        ?int $limit = null,
        ?int $offset = null,
        ?bool $noPagination = false,
        ?int $pagPerPage = null
    );

    public function create(array $userData);

    public function update($id, array $userData);

    public function delete($id);
}
