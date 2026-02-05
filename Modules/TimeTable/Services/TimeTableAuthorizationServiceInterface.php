<?php

namespace Modules\TimeTable\Services;

use Illuminate\Contracts\Auth\Authenticatable;

interface TimeTableAuthorizationServiceInterface
{
    /**
     * Determine if the given user is authorized to perform an action on timetables.
     *
     * @param Authenticatable|null $user
     * @param string|null $action Optional action name (create, update, delete...)
     * @return bool
     */
    public function authorize(?Authenticatable $user, ?string $action = null): bool;
}
