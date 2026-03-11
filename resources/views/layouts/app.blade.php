@php
use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctrax | @yield('title', 'v1.1')</title>
    
    <link rel="icon" type="image/png" href="/logo.png">

    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-900 bg-slate-50" 
      x-data="{ mobileMenuOpen: false }">

    @auth
        <!-- Header -->
        <header class="fixed top-0 z-[60] w-full bg-white/80 backdrop-blur-md border-b border-slate-200">
            <div class="px-4 h-16 flex items-center justify-between lg:px-6">
                <div class="flex items-center gap-4">
                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="lg:hidden p-2 -ml-2 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors focus:outline-none">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>

                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('logo.png') }}" style="height: 50px; width:50px" class="group-hover:scale-105 transition-transform">
                        <div class="bg-indigo-600 p-2 rounded-xl shadow-lg shadow-indigo-200 group-hover:scale-105 transition-transform">
                            <i class="fas fa-file-signature text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-black tracking-tight text-slate-900">DocTrax</span>
                    </a>
                </div>

                <div class="flex items-center gap-0">
                    <!-- Notification Icon -->
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                        <i class="fas fa-bell text-lg"></i>
                    </button>

                    <!-- Help Button -->
                    <a href="{{ route('manual') }}" target="_blank"
                    class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all ml-2">
                        <i class="fas fa-question-circle text-lg"></i>
                    </a>

                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative ml-2">
                        <button @click="open = !open" class="flex items-center gap-3 border-l border-slate-200 pl-4 rounded-xl hover:bg-slate-100 transition-all">
                            @if (auth()->user()->profile_photo_path)
                                <img
                                    @php
                                        $img_src = asset('storage/' . auth()->user()->profile_photo_path);
                                        if (!Str::startsWith($img_src, 'avatars')) {
                                            $img_src = auth()->user()->profile_photo_path;
                                        }
                                    @endphp
                                    src="{{ $img_src}}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-100"
                                >
                            @else
                                <img
                                    src="{{ Avatar::create(auth()->user()->name)->toBase64() }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-10 h-10 rounded-full ring-2 ring-indigo-100"
                                >
                            @endif
                            <span class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-slate-500 text-xs"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-lg py-1 z-50">
                            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                                <i class="fas fa-user mr-2"></i> My Profile
                            </a>
                            <a href="{{ route('settings.preferences') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                                <i class="fas fa-user-gear mr-2"></i> My Preferences
                            </a>
                            <div class="border-t my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-slate-700 hover:bg-red-50 hover:text-red-600 transition-all">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </header>

        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="mobileMenuOpen" x-cloak @click="mobileMenuOpen = false"
             class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden transition-opacity duration-300"></div>

        <!-- Sidebar Navigation -->
        <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed top-0 left-0 z-50 w-72 h-screen pt-16 transition-transform bg-white border-r border-slate-200 lg:translate-x-0 overflow-hidden flex flex-col shadow-2xl lg:shadow-none">
            
            <div class="flex-1 overflow-y-auto px-4 py-3 custom-scrollbar space-y-3">
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 p-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fas fa-layer-group w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </nav>

                
                <div>
                    <h3 class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-3">Initiate</h3>
                    <div class="grid grid-cols-{{ config('modules.internal_routing') ? '2' : '1'}} gap-2">
                        <a href="{{ route('externals.create') }}" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-200 hover:bg-white transition-all text-center group {{ auth()->user()->canReceiveRequests() ? '' : 'pointer-events-none opacity-50' }}">
                            <i class="fas fa-plus-circle text-indigo-500 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] font-black text-slate-700 uppercase">External Request</span>
                        </a>
                        @if(config('modules.internal_routing'))
                        <a href="{{ route('documents.create') }}" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-200 hover:bg-white transition-all text-center group">
                            <i class="fas fa-route text-indigo-500 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] font-black text-slate-700 uppercase">Internal Routing</span>
                        </a>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-3">External Requests</h3>
                    <nav class="space-y-1">
                        @php
                            $extItems = [];

                            if (auth()->user()->canForwardRequests()) {
                                $extItems[] = [
                                    'route' => 'externals.recording',
                                    'icon'  => 'fa-inbox',
                                    'label' => 'Incoming Requests',
                                ];
                            }

                            if (auth()->user()->canEndorseRequests()) {
                                $extItems[] = [
                                    'route' => 'externals.endorsing',
                                    'icon'  => 'fa-stamp',
                                    'label' => 'For Endorsement',
                                ];
                            }

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
                                    ? 'bg-indigo-50 text-indigo-700 shadow-sm active-link'
                                    : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                @if(config('modules.internal_routing'))
                <div>
                    <h3 class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-3">Internal Routing</h3>
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
                                    ? 'bg-indigo-50 text-indigo-700 shadow-sm active-link'
                                    : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
                @endif

                <div>
                    <h3 class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-3">Settings</h3>
                    <nav class="space-y-1">
                        @if(auth()->user()->isSuper())
                            @php
                                $intItems = [
                                    ['route' => 'settings.organization', 'icon' => 'fa-sitemap', 'label' => 'Organization'],
                                    ['route' => 'settings.personnel', 'icon' => 'fa-users', 'label' => 'Personnel'],
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
                                        ? 'bg-indigo-50 text-indigo-700 shadow-sm active-link'
                                        : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                    <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        @endif
                    </nav>
                </div>
            </div>

            {{-- <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3 p-2 bg-white border border-slate-200 rounded-xl">
                    <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase">System Status</span>
                        <span class="text-[11px] font-bold text-slate-700">Operational</span>
                    </div>
                </div>
            </div> --}}
        </aside>

        <main class="lg:ml-72 pt-16 min-h-screen transition-all duration-300">
    @else
        <main class="pt-6 min-h-screen bg-white">
    @endif

            <div class="px-4 py-6 md:px-8 md:py-10 mx-auto">
                
                <!-- Floating Toast Notifications -->
                <div class="fixed top-20 right-4 z-[100] w-full max-w-sm pointer-events-none flex flex-col gap-3">
                    
                    <!-- 1. Success Message -->
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" 
                             x-transition:leave="transition ease-in duration-300 transform translate-x-full opacity-0"
                             class="pointer-events-auto p-4 rounded-2xl bg-white border-l-4 border-l-emerald-500 shadow-2xl border border-slate-100 flex items-start gap-4 animate-slide-in-right">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Operation Successful</p>
                                <p class="text-sm font-bold text-slate-700">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-slate-300 hover:text-slate-500 transition-colors"><i class="fas fa-times"></i></button>
                        </div>
                    @endif

                    <!-- 2. Single Error Message (Custom) -->
                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" 
                             x-transition:leave="transition ease-in duration-300 transform translate-x-full opacity-0"
                             class="pointer-events-auto p-4 rounded-2xl bg-white border-l-4 border-l-rose-500 shadow-2xl border border-slate-100 flex items-start gap-4 animate-slide-in-right">
                            <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center shrink-0">
                                <i class="fas fa-exclamation-triangle text-rose-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-0.5">System Error</p>
                                <p class="text-sm font-bold text-slate-700">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-slate-300 hover:text-slate-500 transition-colors"><i class="fas fa-times"></i></button>
                        </div>
                    @endif

                    <!-- 3. Validation Errors (Laravel $errors bag) -->
                    @if($errors->any())
                        <div x-data="{ show: true }" x-show="show" 
                             class="pointer-events-auto p-4 rounded-2xl bg-white border-l-4 border-l-amber-500 shadow-2xl border border-slate-100 flex items-start gap-4 animate-slide-in-right">
                            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
                                <i class="fas fa-shield-alt text-amber-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-0.5">Validation Issues</p>
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li class="text-sm font-bold text-slate-700">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button @click="show = false" class="text-slate-300 hover:text-slate-500 transition-colors"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                </div>

                <!-- Main Content Slot -->
                <div class="animate-fade-in">
                    @yield('content')
                </div>
                <div class="w-full p-0 m-0 flex justify-center">
                    <div class="text-center p-2 text-[12px] text-gray-400">
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

    <script>
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

        // Close on ESC
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closePreviewModal();
        });

        document.addEventListener('DOMContentLoaded', () => {
            const activeLink = document.querySelector('.active-link');

            if (activeLink) {
                activeLink.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'nearest'
                });
            }
        });
        function handleButtonClick(button) {
            // Disable button
            button.disabled = true;

            // Hide button text
            button.querySelector('.btn-text').classList.add('hidden');

            // Show spinner
            button.querySelector('.spinner').classList.remove('hidden');

            // Submit the form
            document.getElementById('forgotPasswordForm').submit();
        }
    </script>
    @stack('scripts')
</body>
</html>