<?php

namespace App\Services\User\Auth;

use App\Services\User\Auth\Factories\AuthModelFactory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


trait BaseAuthResolver
{
    /**
     * @param  array  $args
     * @param  string  $grantType
     * @return mixed
     */
    public function buildCredentials(array $args = [], $grantType = 'password')
    {
       
        $args = collect($args);
        $credentials = $args->except('directive')->toArray();
        $credentials['client_id'] = $args->get('client_id', config('passport.access_client.id'));
        $credentials['client_secret'] = $args->get('client_secret', config('passport.access_client.secret'));
        $credentials['grant_type'] = $grantType;

        return $credentials;
    }

    /**
     * @param  array  $credentials
     * @return mixed
     *
     * @throws Exception
     */
    public function makeRequest(array $credentials)
    { Log::debug('here');
       
        $request = Request::create('oauth/token', 'POST', $credentials, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);
        $response = app()->handle($request);
        Log::debug($response);
        $decodedResponse = json_decode($response->getContent(), true);
       

        if ($response->getStatusCode() !== 200) {
            if ($decodedResponse['message'] === 'The provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client.') {
                throw new Exception(__('Incorrect username or password'));
            }
            throw new Exception(__($decodedResponse['message']));
        }

        $this->revokeOldTokens($decodedResponse);

        return $decodedResponse;
     
    }

    protected function getAuthModelFactory(): AuthModelFactory
    {
        return app(AuthModelFactory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function makeAuthModelInstance()
    {
        return $this->getAuthModelFactory()->make();
    }

    protected function getAuthModelClass(): string
    {
        return $this->getAuthModelFactory()->getClass();
    }

    protected function getTokenPayload($decodedResponse)
    {
        $tokenParts = explode('.', $decodedResponse['access_token']);
        $tokenPayload = base64_decode($tokenParts[1]);

        return json_decode($tokenPayload);
    }

    protected function revokeOldTokens($decodedResponse)
    {
        $jwtPayload = $this->getTokenPayload($decodedResponse);

        DB::table('oauth_access_tokens')
            ->where('user_id', $jwtPayload->sub)
            ->where('id', '!=', $jwtPayload->jti)
            ->update(['revoked' => 1]);
    }
}
