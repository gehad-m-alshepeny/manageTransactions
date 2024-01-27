<?php

namespace App\Services\User\Auth;

use App\Services\Service;
use App\Http\Traits\UserLoginTrait;
use Defuse\Crypto\Crypto;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthAPIService extends Service
{
    use BaseAuthResolver, UserLoginTrait;

    /**
     * login rules
     *
     * @return mixed
     */
    public function rules(): mixed
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * refresh rules
     *
     * @return mixed
     */
    public function refreshRules(): mixed
    {
        return [
            'refresh_token' => ['required', 'string'],
        ];
    }

    /**
     * getClientToken rules
     *
     */
    public function getClientTokenRules(): mixed
    {
        return [
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
        ];
    }

    /**
     * Api login
     *
     * @param  array  $data
     *
     * @throws \Illuminate\Validation\Exception
     */
    public function login(array $data): array
    {
        try {
            $this->validate($data, $this->rules());
            $this->ensureIsNotRateLimited();

            $credentials = $this->buildCredentials($data);
         
            $response = [];
            if (config('app.env') !== 'testing') {
                $response = $this->makeRequest($credentials);
            }
            
            $user = $this->findUser($data['username']);

            $this->validateUser($user);

            return array_merge(
                $response,
                [
                    'user' => $user,
                ]
                );
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 1);
        }
    }

    private function validateUser($user)
    {
        $authModelClass = $this->getAuthModelClass();
        if ($user instanceof $authModelClass && $user->exists ) {
            return;
        }
        throw (new ModelNotFoundException())
            ->setModel($authModelClass);
    }

    private function findUser(string $email)
    {
        $model = $this->makeAuthModelInstance();

        if (method_exists($model, 'findForPassport')) {
            return $model->findForPassport($email);
        }

        return $model::query()
            ->where('email', $email)
            ->first();
    }

    /**
     * Api logout
     *
     * @param  array  $data
     *
     * @throws \Illuminate\Validation\Exception
     */
    public function logout(array $data): array
    {
        try {
            $this->ensureIsNotRateLimited();
            if (! Auth::guard('api')->check()) {
                throw new Exception('Not Authenticated', 'Not Authenticated');
            }

            // revoke user's token
            Auth::guard('api')->user()->token()->revoke();

            return [
                'status' => 'TOKEN_REVOKED',
                'message' => __('Your session has been terminated'),
            ];
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 1);
        }
    }

    /**
     * Api refresh token
     *
     * @param  array  $data
     *
     * @throws \Illuminate\Validation\Exception
     */
    public function refreshToken(array $data): array
    {
        try {
            $this->validate($data, $this->refreshRules());
            $this->ensureIsNotRateLimited();

            if (! Auth::guard('api')->check()) {
                throw new Exception('Not Authenticated', 'Not Authenticated');
            }

            $credentials = $this->buildCredentials($data, 'refresh_token');

            $response = $this->makeRequest($credentials);

            // let's get the user id from the new Access token so we can emit an event
            $refreshTokenParsed = $this->parseRefreshToken($data);
            $user = User::findOrFail($refreshTokenParsed['user_id']);

            $model = $this->makeAuthModelInstance();

            $user = $model->findOrFail($refreshTokenParsed['user_id']);

            return $response;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 1);
        }
    }

    /**
     * @param $args
     * @return false|mixed
     */
    public function parseRefreshToken($args)
    {
        $refresh_token = $args['refresh_token'];
        $app_key = config('app.key');
        $enc_key = base64_decode(substr($app_key, 7));
        $crypto = Crypto::decryptWithPassword($refresh_token, $enc_key);

        return json_decode($crypto, true);
    }

    /**
     * Api getClientToken
     *
     * @param  array  $data
     *
     * @throws \Illuminate\Validation\Exception
     */
    public function getClientToken(array $data)
    { 
        try {
            $this->validate($data, $this->getClientTokenRules());
            $this->ensureIsNotRateLimited();

            $credentials = $this->buildCredentials($data, 'client_credentials');
            $response = [];
            if (config('app.env') !== 'testing') {
                $response = $this->makeRequest($credentials);
            }

            return $response;

        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 1);
        }
    }
}
