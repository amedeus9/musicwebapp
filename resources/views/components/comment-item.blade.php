@props(['comment'])

<div id="comment-{{ $comment->id }}" class="overflow-hidden">
    <div class="flex gap-2">
        {{-- Avatar --}}
        <div class="w-10 h-10 bg-[#53a1b3]/10 flex items-center justify-center shrink-0 rounded-[3px]">
            <span class="text-[#53a1b3]/40 text-sm font-normal uppercase">{{ substr($comment->user->name, 0, 1) }}</span>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0 overflow-hidden">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[#e96c4c] text-[11px] font-normal uppercase truncate">{{ $comment->user->name }}</span>
                <div class="flex items-center gap-2 shrink-0 ml-2">
                    <span class="text-[#53a1b3]/30 text-[9px] uppercase">{{ $comment->created_at->diffForHumans(['short' => true]) }}</span>
                    @if(auth()->check() && auth()->id() === $comment->user_id)
                        <button onclick="deleteComment({{ $comment->id }}, this)" class="text-[#53a1b3]/30 hover:text-red-500 transition">
                            <ion-icon name="trash-outline" class="w-3.5 h-3.5"></ion-icon>
                        </button>
                    @endif
                </div>
            </div>
            <p class="text-white/90 text-[13px] leading-relaxed font-light break-all overflow-hidden">{{ $comment->body }}</p>
        </div>
    </div>
</div>
