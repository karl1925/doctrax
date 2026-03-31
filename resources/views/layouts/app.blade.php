@php
use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctrax | @yield('title', 'v1.1')</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
    <script>
        dayjs.extend(dayjs_plugin_relativeTime);
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        body { overflow-x: hidden; }

        @keyframes slide-in-right {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in-right { animation: slide-in-right 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .animate-fade-in { animation: fade-in 0.5s ease-out; }
        [x-cloak] { display: none !important; }
        #notif-drop {
            max-height: calc(100vh - 80px);
            overflow-x: hidden;
            overflow-y: auto;
        }
        .notifyItem {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0;
            background-color: transparent;
            transition: all 0.15s ease-in-out;
        }

        .notifyItem:hover {
            background-color: #f0f4ff;
        }
        .capsule {
            display: flex;                        /* flex */
            width: 100%;                          /* w-full */
            align-items: flex-start;              /* items-start */
            justify-content: space-between;       /* justify-between */
            gap: 0.5rem;                          /* gap-2 (Tailwind gap-2 ≈ 0.5rem) */
            padding: 0.5rem;                      /* p-2 (0.5rem all around) */
            border-bottom: 1px solid #f1f5f9;    /* border-b border-slate-100 */
        }
    </style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    class="font-sans overflow-auto antialiased 
           text-slate-900 dark:text-slate-200 
           bg-slate-100 dark:bg-slate-950 
           transition-colors duration-300"
    x-data="{ mobileMenuOpen: false }"
>

    @auth
        <!-- Header -->
        <header class="fixed top-0 z-[60] w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-slate-200 dark:border-gray-700">
            <div class="relative px-4 h-16 flex items-center justify-between lg:px-6">
                <div class="flex items-center gap-4">
                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="lg:hidden p-2 -ml-2 rounded-xl text-slate-500 dark:text-slate-300 
                                hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors focus:outline-none">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>

                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('logo.png') }}" style="height: 50px; width:50px" class="hidden lg:inline group-hover:scale-105 transition-transform">
                        <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white/80">DocTrax</span>
                    </a>
                </div>

                <div class="flex items-center gap-0">
                    @include('partials.notifications')
                    <!-- Help Button -->
                    <a href="{{ route('manual') }}" target="_blank"
                    class="w-10 h-10  mr-2 flex items-center justify-center rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                        <i class="fas fa-question-circle text-lg"></i>
                    </a>

                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <!-- Profile Button -->
                        <button 
                            @click="open = !open"
                            :class="open ? 'text-white bg-indigo-500 dark:bg-indigo-600' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'" 
                            class="flex items-center gap-3 border-l border-slate-200 dark:border-slate-700 pl-0 rounded-full transition-all"
                        >
                            @if (auth()->user()->profile_photo_path)
                                <img
                                    @php
                                        $img_src = asset('storage/' . auth()->user()->profile_photo_path);
                                        if (!Str::startsWith($img_src, 'avatars')) {
                                            $img_src = auth()->user()->profile_photo_path;
                                        }
                                    @endphp
                                    src="{{ $img_src }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-100 dark:ring-indigo-300"
                                >
                            @else
                                <img
                                    src="{{ Avatar::create(auth()->user()->name)->toBase64() }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-10 h-10 rounded-full ring-2 ring-indigo-100 dark:ring-indigo-300"
                                >
                            @endif
                            <div class="hidden lg:flex flex-col gap-0 text-left">
                                <div class="text-[10px] uppercase font-bold mb-0">
                                    {{ auth()->user()->name }}
                                </div>
                                <div class="hidden lg:inline text-[8px] italic text-slate-600 dark:text-slate-400">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>
                            <i 
                                :class="open 
                                    ? 'text-white rotate-180 dark:text-slate-200' 
                                    : 'text-slate-500 dark:text-slate-400'" 
                                class="fas fa-chevron-down text-xs transition-transform duration-300 mr-2">
                            </i>
                        </button>

                        <!-- Dropdown -->
                        <div 
                            x-show="open"
                            x-cloak
                            @click.away="open = false"
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg py-1 z-50 text-slate-900"
                        >
                            <a href="{{ route('profile') }}"
                            class="flex items-center px-4 py-2 text-slate-700 dark:text-white/80 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                                <i class="fas fa-user mr-2"></i> My Profile
                            </a>
                            <a href="{{ route('settings.preferences') }}"
                            class="flex items-center px-4 py-2 text-slate-700 dark:text-white/80 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                                <i class="fas fa-user-gear mr-2"></i> My Preferences
                            </a>
                            <div class="border-t my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full text-left flex items-center px-4 py-2 text-slate-700 hover:bg-red-50 hover:text-red-600 transition-all"
                                >
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar Overlay (Mobile) -->
        <!-- Overlay -->
        <div x-show="mobileMenuOpen" x-cloak @click="mobileMenuOpen = false" class="fixed inset-0 z-40 bg-slate-900/60 dark:bg-black/70 lg:hidden transition-opacity duration-300"></div>
        <!-- Sidebar Navigation -->
        <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed top-0 left-0 z-50 w-72 h-screen pt-16 transition-transform 
                    bg-white dark:bg-slate-900 
                    border-r border-slate-200 dark:border-slate-700
                    lg:translate-x-0 overflow-hidden flex flex-col shadow-2xl lg:shadow-none">
            
            <div class="flex-1 overflow-y-auto px-4 py-3 custom-scrollbar space-y-3">

                <!-- DASHBOARD -->
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                    class="flex items-center gap-3 p-3 text-sm font-bold rounded-xl transition-all
                    {{ request()->routeIs('dashboard') 
                            ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' 
                            : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="fas fa-layer-group w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </nav>

                <!-- INITIATE -->
                <div>
                    <h3 class="px-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.15em] mb-3">
                        Initiate
                    </h3>

                    <div class="grid grid-cols-{{ config('modules.internal_routing') ? '2' : '1'}} gap-2">
                        <a href="{{ route('externals.create') }}"
                        class="flex flex-col items-center justify-center p-3 rounded-2xl 
                                bg-slate-50 dark:bg-slate-800 
                                border border-slate-100 dark:border-slate-700
                                hover:border-indigo-200 dark:hover:border-indigo-500 
                                hover:bg-white dark:hover:bg-slate-700
                                transition-all text-center group
                                {{ auth()->user()->canReceiveRequests() ? '' : 'pointer-events-none opacity-50' }}">
                            <i class="fas fa-plus-circle text-indigo-500 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] font-black text-slate-700 dark:text-slate-200 uppercase">
                                External Request
                            </span>
                        </a>

                        @if(config('modules.internal_routing'))
                        <a href="{{ route('documents.create') }}"
                        class="flex flex-col items-center justify-center p-3 rounded-2xl 
                                bg-slate-50 dark:bg-slate-800 
                                border border-slate-100 dark:border-slate-700
                                hover:border-indigo-200 dark:hover:border-indigo-500 
                                hover:bg-white dark:hover:bg-slate-700
                                transition-all text-center group">
                            <i class="fas fa-route text-indigo-500 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] font-black text-slate-700 dark:text-slate-200 uppercase">
                                Internal Routing
                            </span>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- EXTERNAL REQUESTS -->
                <div>
                    <h3 class="px-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.15em] mb-3">
                        External Requests
                    </h3>

                    <nav class="space-y-1">
                        @php
                            $extItems = [];

                            $extItems[] = [
                                'route' => 'externals.mytasks',
                                'icon'  => 'fa-user-check',
                                'label' => 'Assigned to Me',
                            ];

                            if (auth()->user()->canReceiveRequests()) {
                                $extItems[] = [
                                    'route' => 'externals.monitoring',
                                    'icon'  => 'fa-chart-line',
                                    'label' => 'Monitoring',
                                ];
                            }

                            $extItems[] = [
                                'route' => 'externals.completed',
                                'icon'  => 'fa-check-double',
                                'label' => 'Completed',
                            ];

                        @endphp
                        @foreach($extItems as $item)
                            @php
                                $isActive = Illuminate\Support\Str::startsWith(
                                    request()->route()->getName(),
                                    $item['route']
                                );
                            @endphp

                            <a href="{{ route($item['route']) }}"
                            class="flex items-center gap-3 p-3 text-sm font-bold rounded-xl transition-all
                            {{ $isActive
                                    ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm'
                                    : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                                <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- INTERNAL ROUTING -->
                @if(config('modules.internal_routing'))
                <div>
                    <h3 class="px-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.15em] mb-3">
                        Internal Routing
                    </h3>

                    <nav class="space-y-1">
                        @php
                            $intItems = [
                                ['route' => 'documents.forsigning', 'icon' => 'fa-pen-nib', 'label' => 'For Signing'],
                                ['route' => 'documents.inprogress', 'icon' => 'fa-circle-notch fa-spin', 'label' => 'In Transit'],
                                ['route' => 'documents.forrevision', 'icon' => 'fa-redo-alt', 'label' => 'For Revision'],
                                ['route' => 'documents.rejected', 'icon' => 'fa-circle-xmark', 'label' => 'Rejected'],
                                ['route' => 'documents.completed', 'icon' => 'fa-check-circle', 'label' => 'Finalized'],
                                ['route' => 'documents.history', 'icon' => 'fa-history', 'label' => 'My Signings'],
                            ];
                        @endphp
                        @foreach($intItems as $item)
                        
                            @php
                                $isActive = Illuminate\Support\Str::startsWith(
                                    request()->route()->getName(),
                                    $item['route']
                                );
                            @endphp

                            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                            class="flex items-center gap-3 p-3 text-sm font-bold rounded-xl transition-all
                            {{ $isActive
                                    ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm'
                                    : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                                <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
                @endif
                @if(auth()->user()->isSuper())
                <!-- SETTINGS -->
                <div>
                    <h3 class="px-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.15em] mb-3">
                        Settings
                    </h3>
                        <nav class="space-y-1">
                            
                            @php
                                    $intItems = [
                                        ['route' => 'settings.organization', 'icon' => 'fa-sitemap', 'label' => 'Organization'],
                                        ['route' => 'settings.personnel', 'icon' => 'fa-users', 'label' => 'Personnel'],
                                        ['route' => 'settings.partners', 'icon' => 'fa-handshake', 'label' => 'Partners'],
                                    ];
                            @endphp
                            @foreach($intItems as $item)

                                @php
                                    $isActive = Illuminate\Support\Str::startsWith(
                                        request()->route()->getName(),
                                        $item['route']
                                    );
                                @endphp
                                
                                <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                                class="flex items-center gap-3 p-3 text-sm font-bold rounded-xl transition-all
                                {{ $isActive
                                        ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm'
                                        : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                                    <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </nav>
                </div>
                @endif
            </div>
        </aside>    

        <main class="lg:ml-72 pt-16 min-h-screen  
             text-slate-900 dark:text-slate-200
             transition-all duration-300">
    @else
        <main class="pt-6 min-h-screen text-slate-900 dark:text-slate-200 transition-colors duration-300">
    @endif

            <div class="px-4 py-6 md:px-8 md:py-10 mx-auto">
                
                <!-- Floating Toast Notifications -->
                <div class="fixed top-20 right-4 z-[100] w-full max-w-sm pointer-events-none flex flex-col gap-3">

                    <!-- 1. Success Message -->
                    @if(session('success'))
                        <div x-data="{ show: true }" 
                            x-show="show" 
                            x-init="setTimeout(() => show = false, 6000)" 
                            x-transition:leave="transition ease-in duration-300 transform translate-x-full opacity-0"
                            class="pointer-events-auto p-4 rounded-2xl bg-white dark:bg-slate-800 border-l-4 border-l-emerald-500 dark:border-l-emerald-400 shadow-2xl border border-slate-100 dark:border-slate-700 flex items-start gap-4 animate-slide-in-right">
                            
                            <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900 flex items-center justify-center shrink-0">
                                <i class="fas fa-check text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-0.5">Operation Successful</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ session('success') }}</p>
                            </div>
                            
                            <button @click="show = false" class="text-slate-300 hover:text-slate-500 dark:hover:text-slate-400 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <!-- 2. Single Error Message -->
                    @if(session('error'))
                        <div x-data="{ show: true }" 
                            x-show="show" 
                            x-init="setTimeout(() => show = false, 8000)" 
                            x-transition:leave="transition ease-in duration-300 transform translate-x-full opacity-0"
                            class="pointer-events-auto p-4 rounded-2xl bg-white dark:bg-slate-800 border-l-4 border-l-rose-500 dark:border-l-rose-400 shadow-2xl border border-slate-100 dark:border-slate-700 flex items-start gap-4 animate-slide-in-right">
                            
                            <div class="w-10 h-10 rounded-full bg-rose-50 dark:bg-rose-900 flex items-center justify-center shrink-0">
                                <i class="fas fa-exclamation-triangle text-rose-600 dark:text-rose-400"></i>
                            </div>
                            
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest mb-0.5">System Error</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ session('error') }}</p>
                            </div>
                            
                            <button @click="show = false" class="text-slate-300 hover:text-slate-500 dark:hover:text-slate-400 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <!-- 3. Validation Errors -->
                    @if($errors->any())
                        <div x-data="{ show: true }" 
                            x-show="show" 
                            class="pointer-events-auto p-4 rounded-2xl bg-white dark:bg-slate-800 border-l-4 border-l-amber-500 dark:border-l-amber-400 shadow-2xl border border-slate-100 dark:border-slate-700 flex items-start gap-4 animate-slide-in-right">
                            
                            <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-900 flex items-center justify-center shrink-0">
                                <i class="fas fa-shield-alt text-amber-600 dark:text-amber-400"></i>
                            </div>
                            
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest mb-0.5">Validation Issues</p>
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            <button @click="show = false" class="text-slate-300 hover:text-slate-500 dark:hover:text-slate-400 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Main Content Slot -->
                <div class="animate-fade-in">
                    @yield('content')
                </div>
                <div class="w-full p-0 m-0 flex justify-center">
                    <div class="text-center p-2 text-[12px] text-gray-400 dark:text-gray-400/80">
                        DICT R2 DocTrax: A Regional Initiative
                    </div>
                </div>
            </div>
           
        </main>


    <div id="preview-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm" style="z-index: 9999">

        <div class="bg-white w-full max-w-5xl h-[85vh] rounded-2xl shadow-xl flex flex-col overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-sm font-black uppercase tracking-widest text-slate-700">
                    File Preview
                </h3>
                <button onclick="closePreviewModal()"
                        class="text-slate-400 hover:text-slate-800 text-xl">
                    &times;
                </button>
            </div>

            <!-- Content -->
            <div id="preview-content" class="flex-1 overflow-auto bg-slate-50 p-4 flex items-center justify-center">
                <!-- Injected dynamically -->
            </div>
        </div>
    </div>

    <form id="deleter-form" action="{{ route('notifications.clear-read') }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
</body>
</html>
@stack('scripts')
<script>
    const notifBtn = document.getElementById('notification-btn');
    const notifDropdown = document.getElementById('notification-dropdown');
    const notifCount = document.getElementById('notification-count');
    const notifDrop = document.getElementById('notif-drop');
    const newNotifContainer = document.getElementById('new-notification-items');
    const earlierNotifContainer = document.getElementById('earlier-notification-items');
    const fetchMoreBtn = document.getElementById('fetch-more-btn');
    const allBtn = document.getElementById("notif-filter-all");
    const unreadBtn = document.getElementById("notif-filter-unread");
    const clearReadBtn = document.getElementById('clear-read-btn');
    const markAllReadBtn = document.getElementById('mark-all-read');
    const notifMenuBtn = document.getElementById("notif-menu-btn");
    const notifMenu = document.getElementById("notif-menu");
    const newEmpty = document.getElementById("new-empty");
    const earlierEmpty = document.getElementById("earlier-empty");
    const earlierLabel = document.getElementById("earlier-label");
    const menu = document.getElementById("common-item-menu");
    let currentItemId = null;
    let currentFilter = 'all';
    let lastReadId = null;
    let isLoading = false;

    function openPreviewModal(url, ext) {
        const modal = document.getElementById('preview-modal');
        const content = document.getElementById('preview-content');

        content.innerHTML = '';

        if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
            content.innerHTML = `
                <img src="${url}"
                    class="max-h-full max-w-full rounded-xl shadow"
                    alt="Preview">
            `;
        } else if (ext === 'pdf') {
            content.innerHTML = `
                <iframe src="${url}"
                        class="w-full h-full rounded-xl border bg-white"></iframe>
            `;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePreviewModal() {
        const modal = document.getElementById('preview-modal');
        const content = document.getElementById('preview-content');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        content.innerHTML = '';
    }

    function handleButtonClick(button) {
        button.disabled = true;
        button.querySelector('.btn-text').classList.add('hidden');
        button.querySelector('.spinner').classList.remove('hidden');
        document.getElementById('forgotPasswordForm').submit();
    }

    function getTypeClass(type) {
        switch (type) {
            case "New":
                return "bg-blue-100 text-blue-700";

            case "Update":
                return "bg-indigo-100 text-indigo-700";

            case "Follow-Up":
                return "bg-amber-100 text-amber-700";

            case "Task Completed":
                return "bg-green-100 text-green-700";

            case "For Assignment":
                return "bg-purple-100 text-purple-700";

            case "Attachment Added":
                return "bg-cyan-100 text-cyan-700";

            case "New Assignment":
                return "bg-fuchsia-100 text-fuchsia-700";

            case "Pending Acceptance":
                return "bg-orange-100 text-orange-700";

            case "Task Accepted":
                return "bg-emerald-100 text-emerald-700";

            default:
                return "bg-slate-100 text-slate-700";
        }
    }

    function toggleMenu(e, id) {
        e.preventDefault();
        e.stopPropagation();
        const menu = document.getElementById(`menu-${id}`);
        document.querySelectorAll('[id^="menu-"]').forEach(m => {
            if (m !== menu) {
                m.classList.add('hidden', 'opacity-0', 'scale-95');
            }
        });
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
            }, 10);
        } else {
            menu.classList.add('opacity-0', 'scale-95');
            setTimeout(() => menu.classList.add('hidden'), 150);
        }
    }

    function markAsRead(e, id) {
        e.preventDefault();
        e.stopPropagation();
        fetch('/notifications/mark-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById(`menu-${id}`).querySelector('button').remove();
            const card = document.getElementById(`notification-card-${id}`);
            if (card) {
                card.classList.remove('bg-indigo-50'); 
                earlierNotifContainer.prepend(card);
                const dot = card.querySelector('.newdot');
                if (dot) dot.remove();
                document.getElementById(`menu-${id}`).classList.add('hidden');
                filterNotifications(currentFilter);
            }
        })
        .catch(err => console.error(err));
    }

    function deleteNotification(e, id) {
        e.preventDefault();
        e.stopPropagation();

        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            const card = document.getElementById(`notification-card-${id}`);
            if (card) card.remove();
            filterNotifications(currentFilter);
        })
        .catch(err => console.error(err));
    }

    function renderNotification(n) {
        const div = document.createElement('div');
        div.className = 'notifyItem p-2 border-b border-slate-200';
        div.id = "notification-card-" + n.id;
        if (n.is_new) div.classList.add('bg-indigo-50');
        div.innerHTML = `
            <div class="relative group w-full justify-between">
                <a href="${n.url}" 
                class="block p-3 pr-12 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <div class="flex items-start gap-3">
                        ${n.is_new ? `<span class="newdot mt-1 w-2 h-2 bg-indigo-500 rounded-full"></span>` : ``}
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full ${getTypeClass(n.type)}">
                                    ${n.type}
                                </span>
                                <span class="font-semibold text-sm text-slate-800">
                                    ${n.subject}
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 line-clamp-2">
                                ${n.message}
                            </p>
                            <div class="mt-1 text-xs text-slate-400">
                                ${n.created_by} • ${n.time}
                            </div>
                        </div>
                    </div>
                </a>
                <button onclick="toggleMenu(event, '${n.id}')" 
                    class="absolute top-2 right-2 p-1.5 rounded-lg 
                        text-slate-400 hover:text-slate-700 hover:bg-slate-200
                        opacity-0 group-hover:opacity-100 transition"
                    aria-label="Open menu">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
                <div id="menu-${n.id}" 
                    class="hidden absolute right-2 top-10 w-44 
                            bg-white border border-slate-200 rounded-xl shadow-lg 
                            z-50 overflow-hidden
                            origin-top-right scale-95 opacity-0 transition-all duration-150">
                    ${n.is_new ? `    
                    <button onclick="markAsRead(event, '${n.id}')" 
                            class="w-full text-left px-3 py-2 text-sm 
                                hover:bg-slate-50 flex items-center gap-2">
                        <i class="fa-solid fa-check text-slate-400"></i>
                        Mark as Read
                    </button>
                    ` : ``}
                    <button onclick="deleteNotification(event, '${n.id}')" 
                            class="w-full text-left px-3 py-2 text-sm 
                                hover:bg-red-50 text-red-600 flex items-center gap-2">
                        <i class="fa-solid fa-trash"></i>
                        Delete this notification
                    </button>
                </div>
            </div>
        `;
        return div;
    }

    async function fetchNotifications(loadMore = false, onlyUnread = false) {
        if (isLoading) return;
        isLoading = true;
        const params = new URLSearchParams();
        if (loadMore && lastReadId) params.append('last_read_id', lastReadId);
        if (onlyUnread) params.append('unread_only', '1');
        const url = '/notifications/fetch' + (params.toString() ? '?' + params.toString() : '');
        fetchMoreBtn.disabled = true;
        fetchMoreBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';
        try {
            const res = await fetch(url);
            const data = await res.json();
            if (!loadMore) {
                newNotifContainer.innerHTML ='';
                data.new.forEach(n => {
                    const div = renderNotification(n);
                    newNotifContainer.prepend(div);
                });
                notifCount.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                notifCount.classList.toggle('hidden', data.unread_count === 0);
            }
            if (loadMore) {
                data.earlier.forEach(n => {
                    if (!document.getElementById(`notification-card-${n.id}`)) {
                        const div = renderNotification(n);
                        earlierNotifContainer.appendChild(div);
                    }
                });
                if (data.earlier.length) {
                    lastReadId = data.earlier[data.earlier.length - 1].id;
                } else {
                    fetchMoreBtn.style.display = 'none'; 
                }
            }
            notifCount.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
            notifCount.classList.toggle('hidden', data.unread_count === 0);
        } catch (err) {
            console.error('Error fetching notifications:', err);
        } finally {
            isLoading = false;
            fetchMoreBtn.disabled = false;
            fetchMoreBtn.innerHTML = 'See More';
            filterNotifications(currentFilter);
        }
    }

    function filterNotifications(type) {
        const newLinks = newNotifContainer.querySelectorAll('.notifyItem');
        const earlierLinks = earlierNotifContainer.querySelectorAll('.notifyItem');
        newNotifContainer.style.display = newLinks.length > 0 ? 'block' : 'none';
        earlierNotifContainer.style.display = (type === 'all' && earlierLinks.length > 0) ? 'block' : 'none';
        newEmpty.style.display = newLinks.length === 0 ? 'block' : 'none';
        earlierEmpty.style.display = (type === 'all' && earlierLinks.length === 0) ? 'block' : 'none';
        earlierLabel.style.display = type === 'all' ? 'block' : 'none';
        if (type === 'all') {
            allBtn.classList.add('bg-indigo-50', 'text-indigo-700');
            allBtn.classList.remove('bg-slate-100', 'text-slate-500');
            unreadBtn.classList.add('bg-slate-100', 'text-slate-500');
            unreadBtn.classList.remove('bg-indigo-50', 'text-indigo-700');
        } else {
            unreadBtn.classList.add('bg-indigo-50', 'text-indigo-700');
            unreadBtn.classList.remove('bg-slate-100', 'text-slate-500');
            allBtn.classList.add('bg-slate-100', 'text-slate-500');
            allBtn.classList.remove('bg-indigo-50', 'text-indigo-700');
        }
    }

    function getBadgeColor(subject) {
        switch (subject) {
            case 'New': return 'bg-blue-100 text-blue-600';
            case 'Completed': return 'bg-green-100 text-green-600';
            case 'For Assignment': return 'bg-orange-100 text-orange-600';
            case 'Pending Acceptance': return 'bg-red-100 text-red-600';
            case 'Task Accepted': return 'bg-green-200 text-green-800';
            case 'New Assignment': return 'bg-indigo-100 text-indigo-600';
            case 'Attachment Added': return 'bg-purple-100 text-purple-600';
            case 'Follow-Up': return 'bg-yellow-100 text-yellow-600';
            case 'Update': return 'bg-teal-100 text-teal-600';
            default: return 'bg-slate-100 text-slate-700';
        }
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closePreviewModal();
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('[id^="menu-"]').forEach(m => {
            m.classList.add('opacity-0', 'scale-95');
            setTimeout(() => m.classList.add('hidden'), 150);
        });
    });

    allBtn.addEventListener('click', () => {
        currentFilter = 'all';
        filterNotifications(currentFilter);
    });

    unreadBtn.addEventListener('click', () => {
        currentFilter = 'unread';
        filterNotifications(currentFilter);
    });

    markAllReadBtn.addEventListener("click", async () => {
        await fetch("/notifications/read-all", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        });
        location.reload();
    });

    clearReadBtn.addEventListener('click', async () => {
        if (!confirm('Are you sure you want to delete all read notifications?')) return;
        document.getElementById('deleter-form').submit();
    });

    notifDrop.addEventListener('scroll', () => {
        if (notifDrop.scrollTop + notifDrop.clientHeight >= notifDrop.scrollHeight - 10) {
            fetchNotifications(true); 
        }
    });

    fetchMoreBtn.addEventListener('click', () => fetchNotifications(true));
    setInterval(() => fetchNotifications(false,true), 10000);
    fetchNotifications(false,true);
    isLoading = false;
    fetchNotifications(true,false);

    function timestampCard(timestamp) {
        return {
            lastUpdated: timestamp,
            diffText: '',
            updateDiff() {
                const now = Math.floor(Date.now() / 1000);
                const diff = now - this.lastUpdated;

                if (diff < 60) this.diffText = 'Just now';
                else if (diff < 3600) this.diffText = Math.floor(diff/60) + ' minutes ago';
                else if (diff < 86400) this.diffText = Math.floor(diff/3600) + ' hours ago';
                else this.diffText = Math.floor(diff/86400) + ' days ago';
            },
            init() {
                this.updateDiff();
                setInterval(() => this.updateDiff(), 60000); 
            }
        };
    }
</script>
