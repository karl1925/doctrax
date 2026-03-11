<!-- Header Section -->

<div class="flex items-center gap-4 mb-6">
    <div class="w-10 h-10 rounded-xl bg-indigo-600 shadow-lg shadow-indigo-100 flex items-center justify-center text-white">
        <i class="fa-solid fa-circle-info text-sm"></i>
    </div>
    <div>
        <h3 class="text-sm font-black text-slate-900 uppercase tracking-[0.15em]">Request Details</h3>
        <p class="text-[11px] text-slate-500 font-medium">Primary information and administrative overview</p>
    </div>
</div>

<div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm transition-all hover:shadow-xl hover:shadow-slate-200/50">

<!-- Dynamic Deadline Notification Banner -->
@if($external->target_date)
    @php
        $isPast = $external->target_date->isPast();
        $isSoon = !$isPast && $external->target_date->diffInDays(now()) <= 3;
        $bannerClass = $isPast ? 'bg-rose-50 text-rose-700 border-rose-100' : ($isSoon ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-slate-50 text-slate-600 border-slate-100');
        $iconClass = $isPast ? 'fa-triangle-exclamation' : ($isSoon ? 'fa-clock' : 'fa-calendar-day');
    @endphp
    <div class="flex items-center justify-between px-8 py-3 border-b {{ $bannerClass }}">
        <div class="flex items-center gap-3">
            <i class="fa-solid {{ $iconClass }} text-sm"></i>
            <span class="text-[10px] font-black uppercase tracking-[0.1em]">
                {{ $isPast ? 'Past Due Date' : ($isSoon ? 'Approaching Deadline' : 'Scheduled Target Date') }}
            </span>
        </div>
        <span class="text-[10px] font-bold">
            {{ $external->target_date->diffForHumans() }}
        </span>
    </div>
@endif

<div class="max-w-2xl mx-auto p-6 md:p-10 bg-white rounded-[2rem] shadow-sm border border-slate-100">
<div class="flex flex-col space-y-10">

    <!-- Agency Section -->
    <div class="group relative flex gap-6">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 transition-transform group-hover:scale-110">
                <i class="fa-solid fa-building"></i>
            </div>
            <div class="w-0.5 h-full bg-slate-100 mt-2"></div>
        </div>
        <div class="pb-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-hover:text-indigo-500 transition-colors">
                Requesting Agency
            </label>
            <h3 class="text-xl font-black text-slate-900 tracking-tight leading-tight">
                {{ $external->agency }}
            </h3>
            <p class="text-xs font-bold text-slate-400 uppercase mt-1">Official Document Origin</p>
        </div>
    </div>

    <!-- Point of Contact -->
    <div class="group relative flex gap-6">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-address-card"></i>
            </div>
            <div class="w-0.5 h-full bg-slate-100 mt-2"></div>
        </div>
        <div class="flex-1 pb-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-hover:text-indigo-500 transition-colors">
                Point of Contact
            </label>
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-xl border border-slate-100 group-hover:border-indigo-100 group-hover:bg-white transition-all">
                <span class="font-bold text-slate-700 text-sm tracking-tight">{{ $external->contact }}</span>
            </div>
        </div>
    </div>

    <!-- Deadline & Target -->
    <div class="group relative flex gap-6">
        <div class="flex flex-col items-center">
            @php
                $isOverdue = $external->target_date && $external->target_date->isPast();
            @endphp
            <div class="w-10 h-10 rounded-2xl {{ $isOverdue ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center transition-transform group-hover:rotate-12">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="w-0.5 h-full bg-slate-100 mt-2"></div>
        </div>
        <div class="pb-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">
                Deadline & Target
            </label>
            <div class="flex flex-col">
                <div class="flex items-baseline gap-2">
                    <span class="text-lg font-black {{ $isOverdue ? 'text-rose-600' : 'text-slate-900' }} tracking-tighter">
                        {{ $external->target_date?->format('F d, Y') ?? 'No deadline' }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $external->target_date?->format('l') }}</span>
                </div>
                <div class="mt-1">
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $isOverdue ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $external->target_date ? $external->target_date->diffForHumans() : 'Date not specified' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Info -->
    <div class="group relative flex gap-6">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 transition-transform group-hover:scale-110">
                <i class="fa-solid fa-file-import"></i>
            </div>
            <div class="w-0.5 h-full bg-slate-100 mt-2"></div>
        </div>
        <div class="pb-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-hover:text-emerald-600 transition-colors">
                Received By
            </label>
            <div class="flex items-center gap-3">
                <div class="text-[13px] font-black text-slate-800">{{ $external->creator->name }}</div>
                <span class="text-slate-300">•</span>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tighter">
                    {{ $external->created_at->format('M d, Y') }} at {{ $external->created_at->format('H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Remarks -->
    <div class="group relative flex gap-6">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center text-white transition-transform group-hover:-rotate-12">
                <i class="fa-solid fa-quote-left text-[10px]"></i>
            </div>
        </div>
        <div class="flex-1 bg-slate-50 rounded-2xl p-6 border border-slate-100 group-hover:bg-indigo-50/30 group-hover:border-indigo-100 transition-all">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                Executive Summary / Remarks
            </label>
            <p class="text-slate-600 leading-relaxed text-sm font-medium italic">
                "{{ $external->remarks ?? 'No additional remarks provided for this request.' }}"
            </p>
        </div>
    </div>

</div>


</div>


</div>