<?php

namespace Modules\Authentication\Services;

interface AuthenticationApiServiceInterface {
    public function register(array $data);
    public function login(array $credentials);
    public function logout($user);
}
