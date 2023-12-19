<?php
use Illuminate\Support\Facades\Route;

$currentRoute = Route::currentRouteName();
?>

<section class="header">
    <div class="logo">
        <i class="ri-menu-line icon icon-0 menu"></i>
        <img src="{{ asset('images/passifi-logo.png') }}" alt="" class="passifi-logo">
    </div>
    @if($currentRoute === 'dashboard')
    <div class="search--notification--profile">
        <div class="search">
            <input type="text" placeholder="Search Event">
            <button><i class="ri-search-2-line"></i></button>
        </div>
    </div>
    @endif
</section>
