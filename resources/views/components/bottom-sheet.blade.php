<div id="bottom-sheet" 
     class="fixed inset-0 z-[100] hidden lg:hidden" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop (No blur as per global preference for modals) -->
    <div id="bottom-sheet-backdrop" 
         class="absolute inset-0 bg-black/50 transition-opacity duration-300 opacity-0"></div>

    <!-- Sheet Content (Premium Glassy Look) -->
    <div id="bottom-sheet-content" 
         class="absolute bottom-0 left-0 right-0 bg-[#1a2730]/95 backdrop-blur-xl border-t border-[#53a1b3]/10 transform translate-y-full transition-transform duration-300 ease-out flex flex-col max-h-[90vh] rounded-t-[3px]">
        
        <!-- Scrollable Content Area -->
        <div id="bottom-sheet-body" class="overflow-y-auto p-2 scrollbar-hide">
            <!-- Content will be injected here via JS -->
        </div>
    </div>
</div>

<script>
    function openBottomSheet(contentHtml) {
        const sheet = document.getElementById('bottom-sheet');
        const backdrop = document.getElementById('bottom-sheet-backdrop');
        const content = document.getElementById('bottom-sheet-content');
        const body = document.getElementById('bottom-sheet-body');

        if (!sheet || !content || !body) return;

        // Set content
        body.innerHTML = contentHtml;

        // Show sheet
        sheet.classList.remove('hidden');
        
        // Trigger animations
        requestAnimationFrame(() => {
            backdrop.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('translate-y-full', 'translate-y-0');
        });

        // Close on backdrop click
        backdrop.onclick = closeBottomSheet;
    }

    function closeBottomSheet() {
        const sheet = document.getElementById('bottom-sheet');
        const backdrop = document.getElementById('bottom-sheet-backdrop');
        const content = document.getElementById('bottom-sheet-content');

        if (!sheet || !content) return;

        backdrop.classList.replace('opacity-100', 'opacity-0');
        content.classList.replace('translate-y-0', 'translate-y-full');

        setTimeout(() => {
            sheet.classList.add('hidden');
        }, 300);
    }
</script>
