@extends('layouts.app')

@section('content')
    <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-4 gap-6">
        <div class="space-y-2">
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-xs font-medium text-slate-500 uppercase tracking-wider">
                    <li>SETTINGS</li>
                    <li><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></li>
                    <li class="text-indigo-600 font-bold">NOTIFICATION PREFERENCES</li>
                </ol>
            </nav>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                <div class="p-2 bg-stone-500 rounded-lg shadow-lg shadow-gray-200">
                    <i class="fa-solid fa-user-gear text-white"></i>
                </div>
                Notification Preferences
            </h1>

            <form action="{{ route('settings.preferences.update', $preference->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- External Notifications --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                        External Requests
                    </h3>

                    <div class="space-y-4">
                        @foreach([
                            'external_email_notify_received' => 'Notify when received',
                            'external_email_notify_updated' => 'Notify when updated',
                            'external_email_notify_completed' => 'Notify when completed',
                        ] as $field => $label)

                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">{{ $label }}</span>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                        name="{{ $field }}" 
                                        class="sr-only peer"
                                        {{ $preference->$field ? 'checked' : '' }}>

                                    <div class="w-11 h-6 bg-gray-300 rounded-full peer 
                                                peer-checked:bg-blue-600 
                                                peer-focus:ring-2 peer-focus:ring-blue-300
                                                transition-all duration-300">
                                    </div>

                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full 
                                                transition-all duration-300
                                                peer-checked:translate-x-5">
                                    </div>
                                </label>
                            </div>

                        @endforeach
                    </div>
                </div>

                @if(config('modules.internal_routing'))
                {{-- Internal Notifications --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                        Internal Requests
                    </h3>

                    <div class="space-y-4">
                        @foreach([
                            'internal_email_notify_received' => 'Notify when received',
                            'internal_email_notify_returned' => 'Notify when returned',
                            'internal_email_notify_reviewed' => 'Notify when reviewed',
                            'internal_email_notify_completed' => 'Notify when completed',
                            'internal_email_notify_rejected' => 'Notify when rejected',
                        ] as $field => $label)

                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">{{ $label }}</span>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                        name="{{ $field }}" 
                                        class="sr-only peer"
                                        {{ $preference->$field ? 'checked' : '' }}>

                                    <div class="w-11 h-6 bg-gray-300 rounded-full peer 
                                                peer-checked:bg-green-600 
                                                peer-focus:ring-2 peer-focus:ring-green-300
                                                transition-all duration-300">
                                    </div>

                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full 
                                                transition-all duration-300
                                                peer-checked:translate-x-5">
                                    </div>
                                </label>
                            </div>

                        @endforeach
                    </div>
                </div>
                @endif

                <div class="pt-6 border-t">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl 
                                hover:bg-blue-700 transition duration-300 shadow-md">
                        Save Preferences
                    </button>
                </div>
            </form>
         </div>
    </div>
@endsection