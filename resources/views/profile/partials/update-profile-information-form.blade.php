@extends('layouts.app')

@section('title', 'Profile')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Profile') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Update Profile Info --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl space-y-6">

                <header class="space-y-1">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Profile Information') }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{ __('Update your account\'s profile information, including name, email, and avatar.') }}
                    </p>
                </header>

                {{-- Avatar Display --}}
                <div class="flex items-center gap-4">
                    @if ($user->profile_photo_path)
                        <img
                            @php
                                $img_src = asset('storage/' . auth()->user()->profile_photo_path);
                                if (!Str::startsWith($img_src, 'avatars')) {
                                    $img_src = auth()->user()->profile_photo_path;
                                }
                            @endphp
                            src="{{ $img_src}}"
                            alt="{{ auth()->user()->name }}"
                            class="w-20 h-20 rounded-full object-cover ring-2 ring-indigo-100"
                        >
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <img
                                src="{{ Avatar::create(auth()->user()->name)->toBase64() }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-20 h-20 rounded-full ring-2 ring-indigo-100"
                            >
                        </div>
                    @endif

                    {{-- Delete Avatar --}}
                    @if ($user->profile_photo_path)
                        <form method="POST"
                              action="{{ route('profile.avatar.destroy') }}"
                              onsubmit="return confirm('Delete your profile photo?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700 transition">
                                {{ __('Delete Photo') }}
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Profile Update Form --}}
                <form method="POST"
                      action="{{ route('profile.update') }}"
                      enctype="multipart/form-data"
                      class="space-y-4">

                    @csrf
                    @method('PATCH')

                    {{-- Upload Avatar --}}
                    <div>
                        <x-input-label for="avatar" :value="__('Upload New Photo')" />
                        <input type="file" name="avatar" accept="image/*" class="mt-1 block w-full">
                        <x-input-error :messages="$errors->get('avatar')" class="mt-1" />
                    </div>

                    {{-- Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name"
                                      name="name"
                                      type="text"
                                      class="mt-1 block w-full"
                                      value="{{ old('name', $user->name) }}"
                                      required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email"
                                      name="email"
                                      type="email"
                                      class="mt-1 block w-full"
                                      value="{{ old('email', $user->email) }}"
                                      required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                            {{ __('Save') }}
                        </button>
                    </div>

                </form>

            </div>
        </div>

        {{-- Update Password --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete User --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection