@extends('layouts.app')

@section('title', 'Partner Agencies Directory')

@section('content')
    <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-4 gap-6">
        <div class="space-y-2">
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-xs font-medium text-slate-500 uppercase tracking-wider">
                    <li>SETTINGS</li>
                    <li><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></li>
                    <li class="text-indigo-600 font-bold">PARTNERS</li>
                </ol>
            </nav>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                <div class="p-2 bg-stone-500 rounded-lg shadow-lg shadow-gray-200">
                    <i class="fa-solid fa-handshake text-white"></i>
                </div>
                Partner Agencies Directory
            </h1>
            <p class="text-slate-500 font-medium text-sm">
                Displaying {{ $partners->total() }} team members.
            </p>
        </div>
    </div>

    <form method="GET" action="{{ route('settings.partners') }}" class="flex mb-2 w-full ml-auto max-w-md">
        <div class="relative w-full">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search name or email…"
                class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            >
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        </div>
    </form>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        @foreach(['name' => 'Agency', 'type' => 'Type', 'email' => 'Email', 'contactNo' => 'Contact'] as $key => $label)
                        <th class="px-6 py-4">
                            <a href="{{ $label === 'Status' ? '#' : request()->fullUrlWithQuery(['sort' => $key, 'direction' => $sortField === $key && $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors">
                                {{ $label }}
                                @if($sortField === $key)
                                    <svg class="w-3 h-3 {{ $sortOrder === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @endif
                            </a>
                        </th>
                        @endforeach
                        <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($partners as $partner)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img
                                    src="{{ Avatar::create($partner->name)->toBase64()  }}"
                                    alt="{{ $partner->name }}"
                                    class="w-10 h-10 rounded-full ring-2 ring-indigo-100"
                                >
                                <div class="flex flex-col leading-tight">
                                    <span class="text-sm font-bold text-slate-900 uppercase">{{ $partner->name }}</span>
                                    <span class="text-[10px] text-slate-500 font-bold tracking-wider mt-1">{{ $partner->code }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-700 uppercase">{{ $partner->type }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-700 uppercase">{{ $partner->email ?? '' }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-700 uppercase">{{ $partner->contactNo ?? '' }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('settings.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Archive this partner agency?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 bg-white border border-slate-200 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 text-sm italic">No partners found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
            {{ $partners->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openCreateModal() {
        console.log('Open slide-over for CRUD');
    }
</script>
@endpush