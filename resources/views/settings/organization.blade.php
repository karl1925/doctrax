@extends('layouts.app')

@section('title', 'Organization Settings')

@push('styles')
<style>
    /* Custom form styling */
    .form-select-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.5em 1.5em;
    }

    /* Modal Animation */
    .modal-enter { animation: modalFadeIn 0.2s ease-out; }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Glassmorphism for mobile headers */
    .glass-header {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
</style>
@endpush

@section('content')
<div class="flex flex-col lg:flex-row lg:items-end justify-between mb-10 gap-6">
    <div class="space-y-2">
        <nav class="flex mb-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-xs font-medium text-slate-500 uppercase tracking-wider">
                <li>SETTINGS</li>
                <li><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></li>
                <li class="text-indigo-600 font-bold">ORGANIZATION</li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
            <div class="p-2 bg-stone-500 rounded-lg shadow-lg shadow-gray-200">
                <i class="fa-solid fa-sitemap text-white"></i>
            </div>
            Organization Roles
        </h1>
        <p class="text-slate-500 font-medium text-sm">
            Manage institutional hierarchy and user access controls.
        </p>
    </div>
</div>



<div class="min-h-screen bg-[#f8fafc] pb-24">

    <div class="container mx-auto px-4 md:px-6 py-8">
        <div class="max-w-6xl mx-auto">
            
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Sidebar Navigation (Desktop) -->
                <div class="lg:col-span-3">
                    <nav class="space-y-1 sticky top-32">
                        <a href="#leadership" class="flex items-center gap-3 px-4 py-3 bg-white border border-slate-200 text-slate-900 rounded-xl font-bold shadow-sm transition-all hover:border-indigo-300 group">
                            <i class="fa-solid fa-id-badge text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                            Leadership
                        </a>
                        <a href="#Receiver" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-slate-900 hover:bg-white rounded-xl font-bold transition-all group">
                            <i class="fa-solid fa-download text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                            Receivers
                        </a>
                        <a href="#Forwarder" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-slate-900 hover:bg-white rounded-xl font-bold transition-all group">
                            <i class="fa-solid fa-forward text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                            Forwarders
                        </a>
                        <a href="#Endorser" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-slate-900 hover:bg-white rounded-xl font-bold transition-all group">
                            <i class="fa-solid fa-stamp text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                            Endorsers
                        </a>
                        <a href="#Monitorer" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-slate-900 hover:bg-white rounded-xl font-bold transition-all group">
                            <i class="fa-solid fa-globe text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                            Global Monitoring
                        </a>
                        <a href="#Super" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-slate-900 hover:bg-white rounded-xl font-bold transition-all group">
                            <i class="fa-solid fa-users-gear text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                            Super Users
                        </a>
                        {{-- <div class="pt-4 mt-4 border-t border-slate-200">
                            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Audit Logs</p>
                            <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-slate-900 hover:bg-white rounded-xl font-bold transition-all group">
                                <i class="fa-solid fa-clock-rotate-left text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                                History
                            </a>
                        </div> --}}
                    </nav>
                </div>

                <!-- Right: Content -->
                <div class="lg:col-span-9 space-y-10">
                    
                    <!-- Section: Leadership Roles -->
                    <section id="leadership" class="scroll-mt-32">
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">Leadership Configuration</h2>
                        </div>
                        
                        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                            <form action="{{ route('settings.updateLeadership') }}" method="POST">
                                @csrf
                                <div class="p-8 space-y-8">
                                    <div class="grid grid-cols-1 gap-8">
                                        <!-- Director -->
                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">Director</label>
                                            <select name="director_user_id" class="form-select-custom w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none">
                                                <option value="">Select Director</option>
                                                @foreach($users as $user)
                                                    <option class="uppercase" value="{{ $user->id }}" @selected($directorUserId == $user->id)>{{ strtoupper($user->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- ARD -->
                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">Asst. Regional Director</label>
                                            <select name="ard_user_id" class="form-select-custom w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none">
                                                <option value="">Select ARD</option>
                                                @foreach($users as $user)
                                                    <option class="uppercase" value="{{ $user->id }}" @selected($ardUserId == $user->id)>{{ strtoupper($user->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- AFD Chief -->
                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">AFD Chief</label>
                                            <select name="afd_chief_user_id" class="form-select-custom w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none">
                                                <option value="">Select AFD Chief</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" @selected($afdChiefUserId == $user->id)>{{ strtoupper($user->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- TOD Chief -->
                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">TOD Chief</label>
                                            <select name="tod_chief_user_id" class="form-select-custom w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none">
                                                <option value="">Select TOD Chief</option>
                                                @foreach($users as $user)
                                                    <option class="uppercase" value="{{ $user->id }}" @selected($todChiefUserId == $user->id)>{{ strtoupper($user->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex justify-end">
                                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/20 transition-all active:scale-95">
                                        Update Leadership
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <!-- Section: User Roles -->
                    <section class="scroll-mt-32">
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">Role Assignments</h2>
                        </div>

                        <div class="space-y-6">
                            @foreach($usersByRole as $role => $roleUsers)
                            <div id="{{ $role }}" class="pt-10">
                                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden transition-all hover:border-slate-300">
                                    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                                        <div class="flex items-center gap-3">
                                            <div class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-black text-slate-600 uppercase tracking-tighter">
                                                {{ $role }}s
                                            </div>
                                            <span class="text-slate-400 text-xs font-bold">{{ count($roleUsers) }} Active</span>
                                        </div>
                                        <button type="button" onclick="openAddUserModal('{{ $role }}')"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors group flex items-center gap-2 text-xs font-bold">
                                            <i class="fa-solid fa-plus"></i>
                                            <span class="hidden sm:inline">Add {{ $role }}</span>
                                        </button>
                                    </div>

                                    <!-- Desktop Table / Mobile Cards -->
                                    <div class="overflow-x-auto mb-5">
                                        <table class="w-full text-left hidden md:table">
                                            <thead>
                                                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                                                    <th class="px-6 py-4">Staff</th>
                                                    <th class="px-6 py-4 text-right">Access</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-50">
                                                @forelse($roleUsers as $user)
                                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                                    <td class="px-6 py-2">
                                                        <div class="flex items-center gap-3">
                                                            @if ($user->profile_photo_path)
                                                                <img
                                                                    src="{{ $user->profile_photo_path }}"
                                                                    alt="{{ $user->name }}"
                                                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-100"
                                                                >
                                                            @else
                                                                <img
                                                                    src="{{ Avatar::create($user->name)->toBase64()  }}"
                                                                    alt="{{ $user->name }}"
                                                                    class="w-10 h-10 rounded-full ring-2 ring-indigo-100"
                                                                >
                                                            @endif

                                                            <div class="flex flex-col leading-tight">
                                                                <span class="text-sm font-bold text-slate-900 uppercase">{{ $user->name }}</span>
                                                                <span class="text-[10px] text-slate-500 font-bold tracking-wider mt-1">{{ $user->email }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-right">
                                                        <form action="{{ route('settings.removeUser', [$role, $user->id]) }}" method="POST" class="inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-slate-300 hover:text-rose-600 transition-colors">
                                                                <i class="fa-solid fa-user-minus text-sm"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="px-6 py-12 text-center text-slate-400 text-xs font-bold italic">No users assigned to this role</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <!-- Mobile View -->
                                        <div class="md:hidden divide-y divide-slate-50">
                                            @foreach($roleUsers as $user)
                                            <div class="p-5 flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    @if ($user->profile_photo_path)
                                                        <img
                                                            src="{{ $user->profile_photo_path }}"
                                                            alt="{{ $user->name }}"
                                                            class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-100"
                                                        >
                                                    @else
                                                        <img
                                                            src="{{ Avatar::create($user->name)->toBase64()  }}"
                                                            alt="{{ $user->name }}"
                                                            class="w-10 h-10 rounded-full ring-2 ring-indigo-100"
                                                        >
                                                    @endif

                                                    <div class="flex flex-col leading-tight">
                                                        <span class="text-sm font-bold text-slate-900 uppercase">{{ $user->name }}</span>
                                                        <span class="text-[10px] text-slate-500 font-bold tracking-wider mt-1">{{ $user->email }}</span>
                                                    </div>
                                                </div>
                                                <form action="{{ route('settings.removeUser', [$role, $user->id]) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-500 rounded-xl">
                                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeAddUserModal()"></div>
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 relative modal-enter">
        <div class="mb-6">
            <h3 id="modalRoleTitle" class="text-xl font-black text-slate-900 tracking-tight mb-1">Add User</h3>
            <p class="text-xs text-slate-500 font-medium">Grant specialized permissions to an existing user.</p>
        </div>

        <form id="addUserForm" method="POST">
            @csrf
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">Select Candidate</label>
                    <select name="user_id" class="form-select-custom w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ strtoupper($user->name) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-600/20 transition-all active:scale-95">
                        Confirm Assignment
                    </button>
                    <button type="button" onclick="closeAddUserModal()" class="w-full py-4 text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-slate-600 transition-colors">
                        Discard
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openAddUserModal(role) {
        const modal = document.getElementById('addUserModal');
        const title = document.getElementById('modalRoleTitle');
        const form = document.getElementById('addUserForm');

        title.textContent = 'Add New ' + role;
        form.action = '/settings/addUser/' + role.toLowerCase(); 
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeAddUserModal() {
        const modal = document.getElementById('addUserModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    
    // Smooth scroll for nav
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                top: window.pageYOffset - 150,
                behavior: 'smooth'
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const navLinks = document.querySelectorAll('nav a[href^="#"]');
        const sections = Array.from(navLinks).map(link => document.querySelector(link.getAttribute('href')));
        const offsetY = 100; // adjust for sticky nav

        const activeClass = "flex items-center gap-3 px-4 py-3 bg-white border border-slate-200 text-slate-900 rounded-xl font-bold shadow-sm transition-all hover:border-indigo-300 group";
        const inactiveClass = "flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-slate-900 hover:bg-white rounded-xl font-bold transition-all group";

        function updateActiveLink() {
            const scrollPosition = window.scrollY + offsetY + 5; // small buffer

            sections.forEach((section, index) => {
                if (!section) return;

                const top = section.offsetTop;
                const bottom = top + section.offsetHeight;

                if (scrollPosition >= top && scrollPosition < bottom) {
                    navLinks[index].className = activeClass;
                } else {
                    navLinks[index].className = inactiveClass;
                }
            });
        }

        window.addEventListener('scroll', updateActiveLink);
        updateActiveLink(); // initialize on load
    });
</script>
@endpush