<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Role\Permission;

class PermissionException extends AuthorizationException
{
    /**
     * Create a new authorization exception instance.
     *
     * @param  string|null  $message
     * @param  mixed  $code
     * @param  \Throwable|null  $previous
     * @return void
     */
    public function __construct($abilities = null, $message = null, $code = null, Throwable $previous = null)
    {
        $abilityIds = collect(is_array($abilities) ? $abilities : [$abilities])
            ->flatten()
            ->map(function ($ability) {
                return Permission::where('name', $ability)->first()?->id;
            })
            ->filter();
        if (is_null($message) && count($abilityIds) > 0) {
            $message = "You don't have the right permissions";
        }

        parent::__construct($message ?? 'This action is unauthorized.', 0, $previous);

        $this->code = $code ?: 403;
    }
}
