<?php

use Illuminate\Support\Facades\Route;

$currentRoute = Route::currentRouteName();
?>

<div class="sidebar">
    <ul class="sidebar--items">
        <div class="notification--profile">
            <div class="notification--profile">
                <div class="profile">
                </div>
                <div class="admin-name sidebar--item">
                    <h3>{{auth()->user()->name}}</h3>
                </div>
            </div>
        </div>
        <hr>
        <li>
            <a href="{{ route('dashboard') }}" id="{{ $currentRoute === 'dashboard' ? 'active--link' : '' }}">
                <span class="icon"><i class="ri-layout-grid-line"></i></span>
                <span class="sidebar--item">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('statistics') }}" id="{{ $currentRoute === 'statistics' ? 'active--link' : '' }}">
                <span class="icon"><i class="ri-line-chart-line"></i></span>
                <span class="sidebar--item">Statistics</span>
            </a>
        </li>
        <li>
            <a onclick="window.location.href = `{{ route('logout') }}`">
                <span class="icon"><i class="ri-logout-box-r-line"></i></span>
                <span class="sidebar--item" type="submit">Logout</span>
            </a>
        </li>
    </ul>
</div>
