@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-8 py-10 antialiased">

     <!-- Top Bar: Contextual Navigation -->
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-6">
        <div>
            <nav class="flex items-center space-x-2 text-[11px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">
                <span class="text-blue-600">Dashboard</span>
                <span class="text-gray-300">/</span>
                <span>External Requests Accommodation</span>
            </nav>
            <h1 class="text-3xl font-light text-gray-900 tracking-tight">Requests <span class="font-semibold">Snapshot</span></h1>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user()->canReceiveRequests())
                <a href="{{ route('externals.create') }} " class="px-5 py-2 text-xs font-bold text-white bg-blue-600 rounded-full shadow-lg shadow-blue-500/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                    + Initiate New External Request
                </a>
            @endif
        </div>
    </header>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        @if(auth()->user()->canForwardRequests())
            <div class="relative group p-8 bg-white border border-gray-100 rounded-3xl overflow-hidden transition-all hover:border-blue-200">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">For Recording</p>
                <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ $exstats['pending'] ?? 0 }}</h3>
                <div class="mt-4 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <a href="{{ route('externals.recording')}}" class="text-[10px] font-bold text-blue-500 uppercase">Records Office</a>
                </div>
            </div>
        @endif
        
        @if(auth()->user()->canEndorseRequests())
            <div class="p-8 bg-white border border-gray-100 rounded-3xl transition-all hover:border-purple-200">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">For Division Assignment</p>
                <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ $exstats['forwarded'] ?? 0 }}</h3>
                <div class="mt-4 flex items-center gap-1.5">
                    <a href="{{ route('externals.endorsing')}}" class="text-[10px] font-bold text-purple-500 uppercase">Office of the Regional Director</a>
                </div>
            </div>
        @endif

        @if(auth()->user()->isDirector() || auth()->user()->isChiefAFD())
            <div class="p-8 bg-white border border-gray-100 rounded-3xl transition-all hover:border-amber-200">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Endorsed to AFD</p>
                <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ $exstats['endorsedAFD'] ?? 0 }}</h3>
                <div class="mt-4 flex items-center gap-1.5">
                    @if(auth()->user()->isChiefAFD())
                        <a href="{{ route('externals.mytasks') }}" class="text-[10px] font-bold text-amber-500 uppercase">For your action</a>
                    @else
                        <span class="text-[10px] font-bold text-amber-500 uppercase">For personnel assignment</span>
                    @endif
                </div>
            </div>
        @endif

        @if(auth()->user()->isDirector() || auth()->user()->isChiefTOD())
            <div class="p-8 bg-white border border-gray-100 rounded-3xl transition-all hover:border-amber-200">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Endorsed to TOD</p>
                <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ $exstats['endorsedTOD'] ?? 0 }}</h3>
                <div class="mt-4 flex items-center gap-1.5">
                    @if(auth()->user()->isChiefTOD())
                        <a href="{{ route('externals.mytasks') }}" class="text-[10px] font-bold text-amber-500 uppercase">For your action</a>
                    @else
                        <span class="text-[10px] font-bold text-amber-500 uppercase">For personnel assignment</span>
                    @endif
                </div>
            </div>
        @endif

        @if(auth()->user()->canMonitorRequests())
            <div class="p-8 bg-white border border-gray-100 rounded-3xl transition-all hover:border-green-200">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Active Requests</p>
                <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ count($activeRequests) }}</h3>
                <div class="mt-4 flex items-center gap-1.5">
                    <a href="{{ route('externals.monitoring') }}" class="text-[10px] font-bold text-green-500 uppercase">Being Handled</a>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Side (Active Requests Feed): Spans 8 or 9 columns for readability -->
        <div class="col-span-12 space-y-6">
            <div class="flex items-center justify-between px-2">
                <div>
                    <h2 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em]">Active Requests</h2>
                    <span class="text-[10px] font-bold text-red-500 uppercase">Requests being handled based on your monitoring scope.</span>
                </div>
                <a href="{{ route('externals.monitoring') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 uppercase transition-colors">
                    View All Tasks
                </a>
            </div>

            <div class="space-y-3">
                @forelse($activeRequests as $external)
                    <div class="group bg-white border border-gray-100 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-0.5">
                        <div class="flex items-center gap-5">
                            <div class="min-w-0">
                                <div class="text-sm font-black text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors"> 
                                    @if($external->priority === 'urgent')
                                        <span class="text-[9px] font-black text-rose-600 uppercase tracking-tighter bg-rose-50 px-0 py-0.5 rounded">Urgent</span>
                                    @elseif($external->priority === 'high')
                                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-tighter bg-amber-50 px-0 py-0.5 rounded">High Priority</span>
                                    @endif
                                    {{ $external->subject }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-gray-700 uppercase">{{ $external->reference }}</span>
                                    <span class="text-[10px] text-gray-400">Updated {{ $external->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-6">
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-gray-400 uppercase leading-none mb-1">Due</p>

                                <p class="text-[10px] font-black uppercase
                                    {{ $external->target_date && $external->target_date->isPast()
                                        ? 'text-red-600'
                                        : 'text-gray-900' }}">
                                    
                                    {{ $external->target_date
                                        ? $external->target_date->diffForHumans()
                                        : 'No deadline' }}
                                </p>
                            </div>
                            <a href="{{ route('externals.monitoring.show', $external->id) }}" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.15em] rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-100 transition-all">
                                Review <i class="fas fa-chevron-right ml-2 text-[8px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50/50 border-2 border-dashed border-gray-100 rounded-3xl p-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-sm mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest max-w-[200px] mx-auto leading-relaxed">
                            No active requests need your attention.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Top Bar: Contextual Navigation -->
    <header class="flex flex-col md:flex-row md:items-center justify-between mt-12 mb-4 gap-6">
        <div>
            <nav class="flex items-center space-x-2 text-[11px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">
                <span class="text-blue-600">Dashboard</span>
                <span class="text-gray-300">/</span>
                <span>Internal Routing</span>
            </nav>
            <h1 class="text-3xl font-light text-gray-900 tracking-tight">Routing <span class="font-semibold">Snapshot</span></h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('documents.create') }}" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 rounded-full shadow-lg shadow-blue-500/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                + Initiate New Internal Routing
            </a>
        </div>
    </header>

    <!-- Key Metrics: Minimalist Glass Design -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="relative group p-8 bg-white border border-gray-100 rounded-3xl overflow-hidden transition-all hover:border-blue-200">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Received</p>
            <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ $stats['forSigning'] ?? 0 }}</h3>
            <div class="mt-4 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                <a href="{{ route('documents.forsigning')}}" class="text-[10px] font-bold text-blue-500 uppercase">For Signing</a>
            </div>
        </div>
        
        <div class="p-8 bg-white border border-gray-100 rounded-3xl transition-all hover:border-amber-200">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">In Transit</p>
            <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ $stats['inProgress'] ?? 0 }}</h3>
            <div class="mt-4 flex items-center gap-1.5">
                <span class="text-[10px] font-bold text-amber-500 uppercase">Pending</span>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="p-8 bg-white border border-gray-100 rounded-3xl transition-all hover:border-red-200">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Returned</p>
            <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ $stats['forRevision'] ?? 0 }}</h3>
            <div class="mt-4 flex items-center gap-1.5">
                <span class="text-[10px] font-bold text-red-500 uppercase">For Revision</span>
            </div>
        </div>

        <div class="p-8 bg-white border border-gray-100 rounded-3xl transition-all hover:border-red-200">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Rejected</p>
            <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ $stats['rejected'] ?? 0 }}</h3>
            <div class="mt-4 flex items-center gap-1.5">
                <span class="text-[10px] font-bold text-red-500 uppercase">This month</span>
            </div>
        </div>

        <div class="p-8 bg-white border border-gray-100 rounded-3xl transition-all hover:border-green-200">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Completed</p>
            <h3 class="text-4xl font-bold text-gray-900 tracking-tighter">{{ $stats['completed'] ?? 0 }}</h3>
            <div class="mt-4 flex items-center gap-1.5">
                <span class="text-[10px] font-bold text-green-500 uppercase">This month</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side (Document Feed): Spans 8 or 9 columns for readability -->
        <div class="lg:col-span-8 xl:col-span-9 space-y-6">
            <div class="flex items-center justify-between px-2">
                <div>
                    <h2 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em]">Critical List</h2>
                    <span class="text-[10px] font-bold text-red-500 uppercase">Urgent and High Priority Documents</span>
                </div>
                <a href="{{ route('documents.forsigning') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 uppercase transition-colors">
                    View All Tasks
                </a>
            </div>

            <div class="space-y-3">
                @forelse($actionRequired as $doc)
                    <div class="group bg-white border border-gray-100 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-0.5">
                        <div class="flex items-center gap-5">
                            <div class="min-w-0">
                                <div class="text-sm font-black text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors"> 
                                    @if($doc->priority === 'urgent')
                                        <span class="text-[9px] font-black text-rose-600 uppercase tracking-tighter bg-rose-50 px-0 py-0.5 rounded">Urgent</span>
                                    @elseif($doc->priority === 'high')
                                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-tighter bg-amber-50 px-0 py-0.5 rounded">High</span>
                                    @endif
                                    {{ $doc->title }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-gray-700 uppercase">{{ $doc->owner->name ?? 'Admin' }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $doc->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-6">
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-gray-400 uppercase leading-none mb-1">Due</p>

                                <p class="text-[10px] font-black uppercase
                                    {{ $doc->target_date && $doc->target_date->isPast()
                                        ? 'text-red-600'
                                        : 'text-gray-900' }}">
                                    
                                    {{ $doc->target_date
                                        ? $doc->target_date->diffForHumans()
                                        : 'No deadline' }}
                                </p>
                            </div>
                            <a href="{{ route('documents.forsigning.sign', $doc->id) }}" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.15em] rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-100 transition-all">
                                Review <i class="fas fa-chevron-right ml-2 text-[8px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50/50 border-2 border-dashed border-gray-100 rounded-3xl p-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-sm mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest max-w-[200px] mx-auto leading-relaxed">
                            No urgent documents need your attention.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Side (Activity Audit): Spans 4 or 3 columns -->
        <div class="lg:col-span-4 xl:col-span-3">
            <div class="sticky top-6 bg-gray-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-blue-900/20">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-blue-400 mb-8 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.8)]"></span>
                    Activity Audit
                </h3>
                
                <div class="space-y-8">
                    @forelse($recentDocuments ?? [] as $doc)
                        <div class="relative pl-6 border-l border-gray-800 last:border-0 pb-2">
                            <!-- Dot on the line -->
                            <div class="absolute -left-[5px] top-0 w-2.5 h-2.5 rounded-full bg-gray-800 border-2 border-gray-900 group-hover:bg-blue-500 transition-colors"></div>
                            
                            <p class="text-[9px] text-gray-500 font-bold uppercase mb-1 tracking-wider">
                                {{ $doc->updated_at->diffForHumans() }}
                            </p>
                            <h5 class="text-[11px] font-bold text-gray-200 line-clamp-2 leading-snug mb-1">
                                {{ $doc->title }}
                            </h5>
                            <div class="inline-flex items-center px-2 py-0.5 rounded-md bg-white/5 border border-white/10">
                                <span class="text-[9px] font-bold text-blue-300 uppercase tracking-tighter">
                                    {{ str_replace('_', ' ', $doc->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-[10px] text-gray-500 uppercase font-bold italic">No logs found</p>
                        </div>
                    @endforelse
                </div>

                {{-- <button class="w-full mt-10 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-gray-300 hover:bg-white/10 hover:text-white transition-all active:scale-95">
                    View All Activity
                </button> --}}
            </div>
        </div>

    </div>
</div>
@endsection