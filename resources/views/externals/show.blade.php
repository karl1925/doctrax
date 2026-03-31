@extends('layouts.app')

@section('title', 'View Requests')

@section('content')
<div class="container mx-auto px-4 pt-8 max-w-6xl mb-8">
    <!-- Header Section -->
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
                <div class="sticky top-8 space-y-6">
                    @include('partials.histories')
                </div>
            </div>
        </div>
    </div>

    @php
        $authIsAssignee = $external->assigned_to === auth()->id();
        if($authIsAssignee) {
            $show = $external->status === "accepted";
        } else {
            $show = $external->status !== "completed";
        }
    @endphp
    @if($show) 
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

                        <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-slate-600 text-white hover:bg-slate-700">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
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

                <button type="button" 
                        class="px-4 py-2 text-sm rounded-lg bg-slate-600 text-white hover:bg-slate-700 disabled:opacity-50"
                        onclick="this.disabled = true; this.form.submit();">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
    <script>
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
        
    </script>
@endpush
@endsection