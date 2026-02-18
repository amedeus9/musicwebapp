@props([
    'icon',            // ionicon name e.g. 'download-outline'
    'value',           // the count/number to display
    'iconColor' => 'text-[#53a1b3]',
])

<div class="flex items-center h-4 border border-[#53a1b3]/20 overflow-hidden">
    <div class="bg-[#141e24] px-1.5 h-full flex items-center justify-center border-r border-[#53a1b3]/10">
        <ion-icon name="{{ $icon }}" class="w-2 h-2 {{ $iconColor }}"></ion-icon>
    </div>
    <div class="bg-[#53a1b3]/10 px-1.5 h-full flex items-center justify-center text-[9px] font-normal text-[#53a1b3] font-mono">
        {{ is_numeric($value) ? number_format($value) : $value }}
    </div>
</div>
