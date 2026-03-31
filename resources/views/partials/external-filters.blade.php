<form method="GET" action="{{ $action }}" class="w-full max-w-4xl ml-auto mb-5" id="external-filters-form">
    <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-4 p-2 
                bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 
                shadow-sm rounded-2xl transition-all hover:shadow-md focus-within:ring-1 focus-within:ring-indigo-100">
        
        <!-- Search Input Section -->
        <div class="relative flex-grow group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-slate-400 dark:text-slate-400/80 
                          group-focus-within:text-indigo-500 transition-colors"></i>
            </div>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search criteria..."
                class="block w-full pl-11 pr-4 py-3 bg-transparent border-none 
                       text-slate-700 dark:text-slate-200 text-sm placeholder-slate-400 dark:placeholder-slate-500 focus:ring-0"
            >
        </div>

        <!-- Divider (Desktop Only) -->
        <div class="hidden lg:block w-px h-8 bg-slate-200 dark:bg-slate-600"></div>

        <!-- Priority Filters Section -->
        <div class="flex items-center px-2 py-1 bg-slate-50 dark:bg-slate-700 rounded-xl lg:bg-transparent overflow-x-auto no-scrollbar">
            <div class="flex gap-1 w-full whitespace-nowrap">
                @php
                    $priority = request('priority');
                    $options = [
                        ['val' => '', 'label' => 'All', 'color' => 'indigo'],
                        ['val' => 'normal', 'label' => 'Normal', 'color' => 'slate'],
                        ['val' => 'high', 'label' => 'High', 'color' => 'orange'],
                        ['val' => 'urgent', 'label' => 'Urgent', 'color' => 'rose']
                    ];
                @endphp

                @foreach($options as $opt)
                    <label class="relative flex-1">
                        <input 
                            type="radio" 
                            name="priority" 
                            value="{{ $opt['val'] }}" 
                            {{ $priority == $opt['val'] ? 'checked' : '' }} 
                            class="peer sr-only"
                        >
                        <div class="px-4 py-2 text-xs font-semibold rounded-lg cursor-pointer transition-all text-center
                            text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600
                            peer-checked:bg-white dark:peer-checked:bg-slate-700 
                            peer-checked:text-indigo-600 peer-checked:dark:text-indigo-400 
                            peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-slate-200/60 dark:peer-checked:ring-slate-500/60
                            @if($opt['val'] == 'urgent') peer-checked:text-rose-600 dark:peer-checked:text-rose-400 @endif
                            @if($opt['val'] == 'high') peer-checked:text-orange-600 dark:peer-checked:text-orange-400 @endif">
                            {{ $opt['label'] }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('external-filters-form');
        
        // Submit form on pressing Enter in search input
        form.querySelector('input[name="search"]').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                form.submit();
            }
        });

        // Submit form when a priority radio is clicked
        form.querySelectorAll('input[name="priority"]').forEach(radio => {
            radio.addEventListener('change', () => {
                form.submit();
            });
        });
    });
</script>

<style>
    /* Hide scrollbar for the radio group on mobile */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>