<?php

namespace App\Services;

interface ServiceContract
{
    /**
     * Validate data
     *
     * @param  array  $data
     * @return bool
     *
     * @throws Exception
     */
    public function validate(array $data): bool;

    /**
     * Authorize action
     *
     * @param  array  $ability
     * @param  array  $arguments
     * @return bool
     *
     * @throws \App\Exceptions\PermissionException
     */
    public function authorize(string $ability, array $arguments = []): bool;

    /**
     * Get validation rules
     *
     * @return mixed
     */
    public function rules(): mixed;

    /**
     * Get validation messages
     *
     * @return array
     */
    public function messages(): array;

    /**
     * Get validation attributes
     *
     * @return array
     */
    public function attributes(): array;
}
