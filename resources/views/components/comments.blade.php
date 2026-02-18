@props([
    'type',        // 'song', 'album', 'playlist'
    'modelId',     // model ID (int)
    'comments',    // collection of comments
    'listId',      // unique DOM id for the comments list e.g. 'comments-list-song'
    'placeholder' => 'Write a comment...',
])

<div class="space-y-3">

    {{-- Comment Form --}}
    @auth
    <div class="mt-2">
        <div class="flex gap-2">
            <textarea id="comment-body-{{ $listId }}" rows="1" maxlength="250"
                class="flex-1 bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white/80 text-[12px] p-2 focus:outline-none focus:border-[#e96c4c]/30 transition-all placeholder-[#53a1b3]/20 resize-none h-[35px] font-light leading-tight rounded-[3px]"
                placeholder="{{ $placeholder }}"></textarea>

            <button onclick="submitComment('{{ $type }}', {{ $modelId }}, '{{ $listId }}')"
                class="px-4 py-2 flex items-center justify-center bg-[#e96c4c] rounded-[3px] text-white hover:bg-[#e96c4c]/90 transition shrink-0">
                <ion-icon name="arrow-up" class="w-4 h-4"></ion-icon>
            </button>
        </div>

    </div>
    @else
    <p class="text-[#53a1b3]/30 text-[10px] uppercase tracking-[0.2em]">
        Please <a href="{{ route('login') }}" class="text-[#e96c4c] hover:underline">login</a> to leave a comment
    </p>
    @endauth

    {{-- Comments List --}}
    <div id="{{ $listId }}" class="flex flex-col gap-2 mt-2">
        @forelse($comments as $comment)
            <x-comment-item :comment="$comment" />
        @empty
        <div id="no-comments-{{ $listId }}" class="py-10 text-center">
            <p class="text-[#53a1b3]/20 text-[10px] uppercase">No comments yet</p>
        </div>
        @endforelse
    </div>

</div>

