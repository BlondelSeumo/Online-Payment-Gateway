<?php

namespace Modules\Donation\Http\Middleware;

use App\Http\Helpers\Common;
use Closure;
use Illuminate\Http\Request;

class DonationPermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Middleware logic here
        if (Common::has_permission(auth()->id(), 'manage_campaign') && (
            preference('donation_available_for') == 'both' ||
            (auth()->user()->role_id == 3 && preference('donation_available_for') == 'merchant') ||
            (auth()->user()->role_id == 2 && preference('donation_available_for') == 'user')
        )) {
            return $next($request);
        }
        Common::one_time_message('danger', __('You do not have permission to access this resources.'));
        return redirect(route('user.dashboard'));
    }
}
