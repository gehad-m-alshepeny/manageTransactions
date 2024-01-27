<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Services\User\Auth\AuthAPIService;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;

class AuthenticatedAPIController extends Controller
{
    /**
     * Attempt to authenticate the request's credentials.
     *
     * @param  mixed  $_
     * @param  array  $args
     * @return null|\App\Models\User
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $args=['username'=> $validated['email'],'password'=> $validated['password']];

        return (new AuthAPIService)->login($args);
    }

    /**
     * Destroy an authenticated token.
     *
     * @param  mixed  $_
     * @param  array  $args
     */
    public function logout($_, array $args)
    {
        return (new AuthAPIService)->logout($args);
    }

    /**
     * Refresh an authenticated token.
     *
     * @param  mixed  $_
     * @param  array  $args
     */
    public function refresh($_, array $args)
    {
        return (new AuthAPIService)->refreshToken($args);
    }

    /**
     * Attempt to get Client Access Token.
     *
     * @param  mixed  $_
     * @param  array  $args
     * @return null|\App\Models\User
     */
    public function getClientToken($_, array $args)
    {
        return (new AuthAPIService)->getClientToken($args);
    }
}
