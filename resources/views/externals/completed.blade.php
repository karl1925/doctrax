@extends('layouts.app')

@section('title', 'Completed Requests')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-10 gap-6">
            {{-- Left Section --}}
            <div class="space-y-2">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-slate-500 uppercase tracking-wider">
                        <li>EXTERNAL</li>
                        <li>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </li>
                        <li class="text-indigo-600 font-bold">COMPLETED</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg shadow-lg shadow-gray-200">
                        <i class="fa-solid fa-check-double text-white"></i>
                    </div>
                    Completed Requests
                </h1>
                <p class="text-slate-500 font-medium text-sm">
                    @if($search == '' && $priority == '')
                        There {{ $externals->count() > 1 ? 'are' : 'is' }} <span class="text-slate-900 font-bold">{{ $externals->count() }} delivered {{ Str::plural('request', $externals->count()) }}</span>.
                    @else
                        There {{ $externals->count() > 1 ? 'are' : 'is' }} <span class="text-slate-900 font-bold">{{ $externals->count() }} {{ Str::plural('request', $externals->count()) }}</span> matching your filters.
                    @endif
                </p>
            </div>

            {{-- Right Section: View Archive Button --}}
            <div class="flex-shrink-0">
                <a href="{{ route('externals.completed.archive') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-700 hover:text-white hover:border-slate-700 transition-all shadow-sm shadow-slate-200/5 active:scale-95">
                    <i class="fa-solid fa-box-archive text-sm"></i>
                    <span class="text-xs font-bold">View Archive</span>
                </a>
            </div>
        </div>
        @include('partials.external-filters', ['action' => route('externals.completed')])
        <div class="space-y-4">
            @forelse($externals as $external)
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:border-gray-200 transition-all group">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center space-x-4">
                            <div>
                                @php 
                                    $stat = "";
                                    $color = "";
                                    switch($external->status) {
                                        case "pending": $stat = 'For Assignment'; $color = "rose"; break;
                                        case "assigned": $stat = 'Pending Acceptance'; $color = "red"; break;
                                        case "accepted": $stat = 'Accepted'; $color = "green"; break;
                                    }
                                @endphp
                                <span class="text-[12px] font-black text-{{ $color }}-600 uppercase tracking-tighter bg-{{ $color }}-50 px-0 py-0 rounded">{{ $stat }}</span>
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-gray-600 transition-colors">
                                    @if($external->priority === 'urgent')
                                        <span class="text-[9px] font-black text-rose-600 uppercase tracking-tighter bg-rose-50 px-0 py-0.5 rounded">Urgent</span>
                                    @elseif($external->priority === 'high priority')
                                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-tighter bg-amber-50 px-0 py-0.5 rounded">High</span>
                                    @endif
                                    {{ $external->subject }}
                                </h3>
                                <p class="text-sm text-slate-500">
                                    <span class="font-normal text-xs">{!! $external->reference ? $external->reference . '<br>' : '' !!}</span>
                                    <span class="font-normal text-xs">Requesting Agency: </span><span class="font-bold text-xs">{!! $external->agency . '<br>' !!}</span>
                                    <span class="font-normal text-xs">Point of Contact: </span><span class="font-bold text-xs">{!! $external->contact . '<br>' !!}</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            @if(count($external->histories))
                                <div class="pb-3">
                                    <span class="font-bold text-xs">{{ $external->history->action ?? 'N/A' }}<br><span class="font-normal">by {{ $external->history->user->name ?? 'N/A' }}<span>
                                    <span class="italic font-normal">{{ $external->histories->last()?->created_at?->diffForHumans() ?? '' }}</span></span><br>
                                </div>
                            @endif
                            <a href="{{ route('externals.completed.show', $external->id) }}" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl">
                                Review
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 p-20 flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                        @if($search == '' && $priority == '')
                            No completed requests yet. 
                        @else
                            No completed requests matching your search criteria.
                        @endif
                    </h3>
                    <p class="text-slate-500 font-medium max-w-sm mt-3">
                        @if($search == '' && $priority == '')
                            Once requests are marked completed, they will be securely listed here for your records.
                        @endif
                    </p>
                </div>
            @endforelse
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $externals->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection