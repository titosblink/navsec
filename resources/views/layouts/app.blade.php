<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NN Personnel') — Nigerian Navy Personnel Management</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 50:'#eef2ff', 100:'#e0e7ff', 200:'#c7d2fe', 500:'#6366f1', 600:'#0d1b4b', 700:'#0a1640', 800:'#071230', 900:'#040d21' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; }

        /* ── Sidebar nav links ── */
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px; border-radius: 8px; margin: 1px 0;
            color: #94a3b8; font-size: 0.875rem; font-weight: 500;
            text-decoration: none; transition: all .18s ease;
            position: relative; border-left: 3px solid transparent;
            white-space: nowrap; overflow: hidden;
        }
        .nav-link:hover { background: rgba(255,255,255,0.07); color: #e2e8f0; border-left-color: rgba(255,215,0,0.4); }
        .nav-link.active { background: rgba(255,215,0,0.12); color: #ffd700; font-weight: 600; border-left-color: #ffd700; }
        .nav-link .nav-icon {
            width: 32px; height: 32px; border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; flex-shrink: 0;
            background: rgba(255,255,255,0.06); transition: background .18s;
        }
        .nav-link:hover .nav-icon  { background: rgba(255,255,255,0.12); }
        .nav-link.active .nav-icon { background: rgba(255,215,0,0.18); color: #ffd700; }

        .nav-section {
            padding: 16px 16px 5px;
            font-size: 0.68rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: #475569;
            display: flex; align-items: center; gap: 8px;
            white-space: nowrap; overflow: hidden;
        }
        .nav-section::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.06); }

        /* Sidebar label fade when collapsed */
        .nav-label { transition: opacity .2s, width .2s; }

        .badge-rank { display:inline-block; padding:2px 8px; border-radius:4px; font-size:.75rem; font-weight:700; }
        .select2-container .select2-selection--single { height: 38px !important; border-color: #d1d5db !important; border-radius: 6px !important; }
        .select2-container .select2-selection--single .select2-selection__rendered { line-height: 36px !important; padding-left: 10px !important; }
        .select2-container .select2-selection--single .select2-selection__arrow { height: 36px !important; }
        @media print { .no-print { display: none !important; } }

        /* ── Mobile sidebar hidden ── */
        @media (max-width: 767px) {
            .sidebar-mobile-hidden { transform: translateX(-100%); }
        }

        /* ── Toast notifications ── */
        #toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:10px; pointer-events:none; }
        .toast {
            display:flex; align-items:flex-start; gap:12px;
            background:#fff; border-radius:12px; padding:14px 16px;
            box-shadow:0 8px 32px rgba(0,0,0,.14), 0 2px 8px rgba(0,0,0,.08);
            min-width:300px; max-width:380px; pointer-events:all;
            border-left:4px solid #22c55e; position:relative; overflow:hidden;
            animation: toastIn .3s cubic-bezier(.34,1.56,.64,1) both;
        }
        .toast.toast-error   { border-left-color:#ef4444; }
        .toast.toast-warning { border-left-color:#f59e0b; }
        .toast.toast-info    { border-left-color:#3b82f6; }
        .toast.toast-out     { animation: toastOut .3s ease forwards; }
        .toast-icon { width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; flex-shrink:0; margin-top:1px; }
        .toast-success .toast-icon { background:#dcfce7; color:#16a34a; }
        .toast-error   .toast-icon { background:#fee2e2; color:#dc2626; }
        .toast-warning .toast-icon { background:#fef3c7; color:#d97706; }
        .toast-info    .toast-icon { background:#dbeafe; color:#2563eb; }
        .toast-body { flex:1; }
        .toast-title  { font-size:.8rem; font-weight:700; color:#111827; }
        .toast-msg    { font-size:.78rem; color:#6b7280; margin-top:2px; line-height:1.4; }
        .toast-close  { background:none; border:none; cursor:pointer; color:#9ca3af; font-size:14px; padding:0; flex-shrink:0; }
        .toast-close:hover { color:#374151; }
        .toast-progress { position:absolute; bottom:0; left:0; height:3px; background:currentColor; opacity:.25; border-radius:0 0 0 8px; animation: toastProgress var(--duration, 4s) linear forwards; }
        @keyframes toastIn  { from { opacity:0; transform:translateX(60px) scale(.95); } to { opacity:1; transform:translateX(0) scale(1); } }
        @keyframes toastOut { to   { opacity:0; transform:translateX(60px) scale(.95); } }
        @keyframes toastProgress { from { width:100%; } to { width:0%; } }

        /* ── Inline edit ── */
        .inline-edit-field { cursor:text; border-bottom:1px dashed transparent; transition:border-color .15s; }
        .inline-edit-field:hover { border-bottom-color:#6b7280; }
        .inline-edit-field .edit-trigger { opacity:0; transition:opacity .15s; font-size:.65rem; }
        .inline-edit-field:hover .edit-trigger { opacity:1; }

        /* ── Sticky table head offset ── */
        .sticky-thead th { position:sticky; top:57px; z-index:5; background:#f9fafb; }

        /* ── Global search overlay ── */
        #gsearch-overlay { display:none; }
        #gsearch-overlay.open { display:flex; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen"
      x-data="{
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        toggleCollapse() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
        }
      }">

{{-- Mobile backdrop --}}
<div x-show="sidebarOpen" @click="sidebarOpen=false" x-cloak
     class="fixed inset-0 bg-black/60 z-20 md:hidden" style="display:none"></div>

<div class="flex min-h-screen">

{{-- ── SIDEBAR ── --}}
<aside id="sidebar"
       class="fixed md:sticky top-0 h-full md:h-screen flex-shrink-0 z-30 md:z-auto flex flex-col transition-all duration-300 no-print overflow-y-auto overflow-x-hidden md:translate-x-0"
       :class="{
           'w-64': !sidebarCollapsed,
           'w-16': sidebarCollapsed,
           'sidebar-mobile-hidden': !sidebarOpen
       }"
       style="background: linear-gradient(180deg, #0b1d55 0%, #071230 100%); border-right: 1px solid rgba(255,255,255,0.05);">

    {{-- Brand / Logo --}}
    <div style="border-bottom: 1px solid rgba(255,255,255,0.08); flex-shrink:0;">
        <div style="height:3px; background: linear-gradient(90deg,#ffd700,#e8a000,#ffd700);"></div>
        <div class="flex items-center gap-3 px-3 py-4 overflow-hidden">
            <div style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <img src="{{ asset('navylogo.png') }}" alt="Logo" width="50" height="50" class="mx-auto mb-3">
            </div>
            <div x-show="!sidebarCollapsed" x-cloak class="nav-label overflow-hidden">
                <div style="color:#fff;font-weight:700;font-size:.82rem;letter-spacing:.06em;line-height:1.2;white-space:nowrap;">NIGERIAN NAVY</div>
                <div style="color:#94a3b8;font-size:.7rem;letter-spacing:.03em;white-space:nowrap;">Personnel Mgt System</div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-2 py-3" style="scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.1) transparent;">

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
            <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Dashboard</span>
        </a>

        <div x-show="!sidebarCollapsed" x-cloak class="nav-section">Personnel</div>
        <div x-show="sidebarCollapsed" class="my-2 mx-3 h-px" style="background:rgba(255,255,255,0.06);"></div>

        <a href="{{ route('officers.index') }}" class="nav-link {{ request()->routeIs('officers.index') || request()->routeIs('officers.show') ? 'active' : '' }}" title="All Officers">
            <span class="nav-icon"><i class="fas fa-user-tie"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">All Officers</span>
        </a>

        @if(in_array(auth()->user()->role, ['admin','hr']))
        <a href="{{ route('officers.create') }}" class="nav-link {{ request()->routeIs('officers.create') || request()->routeIs('officers.edit') ? 'active' : '' }}" title="Add New Officer">
            <span class="nav-icon"><i class="fas fa-user-plus"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Add New Officer</span>
        </a>
        @endif

        <div x-show="!sidebarCollapsed" x-cloak class="nav-section">Operations</div>
        <div x-show="sidebarCollapsed" class="my-2 mx-3 h-px" style="background:rgba(255,255,255,0.06);"></div>

        <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" title="Appointments">
            <span class="nav-icon"><i class="fas fa-right-left"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Appointments</span>
        </a>
        <a href="{{ route('courses.index') }}" class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" title="Courses">
            <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Courses</span>
        </a>
        <a href="{{ route('leaves.index') }}" class="nav-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}" title="Leave Management">
            <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Leave Management</span>
        </a>
        <a href="{{ route('ships.index') }}" class="nav-link {{ request()->routeIs('ships.*') ? 'active' : '' }}" title="Ships & Commands">
            <span class="nav-icon"><i class="fas fa-ship"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Ships &amp; Commands</span>
        </a>

        <div x-show="!sidebarCollapsed" x-cloak class="nav-section">Reports</div>
        <div x-show="sidebarCollapsed" class="my-2 mx-3 h-px" style="background:rgba(255,255,255,0.06);"></div>

        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" title="Reports">
            <span class="nav-icon"><i class="fas fa-file-chart-column"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Reports</span>
        </a>
        <a href="{{ route('reports.seniority') }}" class="nav-link {{ request()->routeIs('reports.seniority') ? 'active' : '' }}" title="Seniority List">
            <span class="nav-icon"><i class="fas fa-list-ol"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Seniority List</span>
        </a>
        <a href="{{ route('reports.statistics') }}" class="nav-link {{ request()->routeIs('reports.statistics') ? 'active' : '' }}" title="Statistics">
            <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Statistics</span>
        </a>
        <a href="{{ route('reports.promotion-eligibility') }}" class="nav-link {{ request()->routeIs('reports.promotion-eligibility') ? 'active' : '' }}" title="Promotion Eligibility">
            <span class="nav-icon"><i class="fas fa-arrow-up-right-dots"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Promotion Eligibility</span>
        </a>
        <a href="{{ route('officers.compare') }}" class="nav-link {{ request()->routeIs('officers.compare') ? 'active' : '' }}" title="Compare Officers">
            <span class="nav-icon"><i class="fas fa-code-compare"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Compare Officers</span>
        </a>

        @if(auth()->user()->role === 'admin')
        <div x-show="!sidebarCollapsed" x-cloak class="nav-section">Administration</div>
        <div x-show="sidebarCollapsed" class="my-2 mx-3 h-px" style="background:rgba(255,255,255,0.06);"></div>

        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" title="Manage Users">
            <span class="nav-icon"><i class="fas fa-users-gear"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Manage Users</span>
        </a>
        <a href="{{ route('audit.index') }}" class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}" title="Audit Trail">
            <span class="nav-icon"><i class="fas fa-history"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Audit Trail</span>
        </a>
        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" title="Settings">
            <span class="nav-icon"><i class="fas fa-gear"></i></span>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Settings</span>
        </a>
        @endif

        {{-- Recently Viewed --}}
        @php $recentViewed = session('recent_officers', []); @endphp
        @if(count($recentViewed))
        <div x-show="!sidebarCollapsed" x-cloak class="nav-section">Recent</div>
        <div x-show="!sidebarCollapsed" x-cloak>
            @foreach($recentViewed as $rv)
            <a href="{{ route('officers.show', $rv['id']) }}"
               class="nav-link py-1.5" title="{{ $rv['rank'] }} {{ $rv['name'] }}">
                <span class="nav-icon" style="width:26px;height:26px;font-size:10px;font-weight:700;">
                    {{ strtoupper(substr($rv['name'], 0, 1)) }}
                </span>
                <span class="nav-label truncate text-xs">{{ $rv['rank'] }} {{ $rv['name'] }}</span>
            </a>
            @endforeach
        </div>
        @endif

    </nav>

    {{-- User Profile Footer --}}
    <div style="border-top:1px solid rgba(255,255,255,0.08); padding:10px; flex-shrink:0;">
        {{-- Collapse toggle --}}
        <button @click="toggleCollapse()"
                class="w-full flex items-center justify-center gap-2 mb-2 py-1.5 rounded-lg text-gray-500 hover:text-gray-300 hover:bg-white/5 transition-colors text-xs"
                :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
            <i class="fas" :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
            <span x-show="!sidebarCollapsed" x-cloak class="nav-label">Collapse</span>
        </button>

        <div x-show="!sidebarCollapsed" x-cloak
             style="background:rgba(255,255,255,0.05);border-radius:10px;padding:10px 12px;display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:700;flex-shrink:0;border:1.5px solid rgba(255,255,255,0.2);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div style="flex:1;min-width:0;">
                <p style="color:#e2e8f0;font-size:.8rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</p>
                <p style="font-size:.68rem;margin-top:1px;">
                    @php $roleColors = ['admin'=>'#f87171','hr'=>'#fbbf24','viewer'=>'#6ee7b7']; @endphp
                    <span style="color:{{ $roleColors[auth()->user()->role] ?? '#94a3b8' }};font-weight:600;text-transform:capitalize;">{{ auth()->user()->role }}</span>
                </p>
            </div>
            <a href="{{ route('profile.password') }}" title="Change Password"
               style="width:28px;height:28px;border-radius:7px;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;transition:all .18s;"
               onmouseover="this.style.background='rgba(255,215,0,0.15)';this.style.color='#ffd700';"
               onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='#94a3b8';">
                <i class="fas fa-key" style="font-size:11px;"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Sign out"
                        style="width:28px;height:28px;border-radius:7px;background:rgba(255,255,255,0.06);border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;justify-content:center;transition:all .18s;"
                        onmouseover="this.style.background='rgba(239,68,68,0.2)';this.style.color='#f87171';"
                        onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='#94a3b8';">
                    <i class="fas fa-arrow-right-from-bracket" style="font-size:12px;"></i>
                </button>
            </form>
        </div>

        {{-- Collapsed: just icons --}}
        <div x-show="sidebarCollapsed" class="flex flex-col items-center gap-1">
            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:700;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <a href="{{ route('profile.password') }}" title="Change Password" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-500 hover:text-yellow-400 transition-colors">
                <i class="fas fa-key" style="font-size:11px;"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Sign out" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-500 hover:text-red-400 transition-colors" style="background:none;border:none;cursor:pointer;">
                    <i class="fas fa-arrow-right-from-bracket" style="font-size:12px;"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ── MAIN CONTENT ── --}}
<div class="flex-1 flex flex-col min-w-0">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 flex items-center justify-between no-print sticky top-0 z-10">
        <div class="flex items-center gap-3">
            {{-- Mobile hamburger --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">
                <i class="fas fa-bars text-sm"></i>
            </button>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                <p class="text-xs text-gray-500">@yield('breadcrumb')</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2 md:gap-3">
            {{-- Global search trigger --}}
            <button onclick="openGlobalSearch()"
                    class="hidden md:flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-400 hover:border-gray-300 hover:text-gray-600 transition-colors bg-gray-50"
                    title="Search officers (Ctrl+K)">
                <i class="fas fa-search text-xs"></i>
                <span class="text-xs">Search… <kbd class="ml-1 text-xs bg-white border border-gray-200 rounded px-1">Ctrl K</kbd></span>
            </button>
            <button onclick="openGlobalSearch()" class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">
                <i class="fas fa-search text-sm"></i>
            </button>

            @yield('header-actions')

            {{-- ── Notification Bell ── --}}
            @php
                $notifications = \App\Http\Controllers\NotificationController::getNotifications();
                $notifCount    = count($notifications);
            @endphp
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false"
                        class="relative w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                    <i class="fas fa-bell text-sm"></i>
                    @if($notifCount > 0)
                    <span class="absolute -top-1 -right-1 w-4.5 h-4.5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center"
                          style="width:18px;height:18px;font-size:10px;">{{ $notifCount }}</span>
                    @endif
                </button>
                <div x-show="open" x-cloak x-transition
                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50"
                     style="display:none">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <span class="font-semibold text-gray-800 text-sm">Notifications</span>
                        @if($notifCount > 0)
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $notifCount }} new</span>
                        @endif
                    </div>
                    @if($notifCount === 0)
                    <div class="px-4 py-8 text-center">
                        <i class="fas fa-check-circle text-green-400 text-2xl mb-2 block"></i>
                        <p class="text-sm text-gray-500">All clear — no pending actions</p>
                    </div>
                    @else
                    <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                        @foreach($notifications as $n)
                        <a href="{{ $n['url'] }}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas {{ $n['icon'] }} text-sm {{ $n['color'] }}"></i>
                            </div>
                            <p class="text-sm text-gray-700 leading-snug">{{ $n['title'] }}</p>
                        </a>
                        @endforeach
                    </div>
                    @endif
                    <div class="border-t border-gray-100 px-4 py-2">
                        <a href="{{ route('dashboard') }}" class="text-xs text-blue-600 hover:underline">View dashboard →</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 p-4 md:p-6">
        @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <p class="font-medium flex items-center gap-2"><i class="fas fa-exclamation-triangle text-red-500"></i> Please fix the following errors:</p>
            <ul class="mt-1 list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif
        @yield('content')
    </main>
</div>

</div>{{-- end flex --}}

{{-- Toast container --}}
<div id="toast-container"></div>

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded',()=>showToast('success','Done!',{{ json_encode(session('success')) }}));</script>
@endif
@if(session('error'))
<script>document.addEventListener('DOMContentLoaded',()=>showToast('error','Error',{{ json_encode(session('error')) }}));</script>
@endif
@if(session('warning'))
<script>document.addEventListener('DOMContentLoaded',()=>showToast('warning','Warning',{{ json_encode(session('warning')) }}));</script>
@endif
@if(session('info'))
<script>document.addEventListener('DOMContentLoaded',()=>showToast('info','Info',{{ json_encode(session('info')) }}));</script>
@endif

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Confirm delete
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Are you sure?', text: 'This action cannot be undone.',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then(result => { if (result.isConfirmed) form.submit(); });
    });

    // Select2 init
    $('.select2').select2({ width: '100%' });

    // ── Toast system ──
    function showToast(type, title, message, duration) {
        duration = duration || 4500;
        const icons = { success:'fa-check', error:'fa-times', warning:'fa-exclamation', info:'fa-info' };
        const id = 'toast-' + Date.now();
        const html = `
        <div id="${id}" class="toast toast-${type}">
            <div class="toast-icon"><i class="fas ${icons[type]||'fa-bell'}"></i></div>
            <div class="toast-body">
                <div class="toast-title">${title}</div>
                ${message ? `<div class="toast-msg">${message}</div>` : ''}
            </div>
            <button class="toast-close" onclick="dismissToast('${id}')"><i class="fas fa-xmark"></i></button>
            <div class="toast-progress" style="--duration:${duration}ms; color:${type==='success'?'#22c55e':type==='error'?'#ef4444':type==='warning'?'#f59e0b':'#3b82f6'};"></div>
        </div>`;
        $('#toast-container').append(html);
        setTimeout(() => dismissToast(id), duration);
    }
    function dismissToast(id) {
        const el = $('#' + id);
        if (!el.length) return;
        el.addClass('toast-out');
        setTimeout(() => el.remove(), 300);
    }
    window.showToast = showToast;

    // ── Global search ──
    const GSEARCH_URL = '{{ route("officers.search") }}';
    let gsearchTimer;
    let gsearchSelected = -1;

    window.openGlobalSearch = function() {
        document.getElementById('gsearch-overlay').classList.add('open');
        setTimeout(() => document.getElementById('gsearch-input').focus(), 50);
    };
    window.closeGlobalSearch = function() {
        document.getElementById('gsearch-overlay').classList.remove('open');
        document.getElementById('gsearch-input').value = '';
        document.getElementById('gsearch-results').innerHTML = '<p class="px-4 py-8 text-center text-sm text-gray-400">Start typing to search officers…</p>';
        gsearchSelected = -1;
    };
    window.runGlobalSearch = function(q) {
        clearTimeout(gsearchTimer);
        if (q.length < 2) {
            document.getElementById('gsearch-results').innerHTML = '<p class="px-4 py-8 text-center text-sm text-gray-400">Type at least 2 characters…</p>';
            return;
        }
        document.getElementById('gsearch-results').innerHTML = '<p class="px-4 py-8 text-center text-sm text-gray-400"><i class="fas fa-circle-notch fa-spin mr-2"></i>Searching…</p>';
        gsearchTimer = setTimeout(() => {
            fetch(GSEARCH_URL + '?q=' + encodeURIComponent(q), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(results => {
                const el = document.getElementById('gsearch-results');
                if (!results.length) {
                    el.innerHTML = '<p class="px-4 py-8 text-center text-sm text-gray-400">No officers found for "' + q + '"</p>';
                    return;
                }
                el.innerHTML = results.map((o, i) => `
                <a href="${o.url}" class="gsearch-item flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-colors" data-idx="${i}">
                    ${o.photo_url ? `<img src="${o.photo_url}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">` :
                      `<div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0" style="background:linear-gradient(135deg,#0b1d55,#1e40af)">${o.name.charAt(0)}</div>`}
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 text-sm truncate">${o.name}</p>
                        <p class="text-xs text-gray-400">${o.rank} · ${o.svc_no} · <span class="${o.status==='Retired'?'text-gray-400':'text-green-600'}">${o.status}</span></p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                </a>`).join('');
                gsearchSelected = -1;
            });
        }, 220);
    };

    // Keyboard navigation in search
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); openGlobalSearch(); }
        const overlay = document.getElementById('gsearch-overlay');
        if (!overlay.classList.contains('open')) return;
        if (e.key === 'Escape') { closeGlobalSearch(); return; }
        const items = overlay.querySelectorAll('.gsearch-item');
        if (!items.length) return;
        if (e.key === 'ArrowDown') { e.preventDefault(); gsearchSelected = Math.min(gsearchSelected + 1, items.length - 1); items.forEach((el, i) => el.classList.toggle('bg-blue-50', i === gsearchSelected)); items[gsearchSelected]?.scrollIntoView({ block: 'nearest' }); }
        if (e.key === 'ArrowUp')   { e.preventDefault(); gsearchSelected = Math.max(gsearchSelected - 1, 0); items.forEach((el, i) => el.classList.toggle('bg-blue-50', i === gsearchSelected)); items[gsearchSelected]?.scrollIntoView({ block: 'nearest' }); }
        if (e.key === 'Enter' && gsearchSelected >= 0) { items[gsearchSelected].click(); }
    });

    // ── Copy to clipboard ──
    window.copyToClipboard = function(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-green-500"></i>';
            setTimeout(() => btn.innerHTML = orig, 1500);
            showToast('success', 'Copied!', text, 2000);
        }).catch(() => {
            // fallback
            const el = document.createElement('textarea');
            el.value = text; document.body.appendChild(el);
            el.select(); document.execCommand('copy');
            document.body.removeChild(el);
            showToast('success', 'Copied!', text, 2000);
        });
    };

    // ── Session idle timeout (30 minutes) ──
    (function(){
        const TIMEOUT = 30 * 60 * 1000;
        let timer;
        function reset() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                document.getElementById('session-timeout-modal').classList.remove('hidden');
            }, TIMEOUT);
        }
        ['mousemove','keypress','click','scroll','touchstart'].forEach(e => document.addEventListener(e, reset, true));
        reset();
    })();
</script>
@yield('scripts')

{{-- ── Global Search Overlay ── --}}
<div id="gsearch-overlay" class="fixed inset-0 bg-black/60 z-50 items-start justify-center pt-20 px-4"
     onclick="if(event.target===this)closeGlobalSearch()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden">
        <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100">
            <i class="fas fa-search text-gray-400"></i>
            <input id="gsearch-input" type="text" placeholder="Search by name, service number or rank…"
                   class="flex-1 text-gray-800 text-sm outline-none placeholder-gray-400"
                   oninput="runGlobalSearch(this.value)">
            <button onclick="closeGlobalSearch()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div id="gsearch-results" class="max-h-80 overflow-y-auto divide-y divide-gray-50">
            <p class="px-4 py-8 text-center text-sm text-gray-400">Start typing to search officers…</p>
        </div>
        <div class="px-4 py-2 bg-gray-50 flex items-center gap-4 text-xs text-gray-400">
            <span><kbd class="bg-white border border-gray-200 rounded px-1 mr-1">↑↓</kbd>Navigate</span>
            <span><kbd class="bg-white border border-gray-200 rounded px-1 mr-1">Enter</kbd>Open</span>
            <span><kbd class="bg-white border border-gray-200 rounded px-1 mr-1">Esc</kbd>Close</span>
        </div>
    </div>
</div>

<div id="session-timeout-modal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 text-center shadow-2xl">
        <i class="fas fa-clock text-amber-500 text-4xl mb-4 block"></i>
        <h3 class="text-gray-800 font-bold text-lg mb-2">Session Timeout</h3>
        <p class="text-gray-500 text-sm mb-6">Your session has expired due to inactivity. Please log in again.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-[#0b1d55] text-white py-2.5 rounded-lg font-semibold hover:bg-[#071230]">
                <i class="fas fa-sign-in-alt mr-2"></i>Return to Login
            </button>
        </form>
    </div>
</div>
</body>
</html>
