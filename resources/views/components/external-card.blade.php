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
    $assignedBy = (!$external->latestAssignment) ? '' : ($external->latestAssignment->user_id === auth()->id() ? 'you' : $external->latestAssignment->user?->name ?? 'Unknown');
@endphp
<div 
    x-data="timestampCard({{ $external->history->created_at->timestamp ?? now()->timestamp }})"
    x-init="init()" 
    class="group bg-white border gap-0 border-gray-100 rounded-2xl flex flex-col sm:flex-row items-stretch overflow-hidden transition-all hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1"
>

    <!-- COLUMN 1: DIVISION -->
    <div class="flex flex-row sm:flex-col items-center justify-center px-2 py-1 sm:py-4 text-[10px] font-black uppercase tracking-widest
        {{ $external->division === 'TOD' ? 'bg-blue-100 text-blue-700' : '' }}
        {{ $external->division === 'AFD' ? 'bg-green-100 text-green-700' : '' }}">
        @foreach(str_split($external->division ?? '') as $char)
            <span class="leading-4 sm:leading-5">{{ $char }}</span>
        @endforeach
    </div>

    <!-- COLUMN 2: MAIN INFO -->
    <div class="flex-1 p-3 sm:p-4 flex flex-col gap-1 min-w-0">
        <!-- TOP: PRIORITY + STATUS + REFERENCE -->
        <div class="flex flex-wrap items-center gap-2 text-xs sm:text-[12px]">
            @if($external->priority === 'urgent')
                <span class="font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full uppercase">Urgent</span>
            @elseif($external->priority === 'high')
                <span class="font-black text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full uppercase">High</span>
            @endif
            <span class="font-black text-{{ $color }}-600 uppercase tracking-tighter bg-{{ $color }}-50 px-1 py-0 rounded">{{ $stat }}</span>
            <span class="font-bold text-gray-700 uppercase truncate max-w-[120px] sm:max-w-xs">{{ $external->reference ?? 'No Ref' }}</span>
        </div>
        <!-- SUBJECT -->
        <div class="text-sm sm:text-base font-black text-slate-800 tracking-tight truncate group-hover:text-indigo-600">
            {{ $external->subject }}
        </div>
        <!-- MOBILE BADGES -->
        <div class="flex items-center gap-3 mt-1 text-[10px] text-gray-500">
            <div class="flex items-center gap-1">
                <i class="fa-solid fa-paperclip"></i>
                <span>{{ $external->attachments_count ?? 0 }}</span>
            </div>
            <div class="flex items-center gap-1">
                <i class="fa-solid fa-repeat"></i>
                <span>{{ $external->updates_count ?? 0 }}</span>
            </div>
            <div class="flex items-center gap-1">
                <i class="fa-solid fa-link"></i>
                <span>{{ $external->links_count ?? 0 }}</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-col p-3 sm:p-4 items-end gap-2 w-full sm:w-auto">
        @if(!$external->status === 'pending')
            <!-- ASSIGNED -->
            <div class="flex items-center gap-1.5 text-xs sm:text-[12px] text-slate-500">
                <i class="fa-solid fa-user text-slate-400"></i>
                <span>
                    Assigned to 
                    <span class="font-semibold text-slate-700">{{ $assignedUser }}</span>
                    <span class="font-normal italic">by {{ $assignedBy }}</span>
                </span>
            </div>
        @endif

        <!-- LAST UPDATE -->
        @if($historyText)
        <div class="text-[10px] sm:text-xs text-gray-400 truncate">
            <i class="fa-solid fa-clock text-gray-400 mr-1"></i>
            {{ $historyText }}
            <span x-text="diffText" class="text-gray-500"></span>
        </div>
        @endif

        <!-- DUE -->
        <div class="text-left sm:text-right">
            <p class="text-[10px] sm:text-[11px] font-black uppercase
                {{ $external->target_date && $external->target_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                {{ $external->target_date ? $external->target_date->diffForHumans() : 'No deadline' }}
            </p>
        </div>
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