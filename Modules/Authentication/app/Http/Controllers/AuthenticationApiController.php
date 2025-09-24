<?php

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\Authentication\App\Http\Requests\LoginApiRequest;
use Modules\Authentication\Services\AuthenticationApiServiceInterface;
class AuthenticationApiController extends Controller{
    public function __construct(protected AuthenticationApiServiceInterface $authenticationApiService){}

     public function loginUser(LoginApiRequest $request)
    {
        $data = User::all();
        return apiResponse(true, 'Data fetch successfully', $data);
    }

}
