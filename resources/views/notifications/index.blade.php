@extends('layouts.app')

@section('title', 'Notifications')


@section('content')
<div class="flex-col w-full justify-between items-center mb-6">
    <div class="flex justify-between mb-2 gap-6 space-y-2">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
            <div class="p-3 text-md font-bold text-slate-500 border-b border-slate-100 flex justify-between items-center">
                <div class="p-2 mr-2 bg-green-500 rounded-lg shadow-lg shadow-gray-200">
                    <i class="fa-solid fa-inbox text-white"></i>
                </div>
                <span>Notifications</span>
            </div>
        </h1>
        <div class="relative">
            <button id="notif-menu-btn2" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-slate-600 hover:border-slate-300 transition-all shadow-sm active:scale-95">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
            <div id="notif-menu2"
                class="hidden absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                <button id="clear-read-btn2"
                        class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear Read
                </button>
                <button id="mark-all-read2"
                    class="flex items-center gap-2 w-full px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">
                    <!-- check icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                    Mark All as Read
                </button>
                <a href="/settings/preferences"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.983 5.5a1 1 0 011.034 0l1.665.962a1 1 0 00.998 0l1.665-.962a1 1 0 011.034 0l1.732 1a1 1 0 01.5.866v2a1 1 0 01-.5.866l-1.732 1a1 1 0 00-.5.866v2a1 1 0 01-.5.866l-1.732 1a1 1 0 01-1.034 0l-1.665-.962a1 1 0 00-.998 0l-1.665.962a1 1 0 01-1.034 0l-1.732-1a1 1 0 01-.5-.866v-2a1 1 0 00-.5-.866l-1.732-1a1 1 0 01-.5-.866v-2a1 1 0 01.5-.866l1.732-1a1 1 0 00.5-.866v-2a1 1 0 01.5-.866l1.732-1z"/>
                    </svg>
                    Notification Settings
                </a>
            </div>
        </div>
    </div>
    <div class="flex gap-2 mb-2">
        <button id="notif-filter-all2" class="px-2 py-0.5 text-sm font-semibold rounded bg-slate-100">All</button>
        <button id="notif-filter-unread2" class="px-2 py-0.5 text-sm font-semibold rounded bg-indigo-50 text-indigo-700">Unread</button>
    </div>
    <div id="notification-items2" class="bg-white border border-slate-200 rounded-xl">

        <!-- @foreach($notifications as $notification)

            @php
                $subject = $notification->data['subject'] ?? 'No subject';

                $badgeColor = match($subject) {
                    'New' => 'bg-blue-100 text-blue-600',
                    'Completed' => 'bg-green-100 text-green-600',
                    'For Assignment' => 'bg-orange-100 text-orange-600',
                    'Pending Acceptance' => 'bg-red-100 text-red-600',
                    'Task Accepted' => 'bg-green-200 text-green-800',
                    'New Assignment' => 'bg-indigo-100 text-indigo-600',
                    'Attachment Added' => 'bg-purple-100 text-purple-600',
                    'Follow-Up' => 'bg-yellow-100 text-yellow-600',
                    'Update' => 'bg-teal-100 text-teal-600',
                    default => 'bg-slate-100 text-slate-700',
                };
            @endphp

            <a href="{{ $notification->data['url'] ?? '#' }}"
               class="block px-6 py-4 border-b hover:bg-slate-50
               {{ is_null($notification->read_at) ? 'bg-indigo-50 font-semibold' : '' }}">

                <div class="flex justify-between">

                    <div>
                        <p class="text-sm text-slate-800">
                            {{ \App\Models\External::find($notification->data['request_id'])?->subject ?? '' }}
                        </p>

                        <p class="mt-1">
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $badgeColor }}">
                                {{ $subject }}
                            </span>
                        </p>

                        <p class="text-xs text-slate-500 mt-1">
                            {{ $notification->data['message'] ?? '' }}
                        </p>
                    </div>

                    <div class="text-xs text-slate-400">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>

                </div>

            </a>

        @endforeach -->

        <div class="p-4">
            {{ $notifications->links() }}
        </div>

    </div>
</div>
@endsection

