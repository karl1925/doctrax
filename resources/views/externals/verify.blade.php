@extends('layouts.app')

@section('title', 'View Requests')

@push('styles')
<style>
    /* Modal & UI Transitions */
    .modal-overlay { transition: opacity 0.3s ease-in-out; }
    .modal-content { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease-in-out; }
    .hidden-modal { opacity: 0; pointer-events: none; }
    .hidden-modal .modal-content { transform: scale(0.9) translateY(20px); opacity: 0; }

    /* Action Bar Animation */
    @keyframes slideUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .action-bar-animate {
        animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 pt-8 max-w-6xl mb-8">
    @include('partials.external-header')

    <div class="grid grid-cols-1 mt-8 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Column: Request Details (8 Cols) -->
        <div class="lg:col-span-7 xl:col-span-8 space-y-8">
            @include('partials.details')
            @include('partials.attachments')
        </div>

        <!-- Right Column: Attachments (4 Cols) -->
        <div class="lg:col-span-5 xl:col-span-4">
            <div class="sticky top-8 space-y-6">
                @include('partials.histories')
            </div>
        </div>
    </div>

    @if($external->status !== "assigned" && $external->status !== "completed") 
    <div class="flex flex-col mt-4 sm:flex-row gap-3 justify-center items-center">
        <button
            type="button"
            onclick="openAttachmentModal()"
            class="inline-flex items-center justify-center rounded-lg bg-slate-600 gap-2 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            <i class="fa-solid fa-link"></i> Add Attachments
        </button>

        <button
            type="button"
            onclick="openUpdateModal()"
            class="inline-flex items-center justify-center rounded-lg bg-blue-600 gap-2 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            <i class="fa-solid fa-comment-medical"></i>
            Add Update
        </button>
    </div>
    @endif

    @include('partials.actions')
</div>

<!-- Modal Structure -->
<div id="updateModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">

    <div class="bg-white w-full max-w-md rounded-xl shadow-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Add Update</h3>
        <form action="{{ route('externals.addupdate', $external->id) }}" method="POST">
            @csrf
            @method('PUT')
            <textarea name="remarks" class="w-full rounded-lg border border-slate-300 p-3 text-sm focus:ring focus:ring-blue-300"
                rows="4"
                placeholder="Enter remarks..." required></textarea>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeUpdateModal()"
                        class="px-4 py-2 text-sm rounded-lg border">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<div id="attachmentModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">

    <div class="bg-white w-full max-w-md rounded-xl shadow-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Add Attachments</h3>
        <form action="{{ route('externals.attach', $external->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="file" name="attachments[]"
                multiple
                class="w-full rounded-lg border border-slate-300 p-2 text-sm" required>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeAttachmentModal()"
                        class="px-4 py-2 text-sm rounded-lg border">
                    Cancel
                </button>

                <button type="button" onclick="this.disabled = true; this.form.submit();" class="px-4 py-2 text-sm rounded-lg bg-slate-600 text-white hover:bg-slate-700">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

<div id="actionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-overlay hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>

    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden modal-content">

        <form id="ActionForm"
              action="{{ route(($external->status === 'pending' ? 'externals.forward' : ($external->status === 'forwarded' ? 'externals.endorse' : 'externals.assign' )), $external->id) }}"
              method="POST"
              onsubmit="return validateActionForm()">

            @csrf
            @method('PUT')

            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900">
                        {{ $external->status === 'pending' ? 'Forward to ORD' : ($external->status === 'forwarded' ? 'Endorse to Division' : ($external->status === 'endorsed' ? 'Assign to Personnel' : 'Assign to Colleague')) }}
                    </h3>
                    <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                @if($external->status === 'forwarded')
                    <select id="division"
                            name="division"
                            class="block w-full mb-4"
                            required>
                        <option value="" disabled selected>Select Division</option>
                        <option value="TOD">Technical Operations Division</option>
                        <option value="AFD">Admin and Finance Division</option>
                    </select>
                @elseif($external->status === 'endorsed' || $external->status === 'assigned' || $external->status === 'accepted')
                    <select id="personnel"
                            name="personnel"
                            class="block w-full mb-4"
                            required>
                        <option value="" disabled selected>Select personnel</option>
                        @php
                            $pers = \App\Models\User::withoutTrashed()->orderby('name')->get();
                        @endphp
                        @foreach($pers as $per)
                            <option value="{{ $per->id }}">{{ $per->name }}</option>
                        @endforeach
                    </select>
                @endif

                <textarea id="remarks"
                          name="remarks"
                          rows="4"
                          class="block w-full"
                          placeholder="Optional remarks..."></textarea>
            </div>

            <div class="p-6 bg-slate-50 flex flex-col sm:flex-row-reverse gap-3">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-3 px-8 py-4 text-sm font-black text-white bg-blue-600 hover:bg-blue-500 rounded-[1.5rem] shadow-lg shadow-blue-600/30 transition-all active:scale-95 group">
                    <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    Confirm Action
                </button>

                <button type="button"
                        onclick="closeModal()"
                        class="px-8 py-3 text-sm font-bold text-slate-600 hover:bg-slate-200 rounded-2xl">
                    Cancel
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
    <script>
        const modalEl = document.getElementById('actionModal');
        const remarksEl = document.getElementById('remarks');

        function openUpdateModal() {
            document.getElementById('updateModal').classList.remove('hidden');
            document.getElementById('updateModal').classList.add('flex');
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').classList.add('hidden');
            document.getElementById('updateModal').classList.remove('flex');
        }

        function openAttachmentModal() {
            document.getElementById('attachmentModal').classList.remove('hidden');
            document.getElementById('attachmentModal').classList.add('flex');
        }

        function closeAttachmentModal() {
            document.getElementById('attachmentModal').classList.add('hidden');
            document.getElementById('attachmentModal').classList.remove('flex');
        }
        
        window.openActionModal = function() {
            remarksEl.value = '';

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

        function validateActionForm() {
            @if($external->status === 'forwarded')
            const division = document.getElementById('division');

            if (division && !division.value) {
                alert('Please select a division.');
                return false;
            }
            @endif
            return true;
        }
    </script>
@endpush
@endsection