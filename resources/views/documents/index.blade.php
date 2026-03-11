@extends('layouts.app')

@section('title', 'Document Directory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Document Directory
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('documents.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Document
            </a>
        </div>
    </div>

    <!-- Filter Navigation -->
    <div class="mb-8 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto pb-1" aria-label="Tabs">
            @php
                $navItems = [
                    ['name' => 'For Signing', 'route' => 'forSigning'],
                    ['name' => 'Signing History', 'route' => 'signingHistory'],
                    ['name' => 'In Progress', 'route' => 'inProgress'],
                    ['name' => 'For Revision', 'route' => 'forRevision'],
                    ['name' => 'Completed', 'route' => 'completed'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp
                <a href="{{ route($item['route']) }}" 
                   class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors
                   {{ $isActive 
                      ? 'border-indigo-500 text-indigo-600' 
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Documents Table -->
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Document Details
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Owner
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Step Progress
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($documents as $doc)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $doc->title }}</div>
                                                <div class="text-xs text-gray-500">Updated {{ $doc->updated_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $doc->user->name ?? 'System' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusMap = [
                                                'forSigning' => ['label' => 'Awaiting Signature', 'color' => 'bg-amber-100 text-amber-800'],
                                                'completed'  => ['label' => 'Completed', 'color' => 'bg-emerald-100 text-emerald-800'],
                                                'forRevision'=> ['label' => 'Revision Needed', 'color' => 'bg-red-100 text-red-800'],
                                                'inProgress' => ['label' => 'Processing', 'color' => 'bg-blue-100 text-blue-800'],
                                            ];
                                            $badge = $statusMap[$doc->status] ?? ['label' => ucfirst($doc->status), 'color' => 'bg-gray-100 text-gray-800'];
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge['color'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col w-full max-w-xs">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-semibold text-indigo-600">Step {{ $doc->current_step }} of {{ $doc->total_steps }}</span>
                                            </div>
                                            <div class="overflow-hidden h-2 text-xs flex rounded bg-indigo-100">
                                                @php $width = ($doc->total_steps > 0) ? ($doc->current_step / $doc->total_steps) * 100 : 0; @endphp
                                                <div style="width:{{ $width }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500 transition-all duration-500"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('documents.show', $doc->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-400">
                                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="mt-2 block text-sm font-medium text-gray-600">No documents found in this category.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection