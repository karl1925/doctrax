@extends('layouts.app')

@section('title', 'Sign Document')

@push('styles')
<style>
/* Modal Visibility States */
.modal-overlay {
    transition: opacity 0.3s ease-in-out;
}

.modal-content {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease-in-out;
}

.hidden-modal {
    opacity: 0;
    pointer-events: none;
}

.hidden-modal .modal-content {
    transform: scale(0.9) translateY(20px);
    opacity: 0;
}

/* UI Elements */
.btn-action-ghost {
    padding: 0.625rem 1.25rem;
    font-size: 0.75rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-radius: 0.75rem;
    transition: all 0.2s ease;
}

.textarea-custom {
    width: 100%;
    padding: 1rem 1.25rem;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 1.25rem;
    outline: none;
    transition: all 0.2s ease;
    color: #1e293b;
    resize: none;
}

.textarea-custom:focus {
    background-color: #fff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.textarea-error {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
    animation: shake 0.4s ease-in-out;
}

/* Animations */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}

/* Feedback States */
.processing {
    pointer-events: none;
    opacity: 0.7;
}
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-2 max-w-5xl">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2 text-[9px] uppercase">Priority
                @if($doc->priority === 'urgent')
                    <span class="text-[9px] font-black text-rose-600 uppercase tracking-tighter bg-rose-50 px-1.5 py-0.5 rounded">Urgent</span>
                @elseif($doc->priority === 'high')
                    <span class="text-[9px] font-black text-amber-600 uppercase tracking-tighter bg-amber-50 px-1.5 py-0.5 rounded">High</span>
                @endif
                <span class="text-slate-400 text-sm">{{ $doc->reference }}</span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $doc->title }}</h1>
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
                                <span class="text-sm font-semibold text-slate-700">{{ $doc->owner->name }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Deadline</label>
                            <span class="text-sm font-semibold text-slate-700">{{ $doc->target_date?->diffForHumans() ?? 'No deadline' }}</span>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Executive Summary</label>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $doc->remarks }}
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
                        @foreach($doc->participants as $index => $participant)
                            @php
                                //$table->enum('status', ['active', 'queued', 'approved', 'rejected', 'revision'])->default('queued');
                                $isCurrentUser = auth()->id() === $participant->user_id;
                                $isSigned = $participant->status === 'approved';
                                // Logic for "Active" usually means not signed yet, but it's their turn
                                $isActive = $participant->status === 'active' && ($index === 0 || $doc->participants[$index - 1]->status === 'approved');
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
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">2 Files Total</span>
                </div>
                
                <div class="space-y-3">
                    @foreach($doc->attachments as $file)
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

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('attachments.download', $file->id ) }}" 
                            class="flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                            title="Download PDF">
                                <i class="fa-solid fa-download"></i> Download
                            </a>

                            @if($file->file_type === 'pdf')
                            <form action="{{ route('attachments.replace', $file->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <label class="flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg cursor-pointer transition-colors shadow-sm">
                                    <i class="fa-solid fa-file-arrow-up"></i>
                                    <span>Replace with Signed</span>
                                    <input 
                                        type="file" 
                                        name="attachment" 
                                        class="hidden" 
                                        accept=".pdf"
                                        onchange="this.form.submit(); this.disabled = true; this.parentElement.classList.add('opacity-50', 'cursor-not-allowed');"
                                    >
                                </label>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            <!-- Modal Structure -->
            <div id="actionModal" class="fixed hidden inset-0 z-50 flex items-center justify-center p-4 modal-overlay hidden-modal">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
                
                <!-- Modal Card -->
                <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden modal-content">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 id="modalTitle" class="text-xl font-bold text-slate-900">Action Title</h3>
                                <p id="modalSubtitle" class="text-slate-500 text-xs mt-1">Please provide details for the history log.</p>
                            </div>
                            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Remarks / Reason</label>
                                <textarea 
                                    id="actionRemarks"
                                    rows="4" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-slate-700 resize-none"
                                    placeholder="Type your remarks here..."></textarea>
                            </div>
                            
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center gap-3">
                                <i class="fa-solid fa-circle-info w-5 h-5 text-slate-400 shrink-0"></i>
                                <p class="text-[11px] text-slate-500 leading-relaxed">
                                    Your remarks will be permanently attached to this document's audit trail. This action cannot be undone once confirmed.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="p-6 bg-slate-50 flex flex-col sm:flex-row-reverse gap-3">
                        <button 
                            id="confirmBtn"
                            onclick="handleActionSubmit()"
                            class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-white rounded-2xl shadow-lg transition-all active:scale-95">
                            Confirm Action
                        </button>
                        <button 
                            onclick="closeModal()"
                            class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-slate-600 hover:bg-slate-200 rounded-2xl transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Timeline / History -->
        <div class="lg:col-span-4">
            <div class="sticky top-8 space-y-6">
                <section>
                    <h3 class="flex items-center gap-2 text-sm font-black text-slate-900 uppercase tracking-widest mb-4">
                        <i data-lucide="history" class="w-4 h-4 text-blue-500"></i>
                        Routing History
                    </h3>
                    @forelse($doc->history as $history)
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

                <!-- Help/Support Card -->
                <div class="bg-indigo-50 rounded-3xl p-6 border border-indigo-100">
                    <h4 class="text-sm font-black text-indigo-900 uppercase mb-2">Need Help?</h4>
                    <p class="text-xs text-indigo-700 leading-relaxed mb-4">If you notice errors in the document content, use the "Return for Revision" action below to notify the originator.</p>
                    {{-- <a href="#" class="text-[11px] font-bold text-indigo-600 flex items-center gap-1 hover:underline">
                        View Signing Policy
                        <i data-lucide="external-link" class="w-3 h-3"></i>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- Final Action Bar (Bottom) -->
<div class="max-w-5xl mx-auto w-full my-12">
    <div class="bg-slate-900 rounded-3xl p-6 shadow-xl">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="text-center sm:text-left">
                <h4 class="text-white font-bold">Ready to finalize?</h4>
                <p class="text-slate-400 text-xs">By clicking Approve, you confirm that your digital signature are already applied in the attachments.</p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-3 w-full sm:w-auto">
                <button onclick="openActionModal('revision')" class="px-5 py-2.5 text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider transition-colors">
                    Return for Revision
                </button>
                <button onclick="openActionModal('rejected')" class="px-5 py-2.5 text-xs font-black text-red-500 hover:bg-red-500/10 rounded-xl uppercase tracking-wider transition-colors">
                    Reject
                </button>
                <div class="h-8 w-px bg-slate-800 hidden sm:block"></div>
                <button onclick="openActionModal('approved')" class="flex items-center gap-2 px-8 py-3 text-sm font-black text-white bg-blue-600 hover:bg-blue-500 rounded-2xl shadow-lg shadow-blue-900/20 transition-all active:scale-95">
                    <i class="fa-solid fa-thumbs-up"></i>
                    Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure: Note 'hidden' class added here -->
<div id="actionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-overlay hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden modal-content">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 id="modalTitle" class="text-xl font-bold text-slate-900">Action</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>
            <textarea id="actionRemarks" rows="4" class="textarea-custom" placeholder="Provide remarks..." required></textarea>
        </div>
        <div class="p-6 bg-slate-50 flex flex-col sm:flex-row-reverse gap-3">
            <button id="confirmBtn" onclick="handleActionSubmit()" class="px-8 py-3 text-sm font-bold text-white rounded-2xl shadow-lg">Confirm</button>
            <button onclick="closeModal()" class="px-8 py-3 text-sm font-bold text-slate-600 hover:bg-slate-200 rounded-2xl">Cancel</button>
        </div>
    </div>
</div>

{{-- Hidden Form --}}
<form id="docActionForm" action="{{ route('documents.process', $doc->id) }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="action" id="formActionType">
    <input type="hidden" name="comment" id="formRemarks">
</form>

@push('scripts')
<script>
    const modalEl = document.getElementById('actionModal');
    const titleEl = document.getElementById('modalTitle');
    const remarksEl = document.getElementById('actionRemarks');
    const confirmBtnEl = document.getElementById('confirmBtn');
    
    let currentActionType = null;

    const actionConfig = {
        revision: { title: 'Return for Revision', btnText: 'Send Back', color: 'bg-amber-500', requireRemarks: true },
        rejected: { title: 'Reject Document', btnText: 'Confirm Rejection', color: 'bg-red-600', requireRemarks: true },
        approved: { title: 'Complete Workflow', btnText: 'Approve', color: 'bg-emerald-600', requireRemarks: false }
    };

    window.openActionModal = function(action) {
        currentActionType = action;
        const config = actionConfig[action];

        titleEl.innerText = config.title;
        confirmBtnEl.innerText = config.btnText;
        confirmBtnEl.className = `w-full sm:w-auto px-8 py-3 text-sm font-bold text-white rounded-2xl shadow-lg transition-all ${config.color}`;
        remarksEl.value = '';
        remarksEl.classList.remove('textarea-error');

        // Show and animate
        modalEl.classList.remove('hidden');
        modalEl.classList.add('modal-entering');
        // Trigger reflow for animation
        void modalEl.offsetWidth;
        modalEl.classList.remove('modal-entering');
        
        setTimeout(() => remarksEl.focus(), 150);
    };

    window.closeModal = function() {
        modalEl.classList.add('modal-entering');
        setTimeout(() => {
            modalEl.classList.add('hidden');
            modalEl.classList.remove('modal-entering');
        }, 300);
    };

    window.handleActionSubmit = function() {
        const remarks = remarksEl.value.trim();
        const config = actionConfig[currentActionType];

        if (config.requireRemarks && !remarks) {
            remarksEl.classList.add('textarea-error');
            return;
        }

        confirmBtnEl.classList.add('processing');
        confirmBtnEl.innerText = 'Submitting...';

        document.getElementById('formActionType').value = currentActionType;
        document.getElementById('formRemarks').value = remarks;
        document.getElementById('docActionForm').submit();
    };

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
@endsection