<?php

use App\Http\Controllers\Controller;
use Modules\Authentication\Services\AuthenticationApiServiceInterface;
class AuthenticationApiController extends Controller{
    public function __construct(protected AuthenticationApiServiceInterface $authenticationApiService){}

    

}
