@extends('layouts.app')

@section('title', 'Initiate Request Accommodation')

@section('content')
<div class="max-w-3xl mx-auto py-8 space-y-6">

    <!-- Header -->
    <header class="mb-10 text-center">
        <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
            Step <span id="current-step-label" class="mx-1">1</span> of 2
        </div>
        <h1 id="step-title" class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight transition-all duration-300">
            Document Details
        </h1>
        <p id="step-desc" class="mt-3 text-slate-500 font-medium">Start by providing the basic information for this request.</p>
    </header>

    <form id="request-form" action="{{ route('externals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        @csrf

        <!-- Subject -->
        <div class="flex flex-col space-y-1">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Subject <span class="text-rose-500">*</span></label>
            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="e.g. WiFi Connectivity Request"
                class="text-sm border-b-2 border-slate-200 focus:border-indigo-500 focus:outline-none px-1 py-2 rounded">
        </div>

        <!-- Agency -->
        <div class="flex flex-col space-y-1">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Requesting Agency <span class="text-rose-500">*</span></label>
            <input type="text" name="agency" value="{{ old('agency') }}" placeholder="e.g. LGU Solano"
                class="text-sm border-b-2 border-slate-200 focus:border-indigo-500 focus:outline-none px-1 py-2 rounded">
        </div>

        <!-- Contact -->
        <div class="flex flex-col space-y-1">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Point of Contact / Email / Number <span class="text-rose-500">*</span></label>
            <input type="text" name="contact" value="{{ old('contact') }}" placeholder="John Doe / 0917xxx / email@domain.com"
                class="text-sm border-b-2 border-slate-200 focus:border-indigo-500 focus:outline-none px-1 py-2 rounded">
        </div>

        <!-- Description -->
        <div class="flex flex-col space-y-1">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Executive Summary</label>
            <textarea name="description" rows="3" placeholder="Brief explanation..."
                class="text-sm px-3 py-2 rounded-md bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-300 focus:ring-1 focus:ring-indigo-100"></textarea>
        </div>

        <!-- Reference -->
        <div class="flex flex-col space-y-1">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Reference #</label>
            <input type="text" name="reference" value="{{ old('reference') }}" placeholder="Request.Wifi.2026.001"
                class="text-sm border-b-2 border-slate-200 focus:border-indigo-500 focus:outline-none px-1 py-2 rounded">
        </div>

        <!-- Priority & Target Date -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="mb-3">
                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Priority</span>
                <div class="flex gap-2">
                    @foreach(['normal','high','urgent'] as $p)
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="priority" value="{{ $p }}" class="peer hidden" {{ old('priority') == $p || ($loop->first && !old('priority')) ? 'checked' : '' }}>
                        <div class="text-sm font-semibold text-slate-700 text-center py-2 rounded-lg border border-slate-200 
                                    peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:shadow-md 
                                    hover:bg-indigo-50 transition-all">
                            {{ ucfirst($p) }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex flex-col space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Deadline</label>
                <input type="datetime-local" name="target_date" min="{{ now()->format('Y-m-d\TH:i') }}" 
                    class="text-sm px-3 py-2 rounded border border-slate-200 focus:border-indigo-500 focus:outline-none">
            </div>
        </div>

        <!-- Attachments -->
        <div id="step-2" class="step-container animate-in fade-in zoom-in-95 duration-500">
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

        <!-- Division -->
        <div class="flex flex-col space-y-1">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Division</label>
            <select name="division" class="text-sm px-3 py-2 rounded border border-slate-200 focus:border-indigo-500 focus:outline-none" required>
                <option value="">Select Division</option>
                <option value="AFD" {{ old('division') == 'AFD' ? 'selected' : '' }}>Administration and Finance Division</option>
                <option value="TOD" {{ old('division') == 'TOD' ? 'selected' : '' }}>Technical Operations Division</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="pt-2 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition">
                Submit Request
            </button>
        </div>

    </form>
</div>
<script>
let queuedFiles = [];

const form = document.getElementById('request-form');
const submitBtn = document.getElementById('submit-btn');
const fileInput = document.getElementById('file-input');
const clearFilesBtn = document.getElementById('clear-files-btn');

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

</script>
@endsection