


<div class="max-w-5xl mx-auto w-full my-12">
<div class="bg-slate-900/90 backdrop-blur-xl rounded-[2rem] sm:rounded-[3rem] p-3 sm:p-4 pl-6 sm:pl-10 pr-3 sm:pr-4 shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 ring-1 ring-white/5">
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 sm:gap-6">

            {{-- Status Meta (Hidden on tiny screens, visible on md+) --}}
            <div class="hidden lg:block">
                @php
                    $meta = [
                        'pending'   => ['title' => 'Ready to proceed?', 'desc' => 'Validate and forward this request to ORD.'],
                        'forwarded' => ['title' => 'Next Steps', 'desc' => 'Assign this request to the respective division.'],
                        'endorsed'  => ['title' => 'Ownership', 'desc' => 'Accept responsibility or delegate to personnel.'],
                        'assigned'  => ['title' => 'Task Received', 'desc' => 'Accept the task or collaborate with colleagues.'],
                        'accepted'  => ['title' => 'In Progress', 'desc' => 'Finalize task or forward for peer review.'],
                    ][$external->status] ?? ['title' => 'Action Required', 'desc' => 'No further action needed.'];
                @endphp
                <h4 class="text-white text-sm font-bold tracking-tight">{{ $meta['title'] }}</h4>
                <p class="text-slate-400 text-[11px] leading-tight max-w-[240px]">{{ $meta['desc'] }}</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3 w-full sm:w-auto">
                
                @if($external->status === "pending")
                    <button type="button" onclick="openActionModal()" class="w-full sm:w-auto flex items-center justify-center gap-3 px-8 py-4 text-[13px] font-black uppercase tracking-wider text-white bg-blue-600 hover:bg-blue-500 rounded-2xl sm:rounded-[1.8rem] shadow-lg shadow-blue-600/25 transition-all active:scale-95 group">
                        <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        Forward to ORD
                    </button>

                @elseif($external->status === "forwarded")
                    <button type="button" onclick="openActionModal()" class="w-full sm:w-auto flex items-center justify-center gap-3 px-8 py-4 text-[13px] font-black uppercase tracking-wider text-white bg-indigo-600 hover:bg-indigo-500 rounded-2xl sm:rounded-[1.8rem] shadow-lg shadow-indigo-600/25 transition-all active:scale-95 group">
                        <i class="fa-solid fa-share-nodes group-hover:scale-110 transition-transform"></i>
                        Endorse to Division
                    </button>

                @elseif($external->status === "endorsed")
                    <form id="acceptForm" action="{{ route('externals.accept', $external->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf @method('PUT')
                        <button type="submit" class="w-full flex items-center justify-center gap-3 px-8 py-4 text-[13px] font-black uppercase tracking-wider text-white bg-emerald-600 hover:bg-emerald-500 rounded-2xl sm:rounded-[1.8rem] shadow-lg shadow-emerald-600/20 transition-all active:scale-95 group">
                            <i class="fa-solid fa-check-double group-hover:rotate-12 transition-transform"></i>
                            Accept
                        </button>
                    </form>
                    <button type="button" onclick="openActionModal()" class="flex-1 sm:flex-none flex items-center justify-center gap-3 px-8 py-4 text-[13px] font-black uppercase tracking-wider text-white bg-slate-800 hover:bg-slate-700 rounded-2xl sm:rounded-[1.8rem] border border-white/5 transition-all active:scale-95 group">
                        <i class="fa-solid fa-user-plus group-hover:translate-x-0.5 transition-transform"></i>
                        Assign
                    </button>

                @elseif($external->status === "assigned")
                    <form action="{{ route('externals.accept', $external->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf @method('PUT')
                        <button type="submit" class="w-full flex items-center justify-center gap-3 px-8 py-4 text-[13px] font-black uppercase tracking-wider text-white bg-emerald-600 hover:bg-emerald-500 rounded-2xl sm:rounded-[1.8rem] shadow-lg shadow-emerald-600/20 transition-all active:scale-95 group">
                            <i class="fa-solid fa-hand-holding-check"></i>
                            Accept
                        </button>
                    </form>
                    <button type="button" onclick="openActionModal()" class="flex-1 sm:flex-none flex items-center justify-center gap-3 px-8 py-4 text-[13px] font-black uppercase tracking-wider text-white bg-slate-800 hover:bg-slate-700 rounded-2xl sm:rounded-[1.8rem] border border-white/5 transition-all active:scale-95 group">
                        Forward
                    </button>

                @elseif($external->status === "accepted")
                    <button type="button" onclick="openActionModal()" class="flex-1 sm:flex-none flex items-center justify-center gap-3 px-6 py-4 text-[13px] font-black uppercase tracking-wider text-white bg-slate-800 hover:bg-slate-700 rounded-2xl sm:rounded-[1.8rem] transition-all active:scale-95 group">
                        <i class="fa-solid fa-share"></i>
                        Collaborate
                    </button>
                    <form action="{{ route('externals.complete', $external->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf @method('PUT')
                        <button type="submit" class="w-full flex items-center justify-center gap-3 px-8 py-4 text-[13px] font-black uppercase tracking-wider text-white bg-blue-600 hover:bg-blue-500 rounded-2xl sm:rounded-[1.8rem] shadow-lg shadow-blue-600/30 transition-all active:scale-95 group">
                            <i class="fa-solid fa-circle-check group-hover:scale-110 transition-transform"></i>
                            Complete
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>


</div>