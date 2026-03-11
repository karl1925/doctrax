@extends('layouts.app')

@section('title', 'Rejected Documents')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-10 gap-6">
            <div class="space-y-2">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-slate-500 uppercase tracking-wider">
                        <li>INTERNAL</li>
                        <li><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></li>
                        <li class="text-indigo-600 font-bold">Rejected</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-red-500 rounded-lg shadow-lg shadow-red-200">
                        <i class="fa-solid fa-circle-xmark text-white"></i>
                    </div>
                    Failed Review
                </h1>
                <p class="text-slate-500 font-medium text-sm">
                    You have <span class="text-slate-900 font-bold">{{ $documents->count() }} rejected {{ Str::plural('document', $documents->count()) }}</span>.
                </p>
            </div>
        </div>


        <!-- Rejection List -->
        <div class="space-y-4">
            @forelse($documents as $item)
            @php
                $lastHist = \App\Models\DocumentHistory::where('document_id', $item->id)->latest()->first();
                $rejectedBy = $lastHist ? \App\Models\User::find($lastHist->user_id) : null;
            @endphp
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:border-red-200 transition-all group">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-start space-x-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-red-600 transition-colors">{{ $item->title }}</h3>
                            <p class="text-sm text-slate-500">
                                <span class="font-mono text-xs">{{ $item->reference }}</span><br>
                                Rejected by: <span class="font-bold text-sm">{{ $rejectedBy->name ?? 'Unknown' }}</span> on <i>{{ $lastHist ? $lastHist->created_at->format('M d, Y \a\t h:i A') : 'N/A' }}</i>
                            </p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <!-- Rejection Note -->
                        <div class="mt-1 bg-red-50 border border-red-100 rounded-lg p-3">
                            <div class="flex items-center text-red-800 text-xs font-bold uppercase tracking-wider mb-1">
                                <svg class="h-3.5 w-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                Reviewer Feedback
                            </div>
                            <p class="text-sm text-red-700 leading-relaxed italic">
                                "{{ $lastHist->remarks ?? 'Reason not provided.' }}"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 p-20 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mb-6 ring-8 ring-green-50/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                    All Clear!
                </h3>
                <p class="text-slate-500 font-medium max-w-sm mt-3">
                    You have no rejected documents to address
                </p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection