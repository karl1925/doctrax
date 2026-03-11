@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');

.construction-wrapper {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #0f172a;
    color: #f8fafc;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: 1.5rem;
}

.gradient-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    background: radial-gradient(circle at 20% 30%, #1e293b 0%, #0f172a 100%);
}

.blob {
    position: absolute;
    width: 500px;
    height: 500px;
    background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    filter: blur(80px);
    border-radius: 50%;
    opacity: 0.15;
    z-index: 2;
    animation: move 20s infinite alternate;
}

@keyframes move {
    from { transform: translate(-10%, -10%) scale(1); }
    to { transform: translate(20%, 20%) scale(1.2); }
}

.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 2rem;
}

.text-gradient {
    background: linear-gradient(to right, #fff, #94a3b8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.content-container {
    position: relative;
    z-index: 10;
    max-width: 56rem;
    width: 100%;
    text-align: center;
}
</style>

<div class="construction-wrapper">
    <div class="gradient-bg"></div>
    <div class="blob"></div>
    <div class="blob" style="right: -10%; bottom: -10%; background: #3b82f6; animation-delay: -5s;"></div>

    <div class="content-container space-y-12">
        <!-- Brand Identity -->
        <div class="flex flex-col items-center gap-4">
            <div class="w-16 h-16 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-indigo-500/20 rotate-3 transition-transform hover:rotate-0">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-indigo-400">Launch Sequence Initiated</span>
        </div>

        <!-- Messaging -->
        <div class="space-y-4">
            <h1 class="text-4xl md:text-7xl font-black text-white tracking-tight leading-tight">
                Something Big is <br>
                <span class="text-gradient">Underway.</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-xl mx-auto font-medium">
                Our team is currently polishing the final details. We'll be ready to unveil the new platform very soon.
            </p>
        </div>

        <!-- Countdown -->
        <div class="grid grid-cols-4 gap-3 md:gap-6 max-w-2xl mx-auto">
            @foreach(['Days' => 'days', 'Hours' => 'hours', 'Minutes' => 'minutes', 'Seconds' => 'seconds'] as $label => $id)
                <div class="glass-card p-4 md:p-6">
                    <div id="{{ $id }}" class="text-2xl md:text-4xl font-black text-white">00</div>
                    <div class="text-[9px] md:text-[11px] uppercase font-black tracking-widest text-slate-500 mt-1">{{ $label }}</div>
                </div>
            @endforeach
        </div>

        <!-- Subscriber Form -->
        <div class="max-w-md mx-auto">
            @if(session('success'))
                <p class="mt-4 text-emerald-400 text-xs font-bold">
                    ✓ {{ session('success') }}
                </p>
            @endif
        </div>
    </div>

    <!-- Footer Meta -->
    <div class="absolute bottom-8 left-0 right-0 text-center opacity-30 pointer-events-none z-10">
        <span class="text-[9px] font-black uppercase tracking-[1em] text-slate-400">System Status: Optimizing</span>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const launchDate = new Date().getTime() + (10 * 24 * 60 * 60 * 1000); // 10 days from now

    function updateTimer() {
        const now = new Date().getTime();
        const diff = launchDate - now;

        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
        const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const s = Math.floor((diff % (1000 * 60)) / 1000);

        const els = { days: d, hours: h, minutes: m, seconds: s };
        for (const [id, val] of Object.entries(els)) {
            const el = document.getElementById(id);
            if (el) el.innerText = val.toString().padStart(2, '0');
        }
    }

    setInterval(updateTimer, 1000);
    updateTimer();
});
</script>
@endpush

@endsection