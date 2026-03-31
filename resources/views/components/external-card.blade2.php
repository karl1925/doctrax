@php
    // Status & color
    $statusMap = [
        'pending'   => ['label' => 'For Assignment', 'color' => 'rose'],
        'assigned'  => ['label' => 'Pending Acceptance', 'color' => 'red'],
        'accepted'  => ['label' => 'Accepted', 'color' => 'green'],
        'completed' => ['label' => 'Completed', 'color' => 'blue'],
    ];
    $stat = $statusMap[$external->status]['label'] ?? 'Unknown';
    $color = $statusMap[$external->status]['color'] ?? 'gray';

    // Last history
    $lastHistory = $external->history;
    if ($lastHistory) {
        $param = match($lastHistory->action) {
            'Attached' => ' ' . (int)$lastHistory->param . ' ' . \Illuminate\Support\Str::plural('file', (int)$lastHistory->param) . ' ',
            'Followed-Up' => ' ',
            default => ' this task ',
        };

        $historyUser = $lastHistory->user_id === auth()->id() ? 'You' : ucfirst($lastHistory->user->name);
        $historyText = "{$historyUser} " . strtolower($lastHistory->action) . $param;
    } else {
        $historyText = '';
    }

    // Assigned
    $assignedUser = $external->assigned_to === auth()->id() ? 'You' : $external->assignedTo?->name ?? 'Unknown';
    $assignedBy = $external->latestAssignment->user_id === auth()->id() ? 'you' : $external->latestAssignment->user?->name ?? 'Unknown';
@endphp

<div x-data="timestampCard({{ $external->history->created_at->timestamp ?? now()->timestamp }})"
     x-init="init()">

    <div class="relative w-full  m-2 p-2 hover:translate-y-[-5px] transition-all">
        <div class="m-2 flex w-full flex-col gap-0 overflow-hidden rounded-2xl border border-gray-300 bg-white shadow-sm transition hover:shadow-lg">
            <div class="relative flex max-h-10 flex-col items-center justify-center p-3
                        {{ $external->division === 'TOD' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $external->division === 'AFD' ? 'bg-green-100 text-green-700' : '' }}">
                <div class="flex translate-y-5 justify-center">
                    <img src="https://i.pravatar.cc/100?img=12" class="h-12 w-12 rounded-full border border-gray-200 object-cover" />
                </div>
                <span class="absolute right-2 text-[30px] font-black text-gray-200">{{ $external->division }}</span>
            </div>
            <div class="w-full truncate pt-7 text-center text-[11px] font-black text-gray-800 uppercase">{{ $external->agency }}: <span class="font-normal">{{ $external->contact }}</span></div>
            <div class="text-md flex w-full p-2 pt-0 justify-center leading-none font-black tracking-tight text-indigo-600">
                {{ $external->subject }}
            </div>
            <div class="mb-2 flex flex-row justify-center font-mono text-[8px]"><span class="rounded-sm bg-gray-200 px-2 py-0.5">{{ $external->reference ?? 'No Reference' }}</span></div>
                <div class="mb-2 flex flex-row items-center justify-around px-5">
                    <span class="flflex flex-col text-xs font-bold text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>{{ $external->histories->count() }}
                    </span>
                    <span class="flflex flex-col text-xs font-bold text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                        </svg>{{ $external->attachments_count ?? 0 }}
                    </span>
                    <span class="flex flex-col text-xs font-bold text-center ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                        </svg>3
                </div>
                <div class="mb-2 flex flex-row justify-center font-mono text-{{ $color }}-600 text-[10px] font-black"><span class="rounded-sm bg-{{ $color }}-50 px-2 py-0.5 uppercase">{{ $stat }}</span></div>
                <div class="flex flex-row items-center gap-2 px-2 py-0 text-[10px] italic">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                    </svg>
                    <span>Received 2 days ago</span>
                </div>
    <div class="flex flex-row items-center gap-2 p-2 px-2 py-0 text-[10px] italic">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
        <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
      </svg>
      <span>Due {{ $external->target_date ? $external->target_date->diffForHumans() : 'No deadline' }}</span>
    </div>
    <div class="flex flex-row items-center gap-2 p-2 px-2 py-0 text-[10px] italic">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
        <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
      </svg>
      <span>Assigned to 
                <span class="font-semibold text-slate-700">{{ $assignedUser }}</span>
                <span class="font-normal italic">by {{ $assignedBy }}</span>
        </span>
    </div>
    <!-- MAIN CONTENT -->
    <div class="flex h-full w-full flex-col">
      <div class="mt-auto truncate p-2 pt-0 text-[10px] text-gray-400 italic sm:text-xs">
        @if($historyText)
        <div class="text-[10px] sm:text-xs text-gray-400 truncate">
            <i class="fa-solid fa-clock text-gray-400 mr-1"></i>
            {{ $historyText }}
            <span x-text="diffText" class="text-gray-500"></span>
        </div>
        @endif
      </div>
      <!-- BUTTONS -->
    <div class="flex flex-col items-center justify-center gap-2 sm:gap-1 w-full sm:w-auto mt-1 pe-4">
        @if($external->assigned_to !== auth()->id() && $external->status !== 'completed')
            <button 
                type="button" 
                class="w-full flex justify-center items-center px-3 sm:px-4 py-1 sm:py-2 bg-indigo-600 text-white text-[10px] sm:text-xs font-black uppercase rounded-xl hover:bg-indigo-700 hover:shadow-md transition"
                @click="openFollowUpModal({{ $external->id }})">
                Follow-up
            </button>
        @endif
        <a href="{{ $external->assigned_to === auth()->id() ? route('externals.mytasks.verify', $external->id) : route('externals.monitoring.show', $external->id) }}" 
            class="w-full flex justify-center items-center px-3 sm:px-4 py-1 sm:py-2 bg-gray-600 text-white text-[10px] sm:text-xs font-black uppercase rounded-xl hover:bg-gray-700 hover:shadow-md transition">
            Review
        </a>
    </div>
    </div>

    <!-- URGENCY (FLOATING) -->
    <div class="absolute top-[18px] left-[12px]">
      <span class="inline-block -skew-x-12 transform bg-rose-500 px-3 py-0.5 text-[10px] font-black text-white uppercase shadow-sm"> Urgent </span>
    </div>
  </div>
</div>




