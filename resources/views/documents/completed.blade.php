@extends('layouts.app')

@section('title', 'Completed Documents')

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
                        <li class="text-indigo-600 font-bold">Finalized</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-green-500 rounded-lg shadow-lg shadow-green-200">
                        <i class="fa-solid fa-check-circle text-white"></i>
                    </div>
                    Completed Workflows
                </h1>
                <p class="text-slate-500 font-medium text-sm">
                    Access and download your finalized, legally binding documents.
                </p>
            </div>
        </div>
        
        @if($documents->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-[2rem] border-2 border-dashed border-slate-200 p-20 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                    No completed documents 
                </h3>
                <p class="text-slate-500 font-medium max-w-sm mt-3">
                    Once documents are fully signed by all parties, they will be securely listed here for your records.
                </p>
            </div>
        @else
            <!-- Table Container -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Document Details</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest hidden md:table-cell">Signatories</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest hidden md:table-cell">Completion Date</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($documents as $document)
                            <tr class="group hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                                {{ $document->title }} 
                                                <i class="fas fa-paperclip text-[10px]"></i> {{ $document->attachments->count() }}</div>
                                                <div class="text-xs text-slate-400 mt-0.5">{{ $document->reference }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 hidden md:table-cell">
                                    <div class="flex -space-x-2 overflow-hidden">
                                        @for($i = 0; $i < ($document->participants->count() ?? 3); $i++)
                                        @php
                                            $user = \App\Models\User::find($document->participants[$i]->user_id);
                                        @endphp
                                            <div class="inline-block h-7 w-7 rounded-full ring-2 ring-white bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500"
                                                title="{{ $user->name }}">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endfor
                                        @if(($document->participants->count() ?? 3) > 3)
                                            <div class="inline-block h-7 w-7 rounded-full ring-2 ring-white bg-indigo-50 flex items-center justify-center text-[10px] font-bold text-indigo-600">
                                                +{{ ($document->participants->count() ?? 3) - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 hidden md:table-cell">
                                    <div class="text-sm text-slate-600 font-medium">
                                        {{ $document->updated_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ $document->updated_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right whitespace-nowrap">
                                    <a href="{{ route('documents.show', $document->id) }}" class="whitespace-nowrap px-6 py-3 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 transition-colors">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $documents->links() }}
                </div>
            </div>
        @endif
    </div>  
</div>  
@endsection