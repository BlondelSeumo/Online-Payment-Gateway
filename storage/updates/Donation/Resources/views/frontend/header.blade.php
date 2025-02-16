@if(isActive('Donation'))
    <li class="nav-item <?= isset( $menu ) && ( $menu == 'Donation' ) ? 'nav-active': '' ?>">
        <a href="{{ route('donations.home') }}" class="nav-link">{{ __('Campaign') }}</a>
    </li>
@endif