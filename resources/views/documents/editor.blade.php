@extends('layouts.app')

@section('title', 'Routing Editor')

@section('content')

<div class="min-h-screen pb-20">
<div class="max-w-3xl mx-auto px-4">

    <!-- Header & Progress -->
    <header class="mb-10 text-center">
        <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
            Step <span id="current-step-label" class="mx-1">1</span> of 3
        </div>
        <h1 id="step-title" class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight transition-all duration-300">
            {{ isset($document) ? 'Edit Routing Details' : 'Routing Details' }}
        </h1>
        <p id="step-desc" class="mt-3 text-slate-500 font-medium">Provide the information required to route this document.</p>

        <!-- Progress Bar -->
        <div class="mt-10 h-2 w-full bg-slate-100 rounded-full overflow-hidden max-w-md mx-auto">
            <div id="progress-bar" class="h-full bg-indigo-600 shadow-[0_0_15px_rgba(79,70,229,0.4)] transition-all duration-700 ease-out" style="width: 33.33%"></div>
        </div>
    </header>

    <!-- Main Form -->
    <form id="routing-form" action="{{ isset($document) ? route('documents.update', $document->id) : route('documents.store') }}" method="POST" enctype="multipart/form-data" class="relative">
        @csrf
        @if(isset($document)) 
            @method('PUT') 
        @endif

        <!-- STEP 1: DETAILS -->
        <div id="step-1" class="step-container space-y-8 animate-in fade-in zoom-in-95 duration-500">
            <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 space-y-8">
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Title <span class="text-rose-500">*</span></label>
                    <input type="text" id="field-title" name="title" value="{{ old('title', $document->title ?? '') }}" 
                        class="w-full text-2xl font-bold border-0 border-b-2 border-slate-100 focus:border-indigo-500 focus:ring-0 px-0 py-3 placeholder:text-slate-200 transition-all" 
                        placeholder="e.g. Project Proposal Alpha">
                    <p id="error-title" class="text-[10px] font-bold text-rose-500 uppercase hidden">A descriptive title is required.</p>
                </div>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Executive Summary</label>
                    <textarea name="description" rows="4" 
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-indigo-100 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-slate-700 placeholder:text-slate-300 leading-relaxed" 
                        placeholder="Briefly explain what needs to be reviewed...">{{ old('description', $document->description ?? '') }}</textarea>
                </div>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Reference Number</label>
                    <input type="text" id="field-reference" name="reference" value="{{ old('reference', $document->reference ?? '') }}" 
                        class="w-full text-2xl font-bold border-0 border-b-2 border-slate-100 focus:border-indigo-500 focus:ring-0 px-0 py-3 placeholder:text-slate-200 transition-all" 
                        placeholder="e.g. REF-2024-001">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Priority Level</label>
                        <div class="flex p-1.5 bg-slate-50 rounded-2xl gap-1">
                            @foreach(['normal', 'high', 'urgent'] as $p)
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="priority" value="{{ $p }}" class="peer hidden" {{ old('priority', $document->priority ?? 'normal') === $p ? 'checked' : '' }}>
                                <div class="py-3 text-center rounded-xl text-[10px] font-black uppercase tracking-wider text-slate-400 peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow-sm transition-all">
                                    {{ $p }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Deadline (Target Date)</label>
                        <input type="datetime-local" name="target_date" 
                            value="{{ old('target_date', isset($document->target_date) ? (is_string($document->target_date) ? date('Y-m-d\TH:i', strtotime($document->target_date)) : $document->target_date->format('Y-m-d\TH:i')) : '') }}"
                            class="w-full px-5 py-3.5 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-indigo-100 focus:ring-4 focus:ring-indigo-50 text-slate-700 font-bold outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 2: WORKFLOW (PARTICIPANTS) -->
        <div id="step-2" class="step-container hidden animate-in fade-in zoom-in-95 duration-500">
            <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
                <div class="relative mb-10">
                    <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-slate-100 -z-10"></div>
                    <div id="reviewer-list" class="space-y-4">
                        <!-- Populated via JS -->
                        <div id="empty-state" class="py-20 border-2 border-dashed border-slate-100 rounded-[2rem] text-center bg-slate-50/30">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Define Approval Chain</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-indigo-50/50 rounded-3xl border border-indigo-100 flex flex-col md:flex-row gap-3">
                    <select id="user-select" class="flex-1 px-5 py-3.5 bg-white rounded-2xl border-transparent font-bold text-slate-700 focus:ring-4 focus:ring-indigo-100 outline-none shadow-sm">
                        <option value="">Select a reviewer...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-reviewer-btn" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                        <i class="fas fa-plus mr-2"></i> Add Step
                    </button>
                </div>
                <p id="error-workflow" class="text-[10px] font-bold text-rose-500 uppercase mt-4 text-center hidden">Add at least one reviewer.</p>
            </div>
        </div>

        <!-- STEP 3: ATTACHMENTS -->
        <div id="step-3" class="step-container hidden animate-in fade-in zoom-in-95 duration-500">
            <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 space-y-8">
                
                @if(isset($document) && $document->attachments->count() > 0)
                <div>
                    <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                        <i class="fas fa-archive mr-2"></i> Existing Files
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($document->attachments as $attachment)
                        <div class="group flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <i class="fas fa-paperclip text-slate-400"></i>
                                <span class="text-[11px] font-bold text-slate-600 truncate">{{ $attachment->file_name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-black text-slate-300 uppercase">Saved</span>
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="keep_attachments[]" value="{{ $attachment->id }}" checked class="rounded border-slate-200 text-indigo-600 focus:ring-indigo-500">
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <p class="mt-2 text-[9px] font-bold text-slate-400 uppercase tracking-tight">Uncheck to remove files on update</p>
                </div>
                @endif

                <div id="drop-zone" class="relative border-4 border-dashed border-slate-100 rounded-[2.5rem] p-12 transition-all hover:border-indigo-200 hover:bg-indigo-50/30 text-center cursor-pointer">
                    <input type="file" id="file-input" name="attachments[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="space-y-4 pointer-events-none">
                        <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto text-white shadow-lg">
                            <i class="fas fa-cloud-upload-alt text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-800">Add New Files</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Click or drag files here</p>
                    </div>
                </div>

                <div id="file-list" class="hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-[11px] font-black text-indigo-500 uppercase tracking-widest">New Uploads (<span id="file-count">0</span>)</h4>
                        <button type="button" id="clear-files-btn" class="text-[10px] font-black text-rose-500 uppercase">Clear</button>
                    </div>
                    <div id="file-items" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                </div>
            </div>
        </div>

        <!-- Wizard Footer -->
        <div class="mt-12 flex items-center justify-between gap-6">
            <button type="button" id="prev-btn" class="hidden px-10 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-slate-900 transition-colors">
                <i class="fas fa-arrow-left mr-3"></i> Back
            </button>
            <div class="flex-1 flex justify-end gap-4">
                <button type="button" id="next-btn" class="px-12 py-5 bg-slate-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:shadow-2xl transition-all">
                    Next <i class="fas fa-chevron-right ml-3"></i>
                </button>
                <button type="submit" id="submit-btn" class="hidden px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:shadow-2xl transition-all">
                    {{ isset($document) ? 'Resubmit' : 'Launch' }} <i class="fas fa-paper-plane ml-3"></i>
                </button>
            </div>
        </div>
    </form>
</div>


</div>

<script>
let currentStep = 1;
let queuedFiles = [];
const reviewerContainer = document.getElementById('reviewer-list');

window.onload = () => {
    @if(isset($document))
        const participants = {!! json_encode(
            $document->participants()
                ->with('user')
                ->orderBy('order')
                ->get()
                ->map(fn ($p) => ['id' => $p->user_id, 'name' => $p->user->name])
        ) !!};

        participants.forEach(p => createReviewerItem(p.id, p.name));
    @endif
    updateUI();
};

function createReviewerItem(id, name) {
    document.getElementById('empty-state')?.remove();

    const index = reviewerContainer.querySelectorAll('.reviewer-item').length;
    const div = document.createElement('div');
    div.className = 'reviewer-item flex items-center gap-4 mb-3';

    div.innerHTML = `
        <div class="w-8 h-8 bg-indigo-600 text-white rounded-xl flex items-center justify-center text-xs font-bold">
            ${index + 1}
        </div>
        <div class="flex-1 p-3 bg-slate-50 rounded-xl flex justify-between">
            <div>
                <p class="text-xs font-bold text-indigo-600">Review Step ${index + 1}</p>
                <p class="font-bold">${name}</p>
                <input type="hidden" name="participants[]" value="${id}">
            </div>
            <button type="button" class="remove-reviewer-btn text-rose-500 font-bold">×</button>
        </div>
    `;

    div.querySelector('.remove-reviewer-btn').onclick = () => {
        div.remove();
        refreshIndices();
    };

    reviewerContainer.appendChild(div);
}

function refreshIndices() {
    reviewerContainer.querySelectorAll('.reviewer-item').forEach((item, i) => {
        item.querySelector('.w-8').innerText = i + 1;
        item.querySelector('.text-indigo-600').innerText = `Review Step ${i + 1}`;
    });

    if (!reviewerContainer.children.length) {
        reviewerContainer.innerHTML = `
            <div id="empty-state" class="text-center py-20 text-slate-400 text-xs font-bold uppercase">
                Define Approval Chain
            </div>`;
    }
}

function updateUI() {
    document.querySelectorAll('.step-container').forEach(s => s.classList.add('hidden'));
    document.getElementById(`step-${currentStep}`).classList.remove('hidden');

    document.getElementById('current-step-label').innerText = currentStep;
    document.getElementById('progress-bar').style.width = (currentStep / 3 * 100) + '%';

    document.getElementById('prev-btn').classList.toggle('hidden', currentStep === 1);
    document.getElementById('next-btn').classList.toggle('hidden', currentStep === 3);
    document.getElementById('submit-btn').classList.toggle('hidden', currentStep !== 3);
}

document.getElementById('next-btn').onclick = () => {
    if (validateStep(currentStep)) {
        currentStep++;
        updateUI();
    }
};

document.getElementById('prev-btn').onclick = () => {
    currentStep--;
    updateUI();
};

document.getElementById('add-reviewer-btn').onclick = () => {
    const sel = document.getElementById('user-select');
    if (!sel.value) return;
    createReviewerItem(sel.value, sel.options[sel.selectedIndex].text);
    sel.value = '';
};

document.getElementById('file-input').onchange = e => {
    queuedFiles.push(...e.target.files);
    updateFiles();
};

function updateFiles() {
    const list = document.getElementById('file-list');
    const items = document.getElementById('file-items');

    if (!queuedFiles.length) {
        list.classList.add('hidden');
        return;
    }

    list.classList.remove('hidden');
    document.getElementById('file-count').innerText = queuedFiles.length;

    items.innerHTML = queuedFiles.map(f => `
        <div class="p-2 bg-indigo-50 rounded text-xs font-bold truncate">${f.name}</div>
    `).join('');
}

function validateStep(step) {
    if (step === 1) {
        const title = document.getElementById('field-title').value.trim();
        document.getElementById('error-title').classList.toggle('hidden', !!title);
        return !!title;
    }

    if (step === 2) {
        const ok = reviewerContainer.querySelectorAll('.reviewer-item').length > 0;
        document.getElementById('error-workflow').classList.toggle('hidden', ok);
        return ok;
    }
    return true;
}
</script>

@endsection