<?php

namespace LoafPanel\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Prologue\Alerts\AlertsMessageBag;
use LoafPanel\Exceptions\Http\TwoFactorAuthRequiredException;

class RequireTwoFactorAuthentication
{
    public const LEVEL_NONE = 0;
    public const LEVEL_ADMIN = 1;
    public const LEVEL_ALL = 2;

    /**
     * The route to redirect a user to enable 2FA.
     */
    protected string $redirectRoute = '/account';

    /**
     * RequireTwoFactorAuthentication constructor.
     */
    public function __construct(private AlertsMessageBag $alert)
    {
    }

    /**
     * Check the user state on the incoming request to determine if they should be allowed to
     * proceed or not. This checks if the Panel is configured to require 2FA on an account in
     * order to perform actions. If so, we check the level at which it is required (all users
     * or just admins) and then check if the user has enabled it for their account.
     *
     * @throws TwoFactorAuthRequiredException
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        /** @var \LoafPanel\Models\User $user */
        $user = $request->user();
        $uri = rtrim($request->getRequestUri(), '/') . '/';
        $current = $request->route()->getName();

        if (!$user || Str::startsWith($uri, ['/auth/']) || Str::startsWith($current, ['auth.', 'account.'])) {
            return $next($request);
        }

        $level = (int) config('pterodactyl.auth.2fa_required');
        // If this setting is not configured, or the user is already using 2FA then we can just
        // send them right through, nothing else needs to be checked.
        //
        // If the level is set as admin and the user is not an admin, pass them through as well.
        if ($level === self::LEVEL_NONE || $user->use_totp) {
            return $next($request);
        } elseif ($level === self::LEVEL_ADMIN && !$user->root_admin) {
            return $next($request);
        }

        // For API calls return an exception which gets rendered nicely in the API response.
        if ($request->isJson() || Str::startsWith($uri, '/api/')) {
            throw new TwoFactorAuthRequiredException();
        }

        $this->alert->danger(trans('auth.2fa_must_be_enabled'))->flash();

        return redirect()->to($this->redirectRoute);
    }
}
