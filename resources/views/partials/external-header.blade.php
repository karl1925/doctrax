<header class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-slate-100">
    <div class="flex-1 min-w-0 space-y-3">
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ url()->previous() }}"
                class="group flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm"
                title="Go Back">
                <i class="fa-solid fa-arrow-left text-xs group-hover:-translate-x-0.5 transition-transform"></i>
            </a>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ref:</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-mono text-[11px] font-bold border border-slate-200/50">
                    {{ $external->reference ?? 'N/A' }}
                </span>
            </div>
            @if($external->priority === 'urgent')
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 border border-rose-100 text-rose-600 animate-pulse">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-tight">Urgent Priority</span>
                </div>
            @elseif($external->priority === 'high')
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-100 text-amber-600">
                    <i class="fa-solid fa-circle text-[6px]"></i>
                    <span class="text-[10px] font-black uppercase tracking-tight">High Priority</span>
                </div>
            @endif
        </div>
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-[1.1] truncate group" title="{{ $external->subject }}">
            {{ $external->subject }}
        </h1>
    </div>

    @if($external->status === 'completed' && $external->creator->id === auth()->user()->id)
        <div class="flex items-center gap-2 self-start lg:self-center">
            <div class="h-8 w-px bg-slate-200 mx-1 hidden sm:block"></div>

            @if(!$external->trashed())
                {{-- Archive Button --}}
                <form action="{{ route('externals.destroy', $external->id) }}" 
                    method="POST" 
                    onsubmit="return confirm('Archive this Request?');"
                    class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="group flex items-center gap-2 px-4 py-2.5 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all shadow-sm shadow-rose-600/5 active:scale-95">
                        <i class="fa-solid fa-box-archive text-sm group-hover:shake transition-transform"></i>
                        <span class="text-xs font-bold">Archive</span>
                    </button>
                </form>
            @else
                {{-- Restore Button --}}
                <form action="{{ route('externals.restore', $external->id) }}" 
                    method="POST" 
                    onsubmit="return confirm('Restore this Request?');"
                    class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="group flex items-center gap-2 px-4 py-2.5 bg-green-50 border border-green-100 rounded-xl text-green-600 hover:bg-green-600 hover:text-white hover:border-green-600 transition-all shadow-sm shadow-green-600/5 active:scale-95">
                        <i class="fa-solid fa-box-archive-arrow-up text-sm group-hover:shake transition-transform"></i>
                        <span class="text-xs font-bold">Restore</span>
                    </button>
                </form>
            @endif
        </div>
    @elseif($external->status === 'pending')
        <div class="flex items-center gap-2 self-start lg:self-center">
            <!-- <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm active:scale-95">
                <i class="fa-solid fa-print text-sm opacity-70"></i>
                <span class="text-xs font-bold">Print</span>
            </button> -->

            <div class="h-8 w-px bg-slate-200 mx-1 hidden sm:block"></div>

            {{-- Trash / Cancel Form --}}
            <form action="{{ route('externals.destroy', $external->id) }}" 
                method="POST" 
                onsubmit="return confirm('Cancel this Request?');"
                class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="group flex items-center gap-2 px-4 py-2.5 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all shadow-sm shadow-rose-600/5 active:scale-95">
                    <i class="fa-solid fa-trash-can text-sm group-hover:shake transition-transform"></i>
                    <span class="text-xs font-bold">Cancel</span>
                </button>
            </form>
            <!-- <button class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-slate-600 hover:border-slate-300 transition-all shadow-sm active:scale-95">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button> -->
        </div>
    @endif
</header>