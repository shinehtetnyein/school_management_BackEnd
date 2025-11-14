<?php

namespace App\Models;

// Alias for the Users module User model
use Modules\Users\User\App\Models\User as ModuleUser;

class User extends ModuleUser
{
    // This class serves as an alias to the Users module User model
    // for backwards compatibility and convenience
}
