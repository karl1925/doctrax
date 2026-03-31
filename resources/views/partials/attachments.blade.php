<section class="max-w-2xl">
<!-- Header -->
<div class="flex items-center justify-between mb-8">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-xl bg-indigo-600 shadow-lg shadow-indigo-200 flex items-center justify-center text-white">
<i class="fa-solid fa-paperclip"></i>
</div>
<div>
<h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-[0.15em]">Attachments</h3>
<p class="text-[11px] text-slate-500 dark:text-whitefont-medium">Supporting documents for this request</p>
</div>
</div>
<span class="text-[10px] font-black text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full uppercase tracking-wider">
{{ count($external->attachments) }} Files
</span>
</div>

<!-- Attachments Grid/List -->
<div class="space-y-3">
    @php
        if (!function_exists('humanFileSize')) {
            function humanFileSize($bytes, $decimals = 1): string {
                if ($bytes <= 0) return '0 B';
                $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                $factor = floor((strlen((string) $bytes) - 1) / 3);
                return sprintf("%.{$decimals}f %s", $bytes / pow(1000, $factor), $units[$factor]);
            }
        }
    @endphp

    @forelse($external->attachments as $file)
        @php
            $type = strtolower($file->file_type);
            $iconConfig = match (true) {
                in_array($type, ['pdf']) => [
                    'icon' => 'fa-file-pdf', 
                    'color' => 'text-rose-600', 
                    'bg' => 'bg-rose-50', 
                    'border' => 'group-hover:border-rose-200'
                ],
                in_array($type, ['doc', 'docx']) => [
                    'icon' => 'fa-file-word', 
                    'color' => 'text-blue-600', 
                    'bg' => 'bg-blue-50', 
                    'border' => 'group-hover:border-blue-200'
                ],
                in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp']) => [
                    'icon' => 'fa-file-image', 
                    'color' => 'text-emerald-600', 
                    'bg' => 'bg-emerald-50', 
                    'border' => 'group-hover:border-emerald-200'
                ],
                default => [
                    'icon' => 'fa-file-lines', 
                    'color' => 'text-slate-500', 
                    'bg' => 'bg-slate-50', 
                    'border' => 'group-hover:border-slate-200'
                ],
            };
        @endphp

        <div class="group relative bg-white rounded-2xl border border-slate-200 p-4 flex items-center justify-between gap-4 transition-all duration-300 hover:shadow-xl hover:shadow-slate-200/50 {{ $iconConfig['border'] }}">
            <div class="flex items-center gap-4 flex-1 min-w-0">
                <!-- File Icon Badge -->
                <div class="w-14 h-14 rounded-xl {{ $iconConfig['bg'] }} flex flex-col items-center justify-center border border-transparent transition-all group-hover:scale-105">
                    <i class="fa-solid {{ $iconConfig['icon'] }} {{ $iconConfig['color'] }} text-2xl"></i>
                    <span class="text-[8px] font-black uppercase mt-1 {{ $iconConfig['color'] }} opacity-70">{{ $type }}</span>
                </div>

                <!-- File Details -->
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-slate-800 truncate group-hover:text-indigo-600 transition-colors" title="{{ $file->file_name }}">
                        {{ $file->file_name }}
                    </h4>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                            {{ humanFileSize($file->file_size, 2) }}
                        </span>
                        <span class="text-slate-300">•</span>
                        <span class="text-[10px] text-slate-400 font-medium italic">
                            Added {{ $file->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                @php
                    $previewable = in_array($type, ['pdf','jpg','jpeg','png','gif','webp']);
                @endphp
                
                @if($previewable)
                    <button type="button" 
                        class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 border border-transparent hover:border-indigo-100 rounded-xl transition-all"
                        onclick="openPreviewModal('{{ route('externalatt.preview', $file->id) }}', '{{ $type }}')">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                @endif

                <a href="{{ route('externalatt.download', $file->id ) }}" 
                    class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-100 rounded-xl transition-all">
                    <i class="fa-solid fa-download text-sm"></i>
                </a>
            </div>
        </div>
    @empty
        <div class="flex flex-col items-center justify-center py-12 px-6 bg-slate-50 border border-dashed border-slate-200 rounded-3xl">
            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-3">
                <i class="fa-solid fa-folder-open text-slate-200 text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No documents attached</p>
        </div>
    @endforelse
</div>


</section>