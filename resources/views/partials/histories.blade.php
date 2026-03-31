<section class="max-w-2xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 shadow-lg shadow-indigo-200 flex items-center justify-center text-white">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-[0.15em]">Request History</h3>
                <p class="text-[11px] text-slate-500 font-medium">Activity log for this transaction</p>
            </div>
        </div>
    </div>

    <!-- Timeline Container -->
    <div class="relative ml-4">
        <!-- Vertical Line -->
        <div class="absolute left-0 top-2 bottom-2 w-0.5 bg-slate-200"></div>

        <div class="space-y-8">
            @forelse($external->histories as $history)
                @php
                    // Dynamic styling map based on status
                    $colorMap = [
                        'Received'  => ['bg' => 'bg-blue-500',   'light' => 'bg-blue-50',   'text' => 'text-blue-700',   'border' => 'border-blue-200',   'icon' => 'fa-arrow-down-long'],
                        'Assigned'  => ['bg' => 'bg-amber-500',  'light' => 'bg-amber-50',  'text' => 'text-amber-700',  'border' => 'border-amber-200',  'icon' => 'fa-user-pen'],
                        'Accepted'  => ['bg' => 'bg-green-500',  'light' => 'bg-green-50',  'text' => 'text-green-700',  'border' => 'border-green-200',  'icon' => 'fa-check-double'],
                        'Task accepted'  => ['bg' => 'bg-green-500',  'light' => 'bg-green-50',  'text' => 'text-green-700',  'border' => 'border-green-200',  'icon' => 'fa-check-double'],
                        'Completed' => ['bg' => 'bg-teal-500',   'light' => 'bg-teal-50',   'text' => 'text-teal-700',   'border' => 'border-teal-200',   'icon' => 'fa-circle-check'],
                        'Updated'   => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'icon' => 'fa-pen-to-square'],
                        'update'   => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'icon' => 'fa-pen-to-square'],
                        'Update Added'   => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'icon' => 'fa-pen-to-square'],
                        'Attached'  => ['bg' => 'bg-pink-500',   'light' => 'bg-pink-50',   'text' => 'text-pink-700',   'border' => 'border-pink-200',   'icon' => 'fa-paperclip'],
                        'Archived'  => ['bg' => 'bg-red-500',   'light' => 'bg-red-50',   'text' => 'text-red-700',   'border' => 'border-red-200',   'icon' => 'fa-box-archive'],
                        'Restored'  => ['bg' => 'bg-sky-500',   'light' => 'bg-sky-50',   'text' => 'text-sky-700',   'border' => 'border-sky-200',   'icon' => 'fa-box-archive'],
                        'Follow-Up'  => ['bg' => 'bg-orange-500',   'light' => 'bg-orange-50',   'text' => 'text-orange-700',   'border' => 'border-orange-200',   'icon' => 'fa-bell'],
                        'Sent Follow-Up'  => ['bg' => 'bg-orange-500',   'light' => 'bg-orange-50',   'text' => 'text-orange-700',   'border' => 'border-orange-200',   'icon' => 'fa-bell'],
                    ];

                    $historyAction = $history->action ?? '';
                    $style = null;

                    // Match status using array_keys
                    foreach ($colorMap as $key => $value) {
                        if (str_starts_with($historyAction, $key)) {
                            $style = $value;
                            break;
                        }
                    }

                    // Default Fallback
                    $style = $style ?? [
                        'bg' => 'bg-slate-500', 
                        'light' => 'bg-slate-50', 
                        'text' => 'text-slate-700', 
                        'border' => 'border-slate-200', 
                        'icon' => 'fa-circle'
                    ];
                @endphp

                <div class="relative pl-10 group">
                    <!-- Timeline Node (Dot) -->
                    <div class="absolute left-[-6px] top-1.5 w-3.5 h-3.5 rounded-full border-2 border-white {{ $style['bg'] }} ring-4 ring-transparent group-hover:ring-slate-100 transition-all"></div>

                    <!-- Content Card -->
                    <div class="flex flex-col gap-1">
                        <!-- Action & User -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wide uppercase {{ $style['light'] }} {{ $style['text'] }} border {{ $style['border'] }}">
                                <i class="fa-solid {{ $style['icon'] }} text-[10px]"></i>
                                {{ $history->action }}
                            </span>
                            
                            @if($history->user)
                                <span class="text-xs font-semibold text-slate-700">
                                    {{ $history->user->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Timestamp -->
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-medium">
                            <i class="fa-regular fa-clock text-[10px]"></i>
                            <span>{{ $history->created_at->diffForHumans() }}</span>
                            <span class="text-slate-300">•</span>
                            <span>{{ $history->created_at->format('M d, Y') }} at {{ $history->created_at->format('h:i A') }}</span>
                        </div>

                        <!-- Remarks Box -->
                        @if($history->remarks)
                            @php
                            $isAttachment = str_starts_with(strtolower(trim(strip_tags($history->action))), 'attached');
                                $remarkClasses = $isAttachment 
                                    ? 'font-mono text-[12px] tracking-tight bg-slate-900 text-slate-300 border-slate-800' 
                                    : 'italic text-[13px] text-slate-600 bg-slate-50/50 border-slate-100';
                                $accentColor = $isAttachment ? 'bg-indigo-400' : 'bg-slate-300';
                            @endphp

                            <div class="md:col-span-2 pt-6 border-t border-slate-100 group">
                                <div class="mt-2 relative">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $accentColor }} opacity-40 rounded-full"></div>
                                    <div class="pl-5 py-3 pr-4 rounded-r-2xl border-y border-r leading-relaxed shadow-sm transition-all {{ $remarkClasses }}">
                                        @if($isAttachment)
                                            <div class="flex items-center gap-2 mb-1 opacity-50">
                                                <i class="fa-solid fa-file-export text-[10px]"></i>
                                                <span class="text-[9px] uppercase font-bold tracking-tighter">Files: </span>
                                            </div>
                                        @endif
                                        {!! $history->remarks !!}
                                    </div>
                                </div>
                            </div>
                            @endif
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 px-6 bg-slate-50 border border-dashed border-slate-200 rounded-2xl">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                        <i class="fa-solid fa-inbox text-slate-300"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">No history recorded</span>
                </div>
            @endforelse
        </div>
    </div>
</section>
