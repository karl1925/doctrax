<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
<div class="copyright text-center p-2" style="font-size: 12px; color:rgb(185, 185, 185);">
    <span>DICT R2 DocTrax: A Regional Initiative</span><br>
</div>
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
