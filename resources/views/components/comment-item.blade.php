@props(['comment'])

<div id="comment-{{ $comment->id }}" class="group relative flex items-start gap-2 transition duration-300">

    {{-- Avatar --}}
    <div class="relative w-12 h-12 shrink-0 bg-[#1a2730]/40 rounded-[3px] overflow-hidden border border-white/5">
        <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/30">
            <span class="text-sm font-normal uppercase">{{ substr($comment->user->name, 0, 1) }}</span>
        </div>
    </div>

    {{-- Comment Info --}}
    <div class="flex flex-col min-w-0 flex-1">
        <span class="text-white text-[13px] font-normal uppercase tracking-wide truncate group-hover:text-[#e96c4c] transition duration-300">
            {{ $comment->user->name }}
        </span>
        <p class="text-[#53a1b3]/50 text-[10px] uppercase tracking-widest break-words mt-0.5">
            {{ $comment->body }}
        </p>
    </div>

    {{-- Right: Time + Delete --}}
    <div class="flex items-center gap-2 shrink-0 pt-0.5">
        @php
            $diff = (int) $comment->created_at->diffInSeconds(now());
            if ($diff < 60) $timeAgo = $diff . 's ago';
            elseif ($diff < 3600) $timeAgo = floor($diff / 60) . 'm ago';
            elseif ($diff < 86400) $timeAgo = floor($diff / 3600) . 'h ago';
            elseif ($diff < 604800) $timeAgo = floor($diff / 86400) . 'd ago';
            elseif ($diff < 2592000) $timeAgo = floor($diff / 604800) . 'w ago';
            else $timeAgo = floor($diff / 2592000) . 'mo ago';
        @endphp
        <span class="text-[#53a1b3]/30 text-[9px] uppercase tracking-widest font-mono whitespace-nowrap">
            {{ $timeAgo }}
        </span>
        @auth
        @if(auth()->id() === $comment->user_id)
        <button onclick="deleteComment({{ $comment->id }}, this)"
                class="w-8 h-8 flex items-center justify-center text-[#53a1b3]/20 hover:text-red-500 transition rounded-[3px] hover:bg-red-500/5">
            <ion-icon name="trash-outline" class="w-4 h-4"></ion-icon>
        </button>
        @endif
        @endauth
    </div>

</div>
