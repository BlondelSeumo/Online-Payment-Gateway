@if (
    isActive('Donation') 
    && (
        Common::has_permission(auth('admin')->user()->id, 'view_campaign') || Common::has_permission(auth('admin')->user()->id, 'view_campaign_payment')||  Common::has_permission(auth('admin')->user()->id, 'view_campaign_setting')
    )
)

    <li <?= isset($menu) && $menu == 'donation' ? ' class="active treeview"' : 'treeview'?> >
        <a href="#">
            <i class="fa fa-dollar"></i><span>{{ __('Donation') }}</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <!-- Campaign -->
            @if(isActive('Donation') && Common::has_permission(\Auth::guard('admin')->user()->id, 'view_campaign'))
                <li <?= isset($sub_menu) && $sub_menu == 'donations' ? ' class="active"' : ''?> >
                    <a href="{{ route('admin.donation.index') }}"><i class="fa fa-stop-circle-o"></i><span>{{ __('Campaigns') }}</span></a>
                </li>
            @endif

            <!-- Campaign Payments -->
            @if(Common::has_permission(\Auth::guard('admin')->user()->id, 'view_campaign_payment'))
                <li <?= isset($sub_menu) && $sub_menu == 'donation_payments' ? ' class="active"' : ''?> >
                    <a href="{{ route('admin.donation-payment.index') }}"><i class="fa fa-credit-card" aria-hidden="true"></i><span>{{ __('Payments') }}</span></a>
                </li>
            @endif

            <!-- Campaign Settings -->
            @if(Common::has_permission(\Auth::guard('admin')->user()->id, 'view_campaign_setting'))
                <li <?= isset($sub_menu) && $sub_menu == 'donation_preference' ? ' class="active"' : ''?> >
                    <a href="{{ route('admin.donation.preferences') }}"><i class="fa fa-cogs" aria-hidden="true"></i><span>{{ __('Settings') }}</span></a>
                </li>
            @endif
        </ul>
    </li>

@endif
