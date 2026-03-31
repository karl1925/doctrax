<div x-data="{ 
        open:false, 
        menu:false, 
        filter:'unread' 
    }" 
    class="">

    <!-- Bell Button -->
    <button id="notification-btn"
        @click="open = !open"
        :class="open ? 'text-indigo-600 bg-indigo-50' : 'text-slate-400'"
        class="relative w-10 h-10 flex items-center justify-center rounded-xl hover:text-indigo-600 hover:bg-indigo-50 transition-all">

        <i class="fas fa-bell text-lg"></i>

        <span id="notification-count"
            class="hidden absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold rounded-full px-1.5"
            x-show="$refs.count.innerText != '0'"
        >
            <span x-ref="count">
                @php
                    $count = auth()->user()->unreadNotifications->count();
                    echo $count > 99 ? '99+' : $count;
                @endphp
            </span>
        </span>
    </button>

    <!-- Dropdown -->
    <div id="notif-drop"
        x-show="open"
        @click.outside="open=false; menu=false"
        x-transition
        class="absolute max-h-[calc(100vh-65px)] right-0 mt-2 min-w-80 bg-white shadow-lg rounded-xl border border-slate-100 min-h-[200px] overflow-x-auto overflow-y-hidden z-50">

        <!-- Header -->
        <div class="flex items-center justify-between px-3 py-2 bg-slate-100 border-b">

            <span class="font-bold text-slate-600 text-sm">
                Notifications
            </span>

            <!-- Three Dots -->
            <div class="relative">

                <button
                    @click.stop="menu = !menu"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-200 text-slate-500">
                    <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                </button>

                <!-- Menu -->
                <div
                    x-show="menu"
                    @click.outside="menu=false"
                    x-transition
                    class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-lg shadow-lg"
                    style="z-index: 9000">

                    <button id="mark-all-read"
                        class="flex items-center gap-2 w-full px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        ✓ Mark All as Read
                    </button>

                    <button id="clear-read-btn"
                        class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-slate-100">
                        ✕ Clear Read
                    </button>

                    <hr>

                    <a href="/settings/preferences"
                        class="block px-4 py-2 text-sm hover:bg-slate-100">
                        Notification Preferences
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex gap-2 items-center px-4 py-2">

            <button id="notif-filter-all"
                @click="filter='all'"
                :class="filter=='all'
                    ? 'bg-indigo-50 text-indigo-700'
                    : 'bg-slate-100'"
                class="px-2 py-0.5 text-xs font-semibold rounded">
                All
            </button>

            <button id="notif-filter-unread"
                @click="filter='unread'"
                :class="filter=='unread'
                    ? 'bg-indigo-50 text-indigo-700'
                    : 'bg-slate-100'"
                class="px-2 py-0.5 text-xs font-semibold rounded">
                Unread
            </button>

        </div>

        <!-- Notification Items -->
        <div class="flex items-center px-4 py-2 text-[14px] font-bold">
            New
        </div>
        <div id="new-notification-items" class="flex-1"
             style="z-index: 8000;">
        </div>
        <div id="new-empty" class="flex-1 p-4 text-center text-slate-400 text-xs"
             style="z-index: 8000; display: none;">
             <div class="items-center justify-center flex-col mb-2">
                <i class="fa-solid text-xl fa-bell-slash"></i>
                <div>No new notifications</div>
            </div>
        </div>
        
        <div id="earlier-label" class="flex items-center px-4 py-2 text-[14px] font-bold">
            Earlier
        </div>
        <div id="earlier-empty" class="flex-1 p-4 text-center text-slate-400 text-xs"
             style="z-index: 8000; display: none;">
             <div class="items-center justify-center flex-col mb-2">
                <i class="fa-solid text-xl fa-bell-slash"></i>
                <div>No notifications</div>
            </div>
        </div>
        <div id="earlier-notification-items" class="flex-1"
             style="z-index: 8000;">
        </div>
        
        <button id="fetch-more-btn" class="block w-full px-4 py-2 text-sm bg-indigo-50 text-indigo-700 rounded mt-2">
            See More
        </button>

        <!-- Common Item Menu -->
        <div id="common-item-menu" class="hidden absolute w-32 bg-white border rounded shadow z-50 text-sm">
            <ul>
                <li class="px-3 py-2 hover:bg-slate-100 cursor-pointer" onclick="menuAction('view')">View</li>
                <li class="px-3 py-2 hover:bg-slate-100 cursor-pointer" onclick="menuAction('mark')">Mark as Read</li>
                <li class="px-3 py-2 hover:bg-slate-100 cursor-pointer text-red-500" onclick="menuAction('delete')">Delete</li>
            </ul>
        </div>
        
    </div>
    
</div>