@extends('layouts.app')

@section('title', 'Initiate Request Accommodation')

@section('content')
<div class="min-h-screen pb-20">
    <div class="max-w-3xl mx-auto">
        
        <!-- Header & Progress -->
        <header class="mb-10 text-center">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                Step <span id="current-step-label" class="mx-1">1</span> of 2
            </div>
            <h1 id="step-title" class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight transition-all duration-300">
                Document Details
            </h1>
            <p id="step-desc" class="mt-3 text-slate-500 font-medium">Start by providing the basic information for this request.</p>

            <!-- Progress Bar -->
            <div class="mt-10 h-2 w-full bg-slate-100 rounded-full overflow-hidden max-w-md mx-auto">
                <div id="progress-bar" class="h-full bg-indigo-600 shadow-[0_0_15px_rgba(79,70,229,0.4)] transition-all duration-700 ease-out" style="width: 33.33%"></div>
            </div>
        </header>

        <!-- Main Form -->
        <form id="request-form" action="{{ route('externals.store') }}" method="POST" enctype="multipart/form-data" class="relative">
            @csrf

            <!-- STEP 1: DETAILS -->
            <div id="step-1" class="step-container space-y-8 animate-in fade-in zoom-in-95 duration-500">
                <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 space-y-8">
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Subject <span class="text-rose-500">*</span></label>
                        <input type="text" id="field-subject" name="subject" value="{{ old('subject') }}" 
                            class="w-full text-2xl font-bold border-0 border-b-2 border-slate-100 focus:border-indigo-500 focus:ring-0 px-0 py-3 placeholder:text-slate-200 transition-all" 
                            placeholder="e.g. WiFi Connectivity Request">
                        <p id="error-subject" class="text-[10px] font-bold text-rose-500 uppercase hidden">A subject of this request is required.</p>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Requesting Agency <span class="text-rose-500">*</span></label>
                        <input type="text" id="field-agency" name="agency" value="{{ old('agency') }}" 
                            class="w-full text-2xl font-bold border-0 border-b-2 border-slate-100 focus:border-indigo-500 focus:ring-0 px-0 py-3 placeholder:text-slate-200 transition-all" 
                            placeholder="e.g. LGU Solano, DTI, DOST...">
                            <p id="error-agency" class="text-[10px] font-bold text-rose-500 uppercase hidden">Please indicate requesting agency.</p>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Point of Contact/Contact Number/Email <span class="text-rose-500">*</span></label>
                        <input type="text" id="field-contact" name="contact" value="{{ old('contact') }}" 
                            class="w-full text-2xl font-bold border-0 border-b-2 border-slate-100 focus:border-indigo-500 focus:ring-0 px-0 py-3 placeholder:text-slate-200 transition-all" 
                            placeholder="e.g. John Doe / 09171234567 / jd@somewhere.com">
                            <p id="error-contact" class="text-[10px] font-bold text-rose-500 uppercase hidden">Please indicate point of contact.</p>
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
                            placeholder="e.g. Request.Wifi.2026.001">
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

            <!-- STEP 2: ATTACHMENTS -->
            <div id="step-2" class="step-container hidden animate-in fade-in zoom-in-95 duration-500">
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
                    @if(auth()->user()->canForwardRequests()) 
                        <button type="submit" id="submit-btn" class="hidden flex-1 md:flex-none px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:shadow-2xl transition-all">
                            Forward to ORD <i class="fas fa-paper-plane ml-3"></i>
                        </button>
                    @else
                        <button type="submit" id="submit-btn" class="hidden flex-1 md:flex-none px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:shadow-2xl transition-all">
                            Forward to Records <i class="fas fa-paper-plane ml-3"></i>
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 2;
const stepTitles = ["Request Details", "Attachments"];
const stepDescs = ["Provide external request info", "Attach documents"];
let queuedFiles = [];

const form = document.getElementById('request-form');
const nextBtn = document.getElementById('next-btn');
const prevBtn = document.getElementById('prev-btn');
const submitBtn = document.getElementById('submit-btn');
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
        const subject = document.getElementById('field-subject').value.trim();
        const error1 = document.getElementById('error-subject');
        const agency = document.getElementById('field-agency').value.trim();
        const error2 = document.getElementById('error-agency');
        const contact = document.getElementById('field-contact').value.trim();
        const error3 = document.getElementById('error-contact');

        if (!subject) { error1.classList.remove('hidden'); isValid = false; }
        else { error1.classList.add('hidden'); }
        if (!agency) { error2.classList.remove('hidden'); isValid = false; }
        else { error2.classList.add('hidden'); }
        if (!contact) { error3.classList.remove('hidden'); isValid = false; }
        else { error3.classList.add('hidden'); }
    }
    if (currentStep === 2) {
        const error = document.getElementById('error-files');
        if (queuedFiles.length === 0) { error.classList.remove('hidden'); isValid = false; }
        else { error.classList.add('hidden'); }
    }
    return isValid;
}

nextBtn.addEventListener('click', () => { if (validateCurrentStep()) { currentStep++; updateUI(); } });
prevBtn.addEventListener('click', () => { if (currentStep > 1) { currentStep--; updateUI(); } });

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