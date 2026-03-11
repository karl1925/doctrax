@extends('layouts.app')

@section('title', 'For Signing')

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
                        <li class="text-indigo-600 font-bold">For Signing</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-sky-500 rounded-lg shadow-lg shadow-sky-200">
                        <i class="fa-solid fa-pen-nib text-white"></i>
                    </div>
                    Awaiting Signature
                </h1>
                <p class="text-slate-500 font-medium text-sm">
                    You have <span class="text-slate-900 font-bold">{{ $documents->count() }} {{ Str::plural('document', $documents->count()) }}</span> 
                    {{ request('priority') ? 'filtered by "' . ucfirst(request('priority')) . '"' : 'waiting for your review and action.' }}.
                </p>
            </div>

            <!-- Modern Pill Filters -->
            <div class="flex items-center p-1.5 bg-white rounded-2xl border border-slate-200 shadow-sm self-start lg:self-center overflow-x-auto max-w-full">
                <a href="{{ request()->fullUrlWithQuery(['priority' => null]) }}" 
                   class="whitespace-nowrap px-5 py-2 text-[11px] font-black uppercase tracking-wider rounded-xl transition-all {{ !request('priority') ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">
                    All
                </a>
                <a href="{{ request()->fullUrlWithQuery(['priority' => 'urgent']) }}" 
                   class="whitespace-nowrap px-5 py-2 text-[11px] font-black uppercase tracking-wider rounded-xl transition-all {{ request('priority') === 'urgent' ? 'bg-rose-500 text-white shadow-lg shadow-rose-100' : 'text-slate-400 hover:text-rose-500' }}">
                    Urgent
                </a>
                <a href="{{ request()->fullUrlWithQuery(['priority' => 'high']) }}" 
                   class="whitespace-nowrap px-5 py-2 text-[11px] font-black uppercase tracking-wider rounded-xl transition-all {{ request('priority') === 'high' ? 'bg-amber-500 text-white shadow-lg shadow-amber-100' : 'text-slate-400 hover:text-amber-500' }}">
                    High
                </a>
                <a href="{{ request()->fullUrlWithQuery(['priority' => 'normal']) }}" 
                   class="whitespace-nowrap px-5 py-2 text-[11px] font-black uppercase tracking-wider rounded-xl transition-all {{ request('priority') === 'normal' ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-100' : 'text-slate-400 hover:text-indigo-500' }}">
                    Normal
                </a>
            </div>
        </div>

        @if($documents->isEmpty())
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 p-20 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mb-6 ring-8 ring-green-50/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                    {{ request('priority') ? 'No ' . request('priority') . ' documents found' : 'Perfectly Clear!' }}
                </h3>
                <p class="text-slate-500 font-medium max-w-sm mt-3">
                    {{ request('priority') 
                        ? 'Try adjusting your filters to find what you are looking for.' 
                        : "You've handled all pending requests. Sit back and relax!" }}
                </p>
                @if(request('priority'))
                    <a href="{{ request()->fullUrlWithQuery(['priority' => null]) }}" class="mt-8 px-8 py-3 bg-slate-900 text-white rounded-xl text-[11px] font-black uppercase tracking-widest hover:shadow-lg transition-all">
                        View All Documents
                    </a>
                @endif
            </div>
        @else
            <!-- Responsive Desktop Table -->
            <div class="hidden md:block bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Details</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Received</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($documents as $document)
                        <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div>
                                    <div class="text-sm font-black text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors"> 
                                        @if($document->priority === 'urgent')
                                            <span class="text-[9px] font-black text-rose-600 uppercase tracking-tighter bg-rose-50 px-0 py-0.5 rounded">Urgent</span>
                                        @elseif($document->priority === 'high')
                                            <span class="text-[9px] font-black text-amber-600 uppercase tracking-tighter bg-amber-50 px-0 py-0.5 rounded">High</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm font-black text-slate-700">{{ $document->title }}
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-100 text-slate-500">
                                        <span class="text-xs font-black uppercase tracking-tight"><i class="fas fa-paperclip text-[10px]"></i>{{ $document->attachments->count() }}</span>
                                    </div>
                                </div>
                                <span class="text-xs text-slate-400 font-bold italic uppercase tracking-tight mt-0.5">{{ $document->reference }}</span>
                                
                                <div class="text-xs text-slate-400 font-bold uppercase tracking-tight mt-0.5">{{ $document->owner->name }}</div>
                                
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">{{ $document->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('documents.forsigning.sign', $document->id) }}" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl">
                                    Review
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $documents->links() }}
                </div>
            </div>
            

            <!-- Mobile Card Layout -->
            <div class="md:hidden space-y-4">
                @foreach($documents as $document)
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="text-sm font-black text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors"> 
                                @if($document->priority === 'urgent')
                                    <span class="text-[9px] font-black text-rose-600 uppercase tracking-tighter bg-rose-50 px-0 py-0.5 rounded">Urgent</span>
                                @elseif($document->priority === 'high')
                                    <span class="text-[9px] font-black text-amber-600 uppercase tracking-tighter bg-amber-50 px-0 py-0.5 rounded">High</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right text-[10px] font-black text-slate-400 uppercase tracking-tight">
                            {{ $document->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <h4 class="text-lg font-black text-slate-800 leading-tight mb-1">{{ $document->title }}</h4>
                    <p class="text-xs text-slate-500 font-bold mb-4">From: {{ $document->owner->name }}</p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <div class="inline-flex items-center gap-1.5 text-slate-400">
                            <i class="fas fa-paperclip text-[10px]"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">{{ $document->attachments->count() }} Files</span>
                        </div>
                        <a href="{{ route('documents.forsigning.sign', $document->id) }}" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl">
                            Review
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection