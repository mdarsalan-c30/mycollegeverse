<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/mcv-admin/login',
        '/mcv-admin/register',
        '/recruiter/login',
        '/recruiter/register',
        '/login',
        '/register',
        '/multiverse-reset.php',
    ];
}
