@extends('layouts.app')

@section('title', 'Initiate New Routing')

@section('content')
<div class="min-h-screen pb-20">
    <div class="max-w-3xl mx-auto">
        
        <!-- Header & Progress -->
        <header class="mb-10 text-center">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                Step <span id="current-step-label" class="mx-1">1</span> of 3
            </div>
            <h1 id="step-title" class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight transition-all duration-300">
                Document Details
            </h1>
            <p id="step-desc" class="mt-3 text-slate-500 font-medium">Start by providing the basic information for this routing.</p>

            <!-- Progress Bar -->
            <div class="mt-10 h-2 w-full bg-slate-100 rounded-full overflow-hidden max-w-md mx-auto">
                <div id="progress-bar" class="h-full bg-indigo-600 shadow-[0_0_15px_rgba(79,70,229,0.4)] transition-all duration-700 ease-out" style="width: 33.33%"></div>
            </div>
        </header>

        <!-- Main Form -->
        <form id="routing-form" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="relative">
            @csrf

            <!-- STEP 1: DETAILS -->
            <div id="step-1" class="step-container space-y-8 animate-in fade-in zoom-in-95 duration-500">
                <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 space-y-8">
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Title <span class="text-rose-500">*</span></label>
                        <input type="text" id="field-title" name="title" value="{{ old('title') }}" 
                            class="w-full text-2xl font-bold border-0 border-b-2 border-slate-100 focus:border-indigo-500 focus:ring-0 px-0 py-3 placeholder:text-slate-200 transition-all" 
                            placeholder="e.g. Daily Time Record with Accomplishments">
                        <p id="error-title" class="text-[10px] font-bold text-rose-500 uppercase hidden">A descriptive title is required.</p>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Executive Summary</label>
                        <textarea name="description" rows="4" 
                            class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-indigo-100 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-slate-700 placeholder:text-slate-300 leading-relaxed" 
                            placeholder="Briefly explain what needs to be reviewed...">{{ old('description') }}</textarea>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Reference #</label>
                        <input type="text" id="field-reference" name="reference" value="{{ old('reference') }}" 
                            class="w-full text-2xl font-bold border-0 border-b-2 border-slate-100 focus:border-indigo-500 focus:ring-0 px-0 py-3 placeholder:text-slate-200 transition-all" 
                            placeholder="DTR.SurnameFirstname.yyyymmdd">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <div class="space-y-3">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Priority Level</label>
                            <div class="flex p-1.5 bg-slate-50 rounded-2xl gap-1">
                                @foreach(['normal', 'high', 'urgent'] as $p)
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="priority" value="{{ $p }}" class="peer hidden" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="py-3 text-center rounded-xl text-[10px] font-black uppercase tracking-wider text-slate-400 peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow-sm transition-all">
                                        {{ $p }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Deadline (Target Date)</label>
                            @php
                                $minDate = now()->format('Y-m-d\TH:i'); // format for datetime-local
                            @endphp
                            <input 
                                type="datetime-local" 
                                name="target_date" 
                                min="{{ $minDate }}"
                                class="w-full px-5 py-3.5 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-indigo-100 focus:ring-4 focus:ring-indigo-50 text-slate-700 font-bold outline-none transition-all"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: WORKFLOW -->
            <div id="step-2" class="step-container hidden animate-in fade-in zoom-in-95 duration-500">
                <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-100 -z-10"></div>
                        <div id="reviewer-list" class="space-y-4 mb-10">
                            <div id="empty-state" class="py-20 border-2 border-dashed border-slate-100 rounded-[2rem] text-center bg-slate-50/30">
                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 text-slate-300">
                                    <i class="fas fa-users-cog text-2xl"></i>
                                </div>
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
                    <p id="error-workflow" class="text-[10px] font-bold text-rose-500 uppercase mt-4 text-center hidden">Please add at least one person to the approval sequence.</p>
                </div>
            </div>

            <!-- STEP 3: ATTACHMENTS -->
            <div id="step-3" class="step-container hidden animate-in fade-in zoom-in-95 duration-500">
                <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
                    <div id="drop-zone" class="relative group border-4 border-dashed border-slate-100 rounded-[2.5rem] p-16 transition-all hover:border-indigo-200 hover:bg-indigo-50/30 text-center cursor-pointer overflow-hidden">
                        <!-- MANDATORY: Input must have the name="attachments[]" attribute -->
                        <input type="file" id="file-input" name="attachments[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-6 pointer-events-none relative z-0">
                            <div class="w-20 h-20 bg-indigo-600 rounded-[2rem] flex items-center justify-center mx-auto text-white shadow-xl shadow-indigo-200 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-500">
                                <i class="fas fa-cloud-upload-alt text-3xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-800 tracking-tight">Drop documents here</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-2">At least one attachment is required</p>
                            </div>
                        </div>
                    </div>

                    <div id="file-list" class="mt-12 space-y-4 hidden animate-in fade-in slide-in-from-bottom-4">
                        <div class="flex items-center justify-between px-2 mb-6">
                            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Selected Assets (<span id="file-count">0</span>)</h4>
                            <button type="button" id="clear-files-btn" class="text-[10px] font-black text-rose-500 uppercase hover:underline">Clear All</button>
                        </div>
                        <div id="file-items" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                    </div>
                    <p id="error-files" class="text-[10px] font-bold text-rose-500 uppercase hidden mt-8 text-center">At least one attachment is required to launch the workflow.</p>
                </div>
            </div>

            <!-- Wizard Footer Navigation -->
            <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 px-4">
                <button type="button" id="prev-btn" class="hidden w-full md:w-auto px-10 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left mr-3"></i> Go Back
                </button>
                <div class="flex w-full md:w-auto gap-4">
                    <button type="button" id="next-btn" class="flex-1 md:flex-none px-12 py-5 bg-slate-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:shadow-2xl transition-all">
                        Next Step <i class="fas fa-chevron-right ml-3"></i>
                    </button>
                    <button type="submit" id="submit-btn" class="hidden flex-1 md:flex-none px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:shadow-2xl transition-all">
                        Launch Workflow <i class="fas fa-paper-plane ml-3"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 3;
const stepTitles = ["Routing Details", "Approval Sequence", "Attachments"];
const stepDescs = ["Provide basic info", "Assign reviewers", "Attach documents"];
let queuedFiles = [];

const form = document.getElementById('routing-form');
const nextBtn = document.getElementById('next-btn');
const prevBtn = document.getElementById('prev-btn');
const submitBtn = document.getElementById('submit-btn');
const addReviewerBtn = document.getElementById('add-reviewer-btn');
const fileInput = document.getElementById('file-input');
const clearFilesBtn = document.getElementById('clear-files-btn');

function updateUI() {
    document.querySelectorAll('.step-container').forEach(el => el.classList.add('hidden'));
    document.getElementById(`step-${currentStep}`).classList.remove('hidden');
    document.getElementById('current-step-label').innerText = currentStep;
    document.getElementById('step-title').innerText = stepTitles[currentStep - 1];
    document.getElementById('step-desc').innerText = stepDescs[currentStep - 1];
    document.getElementById('progress-bar').style.width = (currentStep / totalSteps) * 100 + '%';

    prevBtn.classList.toggle('hidden', currentStep === 1);
    if (currentStep === totalSteps) {
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
    } else {
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    }
}

function validateCurrentStep() {
    let isValid = true;
    if (currentStep === 1) {
        const title = document.getElementById('field-title').value.trim();
        const error = document.getElementById('error-title');
        if (!title) { error.classList.remove('hidden'); isValid = false; }
        else { error.classList.add('hidden'); }
    }
    if (currentStep === 2) {
        const reviewers = document.querySelectorAll('input[name="reviewers[]"]').length;
        const error = document.getElementById('error-workflow');
        if (reviewers === 0) { error.classList.remove('hidden'); isValid = false; }
        else { error.classList.add('hidden'); }
    }
    if (currentStep === 3) {
        const error = document.getElementById('error-files');
        if (queuedFiles.length === 0) { error.classList.remove('hidden'); isValid = false; }
        else { error.classList.add('hidden'); }
    }
    return isValid;
}

nextBtn.addEventListener('click', () => { if (validateCurrentStep()) { currentStep++; updateUI(); } });
prevBtn.addEventListener('click', () => { if (currentStep > 1) { currentStep--; updateUI(); } });

addReviewerBtn.addEventListener('click', () => {
    const select = document.getElementById('user-select');
    const id = select.value;
    const name = select.options[select.selectedIndex].text;
    if(!id) return;

    const container = document.getElementById('reviewer-list');
    const emptyState = document.getElementById('empty-state');
    if(emptyState) emptyState.remove();

    const index = container.querySelectorAll('.reviewer-item').length;
    const div = document.createElement('div');
    div.className = "reviewer-item relative flex items-center gap-6 mb-6 animate-in slide-in-from-left-4";
    div.innerHTML = `
        <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xs font-black z-10">${index + 1}</div>
        <div class="flex-1 bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-0.5">Reviewer</p>
                    <p class="text-sm font-black text-slate-800 tracking-tight">${name}</p>
                    <input type="hidden" name="reviewers[]" value="${id}">
                </div>
            </div>
            <button type="button" class="remove-reviewer-btn w-10 h-10 rounded-xl hover:bg-rose-50 text-slate-300 hover:text-rose-500 transition-all">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    `;
    div.querySelector('.remove-reviewer-btn').addEventListener('click', () => {
        div.remove();
        container.querySelectorAll('.reviewer-item').forEach((item, idx) => {
            item.querySelector('.w-10').innerText = idx + 1;
        });
    });
    container.appendChild(div);
    select.value = "";
});

// File Handling
fileInput.addEventListener('change', (e) => {
    const newFiles = Array.from(e.target.files);
    queuedFiles = [...queuedFiles, ...newFiles];
    updateFileDisplay();
});

clearFilesBtn.addEventListener('click', () => {
    queuedFiles = [];
    updateFileDisplay();
});

function updateFileDisplay() {
    const fileItems = document.getElementById('file-items');
    const fileListContainer = document.getElementById('file-list');
    
    if (queuedFiles.length > 0) {
        fileListContainer.classList.remove('hidden');
        document.getElementById('file-count').textContent = queuedFiles.length;
        fileItems.innerHTML = ''; 
        queuedFiles.forEach((file, index) => {
            const div = document.createElement('div');
            div.className = "flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100";
            div.innerHTML = `
                <div class="flex items-center gap-3 overflow-hidden">
                    <i class="fas fa-file-alt text-indigo-500"></i>
                    <p class="text-[11px] font-black text-slate-700 truncate">${file.name}</p>
                </div>
                <button type="button" class="remove-file-btn text-rose-500 px-2"><i class="fas fa-times"></i></button>
            `;
            div.querySelector('.remove-file-btn').addEventListener('click', () => {
                queuedFiles.splice(index, 1);
                updateFileDisplay();
            });
            fileItems.appendChild(div);
        });
    } else {
        fileListContainer.classList.add('hidden');
    }
}

// CRITICAL FIX: Transfer queuedFiles array to the real input before submission
form.addEventListener('submit', (e) => {
    if (!validateCurrentStep()) {
        e.preventDefault();
        return;
    }

    // This creates a virtual FileList from our JavaScript array
    const dataTransfer = new DataTransfer();
    queuedFiles.forEach(file => dataTransfer.items.add(file));
    
    // Inject the files into the real input so Laravel can read them as an array
    fileInput.files = dataTransfer.files;
});

updateUI();
</script>
@endsection