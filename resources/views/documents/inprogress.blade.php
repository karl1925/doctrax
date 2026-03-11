@extends('layouts.app')

@section('title', 'In Transit')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-10 gap-6">
            <div class="space-y-2">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-slate-500 uppercase tracking-wider">
                        <li>Internal Routing</li>
                        <li><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></li>
                        <li class="text-indigo-600 font-bold">In Transit</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg shadow-lg shadow-blue-200">
                        <i class="fas fa-circle-notch fa-spin text-white"></i>
                    </div>
                    Routing in Progress
                </h1>
                <p class="text-slate-500 font-medium text-sm">
                    Tracking <span class="text-slate-900 font-bold">{{ $documents->count() }} {{ Str::plural('document', $documents->count()) }}</span> active document lifecycles.
                </p>
            </div>
        </div>

        @if($documents->isEmpty())
            <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-20 flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-hourglass-start text-3xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900">No active workflows</h3>
                <p class="text-slate-500 font-medium max-w-sm mt-2 leading-relaxed">
                    Documents you've sent for signature or that are currently being processed will appear here for real-time tracking.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-8">
                @foreach($documents as $document)
                <div class="group bg-white rounded-[.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden hover:border-blue-200 transition-all duration-300">
                    <div class="p-4">
                        <div class="grid grid-cols-2 xl:flex-row xl:items-center justify-between gap-8">
                            <!-- Document Branding & Main Info -->
                            <div class="flex items-start gap-5">
                                {{-- <div class="relative">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-100">
                                        <i class="fas fa-file-alt text-2xl"></i>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-white rounded-lg border border-slate-100 flex items-center justify-center shadow-sm">
                                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                    </div>
                                </div> --}}
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 tracking-tight mb-1">{{ $document->title }}</h3>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs font-bold text-slate-400">
                                        Last Update
                                        <span class="flex items-center gap-1.5 uppercase tracking-tighter">
                                            <i class="fas fa-clock text-slate-300"></i>
                                            {{ $document->updated_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    
                                    <!-- Participant Progress Avatars -->
                                    <div class="mt-5 flex items-center gap-4">
                                        <div class="flex -space-x-3 overflow-hidden">
                                            @foreach($document->participants as $recipient)
                                                @php
                                                    $user = \App\Models\User::find($recipient->user_id);
                                                    $approved = $document->participants->where('status', 'approved')->count();
                                                    $all = $document->participants->count();
                                                @endphp
                                                <div @class([
                                                    'relative inline-block h-10 w-10 rounded-xl ring-4 ring-white flex items-center justify-center text-[10px] font-black transition-transform hover:scale-110 hover:z-10',
                                                    'bg-green-100 text-green-700 border border-green-200' => $recipient->status === 'approved',
                                                    'bg-blue-100 text-blue-700 border border-blue-200' => $recipient->status === 'active',
                                                    'bg-slate-100 text-slate-500 border border-slate-200' => $recipient->status === 'queued',
                                                    ]) title="{{ $user->name }}: {{ ucfirst($recipient->status) }}">
                                                    <span class="font-black text-[12px] ">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                    @if($recipient->status === 'approved')
                                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                                            <i class="fas fa-check text-[6px] text-white"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            {{ $approved }} / {{ $all }} <span class="hidden sm:inline">Signed</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                             <!-- Progress Bar & Next Signer -->
                            <div class="flex-1 max-w-md bg-slate-50/80 p-6 rounded-3xl border border-slate-100">
                                <div class="flex justify-between items-end mb-3">
                                    <div class="space-y-0.5">
                                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Workflow Progress</span>
                                        <span class="text-2xl font-black text-slate-900 leading-none">{{ ($approved / $all) * 100 }}%</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Next Up</span>
                                        <span class="text-xs font-black text-blue-600 truncate max-w-[120px] block">
                                            {{ $document->currentReviewer() ?? 'Finalizing' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-3.5 p-1">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-1.5 rounded-full transition-all duration-1000 shadow-sm" style="width: {{ ($approved / $all) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="bg-slate-50/50 px-8 py-4 border-t border-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-6 gap-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Deadline</span>
                            <span @class([
                                'text-xs font-bold',
                                'text-rose-500' => $document->target_date && $document->target_date->isPast(),
                                'text-slate-700' => !$document->target_date || !$document->target_date->isPast(),
                            ])>
                                {{ $document->target_date ? $document->target_date->format('M d, Y') : 'Open Ended' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('documents.show', $document->id) }}" class="px-6 py-3 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
                <!-- Footer Info Bar -->
               
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection