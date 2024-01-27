<?php

namespace App\Services;

use App\Exceptions\PermissionException;
use App\Services\ServiceContract;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\UnauthorizedException as Unauthorized;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

abstract class Service implements ServiceContract
{
  
    /**
     * Get validation rules
     *
     * @return mixed
     */
    public function rules(): mixed
    {
        return [];
    }

    /**
     * Get validation messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get validation attributes
     *
     * @return array
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * Authorize action
     *
     * @param  array  $ability
     * @param  mixed|array  $arguments
     * @return bool
     *
     * @throws \App\Exceptions\PermissionException
     */
    public function authorize(string $ability, $arguments = []): bool
    {
        [$ability, $arguments] = $this->parseAbilityAndArguments($ability, $arguments);

        if (! auth()->check()) {
            throw new Unauthorized('You are not logged in.');
        }

        if (! auth()->user()->hasPermissionTo($ability)) {
            throw new PermissionException($ability);
        }

        return true;
    }

    /**
     * Guesses the ability's name if it wasn't provided.
     *
     * @param  mixed  $ability
     * @param  mixed|array  $arguments
     * @return array
     */
    protected function parseAbilityAndArguments($ability, $arguments)
    {
        if (is_string($ability) && ! str_contains($ability, '\\')) {
            return [$ability, $arguments];
        }

        $method = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2]['function'];

        return [$this->normalizeGuessedAbilityName($method), $ability];
    }

    /**
     * Check user is logged in.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    public function checkUserLogged()
    {
        if (! auth()->check()) {
            throw UnauthorizedException::notLoggedIn();
        }
    }


    /**
     * Validate data
     *
     * @param  array  $data
     * @param  array  $rules
     * @return bool
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(array $data, mixed $rules = null): bool
    {
        if (is_null($rules)) {
            $rules = $this->rules();
        }

        $validator = Validator::make(
            data: $data,
            rules:$rules,
            messages:$this->messages(),
            // customAttributes:$this->attributes() L10 update
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        return true;
    }

}
