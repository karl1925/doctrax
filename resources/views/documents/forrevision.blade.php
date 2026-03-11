@extends('layouts.app')

@section('title', 'For Revision')

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
                        <li class="text-indigo-600 font-bold">For Revision</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-amber-500 rounded-lg shadow-lg shadow-amber-200">
                        <i class="fa-solid fa-arrow-rotate-right text-white"></i>
                    </div>
                    Requires Updates
                </h1>
                <p class="text-slate-500 font-medium text-sm">
                    You have <span class="text-slate-900 font-bold">{{ $documents->count() }} {{ Str::plural('document', $documents->count()) }}</span> waiting for your review and action.
                </p>
            </div>
            
            {{-- <div class="flex items-center gap-3">
                <button class="flex items-center px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                    Sort by Date
                </button>
            </div> --}}
        </div>

        @if($documents->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 flex flex-col items-center text-center animate-in fade-in zoom-in duration-300">
                <div class="w-24 h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mb-6 ring-8 ring-green-50/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                    Workspace Clear!
                </h3>
                <p class="text-slate-500 font-medium max-w-sm mt-3">
                    No documents currently require revision. Enjoy your productive day!
                </p>
            </div>
        @else
            <div class="grid gap-8">
                @foreach($documents as $document)
                <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 overflow-hidden">
                    <!-- Accent Color Bar -->
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500"></div>

                    <div class="p-6 md:p-8">
                        <div class="flex flex-col lg:flex-row gap-8">
                            
                            <!-- Main Content -->
                            <div class="flex-1 space-y-5">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ $document->reference ?? 'Doc #' . $document->id }}</span>
                                    <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                                    <span class="flex items-center text-sm text-gray-500">
                                        <svg class="h-4 w-4 mr-1.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Returned {{ $document->updated_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div>
                                    <h3 class="text-2xl font-extrabold text-gray-900 group-hover:text-blue-600 transition-colors cursor-pointer mb-2">
                                        {{ $document->title }}
                                    </h3>
                                    <div class="flex items-center gap-3">
                                        <div class="flex -space-x-2 overflow-hidden">
                                            <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-blue-500 flex items-center justify-center text-[10px] text-white font-bold">
                                                {{ substr($document->rejected_by_name ?? 'U', 0, 1) }}
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Returned by <span class="font-bold text-gray-800">{{ $document->currentRevision() }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Feedback Card -->
                                <div class="relative bg-amber-50/50 rounded-xl p-5 border border-amber-100/50">
                                    <div class="absolute -top-3 left-4 bg-amber-100 text-amber-800 text-[10px] font-black uppercase px-2 py-0.5 rounded-md border border-amber-200">
                                        Revision Instructions
                                    </div>
                                    <p class="text-amber-900 font-medium italic leading-relaxed">
                                        @php
                                            $history = \App\Models\DocumentHistory::where('action', 'Returned for Revision')
                                                ->where('document_id', $document->id)
                                                ->latest()
                                                ->first();
                                        @endphp

                                        "{{ $history?->remarks ?? 'Please ensure all compliance fields are completed and accurate before resubmitting.' }}"
                                    </p>
                                </div>
                            </div>

                            <!-- Sidebar Stats & Quick Actions -->
                            <div class="lg:w-64 flex flex-col justify-between p-6 bg-gray-50 rounded-xl border border-gray-100 gap-4">
                                <div class="sticky top-8 space-y-6">
                                    <section>
                                        <h3 class="flex items-center gap-2 text-sm font-black text-slate-900 uppercase tracking-widest mb-4">
                                            <i data-lucide="history" class="w-4 h-4 text-blue-500"></i>
                                            Routing History
                                        </h3>
                                        @forelse($document->history as $history)
                                            <div class="relative">
                                                {{-- Dynamic Status Dot Color --}}
                                                @php
                                                    $dotColor = match(strtolower($history->action)) {
                                                        'signed', 'approved', 'completed' => 'bg-green-500',
                                                        'forwarded', 'sent', 'shared' => 'bg-blue-500',
                                                        'rejected', 'cancelled' => 'bg-red-500',
                                                        default => 'bg-slate-400',
                                                    };
                                                @endphp
                                                
                                                <div class="absolute -left-[20px] top-1 w-2 h-2 rounded-full {{ $dotColor }} ring-4 ring-white"></div>
                                                
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-slate-900">
                                                        {{ ucwords($history->action) }} 
                                                        @if($history->user)
                                                            by {{ $history->user->name }}
                                                        @endif
                                                    </span>
                                                    
                                                    <span class="text-[10px] text-slate-400 mt-0.5">
                                                        {{ $history->created_at->diffForHumans() }} 
                                                        ({{ $history->created_at->format('M d, Y \a\t h:i A') }})
                                                    </span>

                                                    @if($history->remarks)
                                                        <p class="text-[11px] text-slate-500 mt-2 bg-slate-50 p-2 rounded-lg border border-slate-100 italic">
                                                            "{{ $history->remarks }}"
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="relative">
                                                <div class="absolute -left-[20px] top-1 w-2 h-2 rounded-full bg-slate-200 ring-4 ring-white"></div>
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-slate-400 uppercase italic">No history recorded</span>
                                                </div>
                                            </div>
                                        @endforelse
                                    </section>

                                </div>

                                <div class="space-y-2">
                                    <a href="{{ route('documents.edit', $document->id) }}" class="group/btn w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md shadow-blue-200 active:scale-95">
                                        <span>Edit & Resubmit</span>
                                        <svg class="h-4 w-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection