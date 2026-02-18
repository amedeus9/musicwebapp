@props([
    'icon',            
    'value',           
    'color' => '#53a1b3', // Default color
])

@php
    // We can use the hex color for border and background opacity
    // If the user passes a Tailwind class name instead of hex, it might break inline styles,
    // so we assume hex or standard color names for this simplified premium component.
    $borderColor = str_contains($color, '#') ? $color . '33' : $color; // Adding 33 for ~20% opacity
    $bgColor = str_contains($color, '#') ? $color . '1a' : $color;    // Adding 1a for ~10% opacity
@endphp

<div class="flex items-center h-4 border overflow-hidden" style="border-color: {{ $borderColor }}">
    <div class="bg-[#141e24] px-1.5 h-full flex items-center justify-center border-r" style="border-color: {{ $bgColor }}">
        <ion-icon name="{{ $icon }}" class="w-2 h-2" style="color: {{ $color }}"></ion-icon>
    </div>
    <div class="px-1.5 h-full flex items-center justify-center text-[9px] font-normal font-mono" style="background-color: {{ $bgColor }}; color: {{ $color }}">
        {{ is_numeric($value) ? number_format($value) : $value }}
    </div>
</div>
