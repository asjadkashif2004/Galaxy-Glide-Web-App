@extends('layouts.app')

@section('title', 'Navigation')

@section('content')
<style>
    :root{
        --bg: #ffffff;
        --bg-soft: #f9fafb;
        --text: #111827;
        --muted: #6b7280;
        --brand: #4f46e5; /* indigo */
        --brand-weak: #eef2ff;
        --border: #e5e7eb;
        --shadow: 0 8px 28px rgba(0,0,0,.06);
        --radius: 14px;
        --radius-sm: 10px;
        --ring: 0 0 0 3px rgba(79,70,229,.18);
    }

    .nav-wrap{
        position: sticky; top:0; z-index:40;
        background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(255,255,255,.86));
        backdrop-filter: saturate(1.1) blur(10px);
        border-bottom: 1px solid var(--border);
        box-shadow: var(--shadow);
    }
    .container{
        max-width: 1120px; margin: 0 auto;
        padding: 0 1rem;
    }
    .bar{
        display:flex; align-items:center; justify-content:space-between;
        height: 66px;
    }

    /* Left cluster */
    .logo-link{
        display:inline-flex; align-items:center; gap:.625rem;
        padding: .42rem .6rem; border-radius: var(--radius-sm);
        transition: background .2s ease, transform .15s ease, box-shadow .2s ease;
        text-decoration:none;
    }
    .logo-link:hover{ background: var(--bg-soft); transform: translateY(-1px) scale(1.02); box-shadow: 0 8px 18px rgba(0,0,0,.04); }
    .logo-svg{ height: 36px; width:auto; color: var(--brand); display:block; filter: drop-shadow(0 2px 6px rgba(79,70,229,.2)); transition: transform .25s ease; }
    .logo-link:hover .logo-svg{ transform: rotate(-6deg) scale(1.06); }

    /* Primary links */
    .nav-links{
        display:none; align-items:center; gap:.25rem; margin-left: 1.75rem;
    }
    @media (min-width:640px){ .nav-links{ display:flex; } }

    .nav-link{
        position:relative; display:inline-flex; align-items:center; gap:.5rem;
        padding: .52rem .78rem; border-radius: 10px;
        font: 500 15px/1.2 ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Apple Color Emoji","Segoe UI Emoji";
        color: var(--muted); text-decoration:none;
        transition: color .2s ease, background .2s ease, transform .15s ease, box-shadow .2s ease;
    }
    .nav-link:hover{ color: var(--text); background: var(--bg-soft); transform: translateY(-1px); }
    .nav-link:focus-visible{ outline: none; box-shadow: var(--ring); }
    .nav-link.active{ color: var(--brand); font-weight:600; }
    .nav-link.active::after{
        content:""; position:absolute; left:10px; right:10px; bottom: -4px;
        height: 2px; background: var(--brand);
        border-radius: 999px; box-shadow: 0 2px 8px rgba(79,70,229,.35);
    }

    /* Icon inside links */
    .icon{
        width: 18px; height: 18px; flex: 0 0 18px; opacity:.9;
        color: currentColor; transition: transform .2s ease, opacity .2s ease;
    }
    .nav-link:hover .icon{ transform: translateY(-1px); opacity:1; }

    /* User dropdown (visual only) */
    .user-btn{ display:none; }
    @media (min-width:640px){
        .user-btn{
            display:inline-flex; align-items:center; gap:.5rem;
            padding: .56rem .8rem; border-radius: var(--radius-sm);
            border: 1px solid var(--border); background: var(--bg);
            color: var(--text); font: 500 14px/1 ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
            box-shadow: var(--shadow);
            transition: background .2s ease, border-color .2s ease, box-shadow .2s ease, transform .15s ease;
            max-width: 14rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .user-btn:hover{ background: #fff; border-color:#d1d5db; box-shadow: 0 12px 26px rgba(2,6,23,.06); transform: translateY(-1px); }
        .chev{ width:16px; height:16px; color: var(--muted); transition: transform .2s ease; }
        .user-btn:hover .chev{ transform: translateY(-1px) }
        .avatar{ width:18px; height:18px; color: var(--brand); }
    }

    /* Hamburger */
    .hamb{
        display:inline-flex; align-items:center; justify-content:center;
        width:42px; height:42px; border-radius: 12px; border: 1px solid transparent;
        color: #6b7280; background: transparent;
        transition: background .2s ease, border-color .2s ease, color .2s ease, transform .15s ease;
    }
    .hamb:hover{ background: var(--bg-soft); color: var(--brand); border-color: var(--border); transform: scale(1.05); }
    @media (min-width:640px){ .hamb{ display:none; } }
    .hamb svg{ width:24px; height:24px; }

    /* Mobile panel (CSS-only toggle) */
    #nav-toggle{ display:none; }
    .panel{
        display:none; border-top: 1px solid var(--border); background: #fff; box-shadow: var(--shadow);
        transform-origin: top; animation: none;
    }
    #nav-toggle:checked ~ .panel{ display:block; animation: slideDown .22s ease-out; }
    @keyframes slideDown{
        from{ opacity:0; transform: translateY(-6px); }
        to{ opacity:1; transform: translateY(0); }
    }

    .m-link, .m-link-danger{
        display:block; margin: .25rem .5rem; padding: .625rem .875rem;
        border-radius: 10px; text-decoration:none; transition: background .2s ease, color .2s ease, transform .15s ease;
        color: var(--text); display:flex; align-items:center; gap:.6rem;
    }
    .m-link:hover{ background: var(--bg-soft); transform: translateX(2px); color: var(--brand); }
    .m-link:focus-visible{ outline:none; box-shadow: var(--ring); }
    .m-link .icon{ width:18px; height:18px; color: currentColor; }

    .m-link-danger{ color:#b91c1c; }
    .m-link-danger:hover{ background:#fef2f2; }

    .panel-meta{ padding: .75rem 1rem; border-top: 1px solid var(--border); }
    .meta-name{ font-weight: 600; color: var(--text); }
    .meta-email{ font-size: 13px; color: var(--muted); }

    /* Utility */
    .hidden-sm{ display:none; }
    @media (min-width:640px){ .hidden-sm{ display:flex; } }

    .badge{
        font-size: 11px; font-weight:600; color: var(--brand);
        background: var(--brand-weak); border-radius: 999px; padding:.15rem .5rem; margin-left:.5rem;
    }
</style>

<nav class="nav-wrap">
    <div class="container">
        <div class="bar">
            <div style="display:flex; align-items:center;">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="logo-link" aria-label="Go to dashboard">
                    <svg class="logo-svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" opacity=".08"/>
                        <path d="M7 13.5 12 6l5 7.5h-3V18h-4v-4.5H7z"/>
                    </svg>
                </a>

                <!-- Primary Links -->
                <div class="nav-links">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <!-- Dashboard icon -->
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        {{ __('Dashboard') }}
                    </a>
                </div>
            </div>

            <!-- Right cluster -->
            <div class="hidden-sm" style="align-items:center; gap:.75rem;">
                <!-- Settings Dropdown trigger (visual only) -->
                <button type="button" class="user-btn" aria-haspopup="menu" aria-expanded="false">
                    <!-- User avatar icon -->
                    <svg class="avatar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.2"></circle>
                        <path d="M5 19.5c1.8-3 4.2-4.5 7-4.5s5.2 1.5 7 4.5"></path>
                    </svg>
                    <span style="overflow:hidden; text-overflow:ellipsis;">{{ Auth::user()->name }}</span>
                    <svg class="chev" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.3 7.3a1 1 0 0 1 1.4 0L10 10.6l3.3-3.3a1 1 0 1 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 0-1.4z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <!-- Hamburger (mobile) -->
            <label for="nav-toggle" class="hamb" aria-label="Toggle menu">
                <svg viewBox="0 0 24 24" stroke="currentColor" fill="none" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </label>
            <input type="checkbox" id="nav-toggle" />
        </div>
    </div>

    <!-- Responsive Panel -->
    <div class="panel">
        <div style="padding-top:.25rem; padding-bottom:.5rem;">
            <a href="{{ route('dashboard') }}" class="m-link">
                <!-- small dashboard icon -->
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                </svg>
                {{ __('Dashboard') }} <span class="badge">Active</span>
            </a>
        </div>

        <div class="panel-meta">
            <div class="meta-name">{{ Auth::user()->name }}</div>
            <div class="meta-email">{{ Auth::user()->email }}</div>
        </div>

        <div style="padding:.5rem 0 1rem;">
            <a href="{{ route('profile.edit') }}" class="m-link">
                <!-- profile icon -->
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="8" r="3.2"></circle>
                    <path d="M5 19.5c1.8-3 4.2-4.5 7-4.5s5.2 1.5 7 4.5"></path>
                </svg>
                {{ __('Profile') }}
            </a>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}" style="margin-top:.25rem;">
                @csrf
                <a href="{{ route('logout') }}" class="m-link m-link-danger"
                   onclick="event.preventDefault(); this.closest('form').submit();">
                    <!-- logout icon -->
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15 3h3a2 2 0 0 1 2 2v3"></path>
                        <path d="M21 9l-7 7"></path>
                        <path d="M8 21H5a2 2 0 0 1-2-2v-3"></path>
                        <path d="M3 15l7-7"></path>
                    </svg>
                    {{ __('Log Out') }}
                </a>
            </form>
        </div>
    </div>
</nav>
@endsection
