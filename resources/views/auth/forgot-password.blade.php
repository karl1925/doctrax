@extends('layouts.app')

@section('title', 'Password Recovery')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-md">
        <!-- Logo & Branding -->
        <div class="text-center mb-10 justify-center">
            <div class="inline-flex items-center justify-center  p-4 rounded-3xl shadow-2xl shadow-blue-200 mb-6">
                <img src="{{ asset('logo.png') }}" style="height: 80px; width:80px">
            </div>
            <div class="flex items-center gap-2.5 group justify-center text-4xl">
                <div class="bg-indigo-600 p-2 rounded-xl shadow-lg shadow-indigo-200 group-hover:scale-105 transition-transform">
                    <i class="fas fa-file-signature text-white text-3xl"></i>
                </div>
                <span class="font-black tracking-tight text-slate-900 text-4xl">DocTrax</span>
            </div>
            <p class="text-slate-500 font-medium mt-2">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
        </div>

        <!-- Forgot Password Card -->
        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm p-10 md:p-12">
            <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                        class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-600 outline-none transition-all font-bold text-slate-700 placeholder:text-slate-300"
                        placeholder="name@company.com">
                    @error('email')
                        <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

               <div class="pt-4">
                    <button 
                        type="submit" 
                        id="submitBtn"
                        onclick="handleButtonClick(this)"
                        class="w-full relative flex items-center justify-center bg-blue-600 text-white py-5 rounded-[2rem] font-black text-sm shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="btn-text">Email Password Reset Link</span>
                        <svg class="spinner w-5 h-5 absolute animate-spin hidden text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>

                    <!-- Back Button -->
                    <a href="{{ route('login') }}"
                    class="mt-4 w-full flex items-center justify-center bg-gray-200 text-gray-700 py-4 rounded-[2rem] font-bold text-sm hover:bg-gray-300 transition-all uppercase tracking-widest">
                        ← Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