@push('scripts')
    <script>

        const notifMenuBtn2 = document.getElementById("notif-menu-btn2");
        const notifMenu2 = document.getElementById("notif-menu2");
        const allBtn2 = document.getElementById("notif-filter-all2");
        const unreadBtn2 = document.getElementById("notif-filter-unread2");
        const clearReadBtn2 = document.getElementById('clear-read-btn2');
        const markAllReadBtn2 = document.getElementById("mark-all-read2");

        let currentFilter2 = 'all';
        
        function filterNotificationsCenter(type) {
            const links = notifItems.querySelectorAll('a');
            let visibleCount = 0;
            links.forEach(a => {
                const isRead = a.dataset.read === "1";
                if (type === 'all') {
                    a.classList.remove('hidden');
                } else if (type === 'unread') {
                    a.classList.toggle('hidden', isRead);
                }
                if (!a.classList.contains('hidden')) {
                    visibleCount++;
                }
            });
            let emptyMsg = notifItems.querySelector('.empty-msg');
            if (!emptyMsg) {
                emptyMsg = document.createElement('p');
                emptyMsg.className = 'empty-msg p-4 text-center text-slate-400 text-xs';
                emptyMsg.innerHTML = `
                    <div class="items-center justify-center flex-col mb-2">
                        <i class="fa-solid text-xl fa-bell-slash"></i>
                        <div>No notifications</div>
                    </div>
                `;
                notifItems.appendChild(emptyMsg);
            }
            emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
            if (type === 'all') {
                allBtn2.classList.add('bg-indigo-50', 'text-indigo-700');
                allBtn2.classList.remove('bg-slate-100', 'text-slate-500');
                unreadBtn2.classList.add('bg-slate-100', 'text-slate-500');
                unreadBtn2.classList.remove('bg-indigo-50', 'text-indigo-700');
            } else {
                unreadBtn2.classList.add('bg-indigo-50', 'text-indigo-700');
                unreadBtn2.classList.remove('bg-slate-100', 'text-slate-500');
                allBtn2.classList.add('bg-slate-100', 'text-slate-500');
                allBtn2.classList.remove('bg-indigo-50', 'text-indigo-700');
            }
        }

        document.addEventListener("click", () => {
            notifMenu2.classList.add("hidden");
        });

        notifMenuBtn2.addEventListener("click", (e) => {
            e.stopPropagation();
            notifMenu2.classList.toggle("hidden");
        });

        allBtn2.addEventListener('click', () => {
            currentFilter2 = 'all';
            filterNotificationsCenter(currentFilter2);
        });

        unreadBtn2.addEventListener('click', () => {
            currentFilter2 = 'unread';
            filterNotificationsCenter(currentFilter2);
        });
        markAllReadBtn2.addEventListener('click', function() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        });

        const loadedNotifications2 = new Set();

        async function fetchNotifications2() {
            try {
                const response = await fetch('{{ route('notifications.all') }}');
                const data = await response.json();
                const notifItems = document.getElementById('notification-items2');
                const unreadCount = data.unread_count;
                notifCount.textContent = unreadCount > 99 ? '99+' : unreadCount;
                notifCount.classList.toggle('hidden', unreadCount === 0);
                data.notifications.forEach(n => {
                    // Skip if already exists
                    if (loadedNotifications2.has(n.id)) return;
                    const a = document.createElement('a');
                    a.id = 'notif-' + n.id;
                    a.href = n.url || '#';
                    a.dataset.read = n.is_new ? "0" : "1";
                    a.className = "block px-4 py-2 transition flex justify-between items-start border-b border-slate-100";
                    if (n.is_new) a.classList.add('font-bold', 'bg-indigo-50');
                    a.innerHTML = `
                        <div class="flex-1">
                            <p class="text-xs text-slate-700 flex items-center gap-2">
                                ${n.subject} 
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold ${getBadgeColor(n.subject)}">
                                    ${n.type}
                                </span>
                            </p>
                            <p class="text-[10px] text-slate-400 truncate">${n.message}</p>
                            <p class="text-[10px] text-slate-400 truncate text-italic">By: ${n.created_by}</p>
                            <p class="text-[9px] text-slate-300">${n.time}</p>
                        </div>
                    `;
                    if (currentFilter2 === 'all' || (currentFilter2 === 'unread' && n.is_new)) {
                        notifItems.prepend(a);
                    }
                    loadedNotifications2.add(n.id);
                });
                filterNotificationsCenter(currentFilter2);

            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        }

        clearReadBtn2.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete all read notifications?')) return;
            try {
                const res = await fetch('/notifications/clear-read', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });
                if (res.ok) {
                    const notifItems = document.getElementById('notification-items2');
                    notifItems.querySelectorAll('a[data-read="1"]').forEach(a => a.remove());
                    const unreadCount = notifItems.querySelectorAll('a[data-read="0"]').length;
                    const notifCount = document.getElementById('notification-count');
                    if (!notifItems.querySelector('a')) {
                        let emptyMsg = notifItems.querySelector('.empty-msg');
                        if (!emptyMsg) {
                            emptyMsg = document.createElement('p');
                            emptyMsg.className = 'empty-msg p-4 text-center text-slate-400 text-xs flex flex-col items-center justify-center gap-2';
                            emptyMsg.innerHTML = `
                                <div class="items-center justify-center flex-col mb-2">
                                    <i class="fa-solid text-xl fa-bell-slash"></i>
                                    <div>No notifications</div>
                                </div>
                            `;
                            notifItems.appendChild(emptyMsg);
                        }
                        emptyMsg.style.display = 'flex'; // ensure flex for vertical alignment
                    }
                } else {
                    console.error('Failed to clear read notifications');
                }
            } catch (err) {
                console.error('Error clearing read notifications:', err);
            }
        });

        setInterval(fetchNotifications2, 10000);
        fetchNotifications2(); 

        function getBadgeColor(subject) {
            switch (subject) {
                case 'New': return 'bg-blue-100 text-blue-600';
                case 'Completed': return 'bg-green-100 text-green-600';
                case 'For Assignment': return 'bg-orange-100 text-orange-600';
                case 'Pending Acceptance': return 'bg-red-100 text-red-600';
                case 'Task Accepted': return 'bg-green-200 text-green-800';
                case 'New Assignment': return 'bg-indigo-100 text-indigo-600';
                case 'Attachment Added': return 'bg-purple-100 text-purple-600';
                case 'Follow-Up': return 'bg-yellow-100 text-yellow-600';
                case 'Update': return 'bg-teal-100 text-teal-600';
                default: return 'bg-slate-100 text-slate-700';
            }
        }

    </script>
@endpush