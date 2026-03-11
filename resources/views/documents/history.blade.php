@extends('layouts.app')

@section('title', 'Signing History')

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
                        <li class="text-indigo-600 font-bold">Signed</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-fuchsia-500 rounded-lg shadow-lg shadow-fuchsia-200">
                        <i class="fa-solid fa-history text-white"></i>
                    </div>
                    Signing History
                </h1>
                <p class="text-slate-500 font-medium text-sm">
                    A list of documents you have digitally signed.
                </p>
            </div>
        </div>

        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overall Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Your Signature Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($documents as $document)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $document->title }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $document->reference }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $document->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $document->status === 'approved' ? 'Completed' : 'In Progress' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $document->updated_at->format('M d, Y - h:i A') ?? 'N/A' }}
                        </td>
                        <td class="text-right">
                            <a href="{{ route('documents.show', $document->id) }}" class="whitespace-nowrap px-6 py-3 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 transition-colors">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-gray-400 mb-2">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                                You haven't signed any documents yet.
                            </h3>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>


@endsection