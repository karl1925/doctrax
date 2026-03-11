@extends('layouts.app')

@section('title', 'Login to DocTrax')

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
            <p class="text-slate-500 font-medium mt-2">Sign in to manage your document workflows</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm p-10 md:p-12">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
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

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center ml-1">
                        <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Forgot?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required 
                        class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-600 outline-none transition-all font-bold text-slate-700 placeholder:text-slate-300"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center ml-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded-lg border-slate-200 text-blue-600 shadow-sm focus:ring-blue-50 w-5 h-5 transition-all">
                        <span class="ml-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Keep me signed in</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white py-5 rounded-[2rem] font-black text-sm shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] uppercase tracking-widest">
                        Sign In to DocTrax
                    </button>
                    <a href="{{ route('google.redirect') }}" class="btn btn-google w-full mt-4 bg-red-600 text-white py-3 rounded-[2rem] font-black text-sm shadow-xl shadow-red-100 hover:bg-red-700 transition-all active:scale-[0.98] uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fa-brands fa-google"></i> Login with DICT GovMail
                    </a> 
                </div>
            </form>
        </div>

        <!-- Footer Link -->
        <p class="text-center mt-8 text-sm font-bold text-slate-400">
            Don't have an account? Please contact <strong>miss.region2@dict.gov.ph</strong>.
            <!-- <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Request access</a> -->
        </p>
    </div>
</div>
@endsection