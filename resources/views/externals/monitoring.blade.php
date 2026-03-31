@extends('layouts.app')

@section('title', 'Active Requests')

@section('content')
<div class="pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-10 gap-6">
            <div class="space-y-2">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-slate-500 uppercase tracking-wider">
                        <li>EXTERNAL</li>
                        <li><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></li>
                        <li class="text-indigo-600 font-bold">MONITORING</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-green-500 rounded-lg shadow-lg shadow-gray-200">
                        <i class="fa-solid fa-chart-line text-stale-400 dark    :text-white"></i>
                    </div>
                    Active Accommodated Requests
                </h1>
                <p class="text-slate-500 font-medium text-sm">
                    @if($search == '' && $priority == '')
                        There {{ $externals->count() > 1 ? 'are' : 'is' }} <span class="text-slate-900 dark:text-white font-bold">{{ $externals->count() }} {{ Str::plural('request', $externals->count()) }}</span> currently being accommodated.
                    @else
                        There {{ $externals->count() > 1 ? 'are' : 'is' }} <span class="text-slate-900 dark:text-white font-bold">{{ $externals->count() }} {{ Str::plural('request', $externals->count()) }}</span> matching your filters.
                    @endif
                </p>
            </div>
        </div>
        @include('partials.external-filters', ['action' => route('externals.monitoring')])
        <div class="space-y-4">
            @forelse($externals as $external)
                @include('components.external-card', ['external' => $external])
            @empty
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 p-20 flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mb-6 ring-8 ring-green-50/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                        @if($search == '' && $priority == '')
                            Workspace Clear!
                        @else
                            Try different search or filter.
                        @endif
                    </h3>
                    <p class="text-slate-500 font-medium max-w-sm mt-3">
                        @if($search == '' && $priority == '')
                            There are no active external requests being accommodated at the moment. Thank you for staying on top of things!
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

@if($externals->count())
    <!-- Follow-up Modal -->
    <div id="followup-modal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold mb-4">Add Follow-up Remarks</h3>
            <form method="POST" id="followup-form">
                @csrf
                <textarea name="remarks" rows="4" placeholder="Enter your remarks..."
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-indigo-200 focus:border-indigo-400 resize-none mb-4"></textarea>
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            class="px-4 py-2 bg-gray-200 text-slate-700 rounded-xl font-bold hover:bg-gray-300 transition"
                            onclick="closeFollowUpModal()">Cancel</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                        Follow-up
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
@push('scripts')
<script>
    function openFollowUpModal(id) {
        document.getElementById(`followup-form`).action = `/externals/${id}/followup`;
        document.getElementById(`followup-modal`).classList.remove('hidden');
        document.getElementById(`followup-modal`).classList.add('flex');
    }

    function closeFollowUpModal() {
        document.getElementById(`followup-modal`).classList.add('hidden');
        document.getElementById(`followup-modal`).classList.remove('flex');
    }

    el = document.querySelector('.bg-slate-50\\/50');
    el.classList.remove('bg-slate-50/50');
    el.classList.remove('border-t');
</script>
@endpush
@endsection