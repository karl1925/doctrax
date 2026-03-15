<!-- Notification Icon -->
<div class="">
    <!-- Bell Button -->
    <button id="notification-btn" class="relative focus:outline-none w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all ml-2">
        <i class="fas fa-bell text-lg text-slate-400"></i>
        <span id="notification-count"
            class="absolute hidden  -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold rounded-full px-1.5">
            @php
                $count = auth()->user()->unreadNotifications->count();
                echo $count > 99 ? '99+' : $count;
            @endphp
        </span>
    </button>

    <!-- Dropdown -->
    <div id="notification-dropdown" class="absolute hidden right-0 mt-2 w-80 bg-white shadow-lg rounded-lg border border-slate-100 min-h-[200px] overflow-hidden z-50">
        <div class="flex items-center justify-between px-2 py-2 bg-slate-200 border-b border-slate-200">
            <div class="p-2 text-md font-bold text-slate-500 border-b border-slate-100 flex justify-between items-center">
                <span>Notifications</span>
            </div>
            <div class="relative">
                <button id="notif-menu-btn"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-500 transition">
                    <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                </button>
                <div id="notif-menu"
                    class="hidden absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                    <button id="mark-all-read"
                        class="flex items-center gap-2 w-full px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7"/>
                        </svg>
                        Mark All as Read
                    </button>
                    <button id="clear-read-btn"
                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear Read
                    </button>
                    <hr>
                    <a href="{{ route('notifications.index') }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V5a2 2 0 00-2-2H6a2 2 0 00-2 2v8m16 0l-2 6H6l-2-6m16 0h-4a2 2 0 01-2-2V9H10v2a2 2 0 01-2 2H4"/>
                        </svg>
                        Open Notifications
                    </a>
                    <a href="/settings/preferences"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.983 5.5a1 1 0 011.034 0l1.665.962a1 1 0 00.998 0l1.665-.962a1 1 0 011.034 0l1.732 1a1 1 0 01.5.866v2a1 1 0 01-.5.866l-1.732 1a1 1 0 00-.5.866v2a1 1 0 01-.5.866l-1.732 1a1 1 0 01-1.034 0l-1.665-.962a1 1 0 00-.998 0l-1.665.962a1 1 0 01-1.034 0l-1.732-1a1 1 0 01-.5-.866v-2a1 1 0 00-.5-.866l-1.732-1a1 1 0 01-.5-.866v-2a1 1 0 01.5-.866l1.732-1a1 1 0 00.5-.866v-2a1 1 0 01.5-.866l1.732-1z"/>
                        </svg>
                        Notification Preferences
                    </a>
                </div>
            </div>
        </div>
        <div class="flex gap-2 items-center px-4 py-2">
            <button id="notif-filter-all" class="px-2 py-0.5 text-xs font-semibold rounded bg-slate-100">All</button>
            <button id="notif-filter-unread" class="px-2 py-0.5 text-xs font-semibold rounded bg-indigo-50 text-indigo-700">Unread</button>
        </div>
        <!-- Notification Items -->
        <div id="notification-items" 
            class="overflow-y-auto" 
            style="max-height: calc(100vh - 8rem);">
        </div>
    </div>
</div>
    