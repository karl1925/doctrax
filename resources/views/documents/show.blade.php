@extends('layouts.app')

@section('title', 'Routing Details')

@section('content')
<div class="container mx-auto px-4 py-2 max-w-5xl">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            
            <div class="flex items-center gap-2 mb-2 text-[9px] uppercase">
                <a href="{{ url()->previous() }}" class="text-lg font-bold text-gray-600">
                    ←
                </a>
                Priority
                @if($document->priority === 'urgent')
                    <span class="text-[9px] font-black text-rose-600 uppercase tracking-tighter bg-rose-50 px-1.5 py-0.5 rounded">Urgent</span>
                @elseif($document->priority === 'high')
                    <span class="text-[9px] font-black text-amber-600 uppercase tracking-tighter bg-amber-50 px-1.5 py-0.5 rounded">High</span>
                @endif
                {{-- <span class="text-slate-400 text-sm">#DOC-2024-0892</span> --}}
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $document->title }}</h1>
            <p class="text-slate-500 font-medium text-sm">
                {{ $document->reference}}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Workflow Column -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Section 1: Document Details -->
            <section>
                <h3 class="flex items-center gap-2 text-sm font-black text-slate-900 uppercase tracking-widest mb-4">
                    <i class="fa-solid fa-circle-info"></i>
                    Routing Details
                </h3>
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Originator</label>
                            <div class="flex items-center gap-2">
                                {{-- <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold">JD</div> --}}
                                <span class="text-sm font-semibold text-slate-700">{{ $document->owner->name }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Deadline</label>
                            <span class="text-sm font-semibold text-slate-700">{{ $document->target_date ? $document->target_date->diffForHumans() : 'No deadline' }}</span>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Executive Summary</label>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $document->remarks }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2: Signing Order -->
            <section>
                <h3 class="flex items-center gap-2 text-sm font-black text-slate-900 uppercase tracking-widest mb-4">
                    <i class="fa-solid fa-arrow-down-1-9"></i>
                    Signing Order
                </h3>
                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="divide-y divide-slate-100">
                        @foreach($document->participants as $index => $participant)
                            @php
                                //$table->enum('status', ['active', 'queued', 'approved', 'rejected', 'revision'])->default('queued');
                                $isCurrentUser = auth()->id() === $participant->user_id;
                                $isSigned = $participant->status === 'approved';
                                // Logic for "Active" usually means not signed yet, but it's their turn
                                $isActive = $participant->status === 'active' && ($index === 0 || $document->participants[$index - 1]->status === 'approved');
                                $isPending = !$isSigned && !$isActive;
                            @endphp

                            <div @class([
                                'p-4 flex items-center justify-between transition-colors',
                                'bg-slate-50/50' => $isSigned,
                                'border-l-4 border-l-blue-500 bg-blue-50/20' => $isActive,
                                'opacity-60' => $isPending
                            ])>
                                <div class="flex items-center gap-4">
                                    <!-- Status Icon / Number -->
                                    <div @class([
                                        'w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all',
                                        'bg-green-500 text-white' => $isSigned,
                                        'bg-blue-600 text-white shadow-lg shadow-blue-200' => $isActive,
                                        'bg-slate-200 text-slate-500' => $isPending,
                                    ])>
                                        @if($isSigned)
                                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>

                                    <div>
                                        <p @class([
                                            'text-sm font-bold leading-none',
                                            'text-slate-900' => !$isPending,
                                            'text-slate-500' => $isPending
                                        ])>
                                            {{ $isCurrentUser ? 'You (' . $participant->user->name . ')' : $participant->user->name }}
                                        </p>
                                        
                                        @if($isSigned)
                                            <p class="text-[11px] text-green-600 mt-1 font-medium">
                                                Signed {{ $participant->signed_at ? $participant->signed_at->diffForHumans() : '' }}
                                            </p>
                                        @elseif($isActive)
                                            <p class="text-[11px] text-blue-600 mt-1 font-bold animate-pulse">
                                                Awaiting Action
                                            </p>
                                        @else
                                            <p class="text-[11px] text-slate-400 mt-1">
                                                {{ $participant->role ?? 'Signatory' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right Side Indicator -->
                                @if($isSigned)
                                    <span class="text-[10px] font-bold text-green-600 uppercase tracking-tight">Completed</span>
                                @elseif($isActive)
                                    <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-tighter">Active</span>
                                @else
                                    <i data-lucide="lock" class="w-4 h-4 text-slate-300"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Section 3: Attachments & Upload -->
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="flex items-center gap-2 text-sm font-black text-slate-900 uppercase tracking-widest">
                        <i class="fa-solid fa-paperclip"></i>
                        Attachments
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ $document->attachments->count() }} Total</span>
                </div>
                
                <div class="space-y-3">
                    @foreach($document->attachments as $file)
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all hover:border-blue-200">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            @php
                                $type = strtolower($file->file_type);

                                $icon = match (true) {
                                    in_array($type, ['pdf'])          => 'fa-file-pdf',
                                    in_array($type, ['doc', 'docx'])  => 'fa-file-word',
                                    in_array($type, ['txt'])          => 'fa-file-lines',
                                    in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])          => 'fa-file-image text-orange-400',
                                    default                           => 'fa-file',
                                };
                            @endphp

                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-100">
                                <i class="fa-solid {{ $icon }} text-slate-400 text-lg"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate" title="{{ $file->file_name }}">
                                    {{ $file->file_name }}
                                </p>
                                <p class="text-[11px] text-slate-400 uppercase font-black">
                                    {{ $file->file_type }} • {{ \App\Http\Controllers\DocumentController::humanFileSize($file->file_size, 2) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @php
                                $ext = strtolower($file->file_type);
                                $previewable = in_array($ext, ['pdf','jpg','jpeg','png','gif','webp']);
                            @endphp
                            @if($previewable)
                            <a href="#"
                                type="button"
                                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                                onclick="openPreviewModal('{{ route('attachments.preview', $file->id) }}?v={{ $file->updated_at->timestamp }}', '{{ $ext }}')">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @endif
                            <a href="{{ route('attachments.download', $file->id ) }}" 
                            class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                            title="Download PDF">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
        <!-- Right Column: Timeline / History -->
        <div class="lg:col-span-4">
            <div class="sticky top-8 mx-2 space-y-6">
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
        </div>
    </div>
</div>

@endsection