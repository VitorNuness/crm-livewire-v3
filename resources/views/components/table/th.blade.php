@props([
    'header',
    'name'
])
@php
dd($header);
@endphp
<div wire:click="sortBy('{{ $name }}', '{{ $header['sortDirection'] == 'asc' ? 'desc' : 'asc' }}')" class="cursor-pointer">
    {{ $header['label'] }}
    @if ($header['sortByColumn'] == '{{ $name }}')
        <x-icon :name="$header['sortDirection'] == 'asc' ? 'o-chevron-down': 'o-chevron-up'" />
    @endif
</div>
