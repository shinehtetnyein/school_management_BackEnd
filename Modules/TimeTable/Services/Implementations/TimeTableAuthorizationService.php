<?php

namespace Modules\TimeTable\Services\Implementations;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\TimeTable\Services\TimeTableAuthorizationServiceInterface;

class TimeTableAuthorizationService implements TimeTableAuthorizationServiceInterface
{
    protected array $allowedRoles = ['root_admin', 'admin', 'teacher'];

    public function authorize(?Authenticatable $user, ?string $action = null): bool
    {
        if (! $user) {
            return false;
        }

        if (method_exists($user, 'hasRole')) {
            return $user->hasRole($this->allowedRoles);
        }

        return false;
    }
}
