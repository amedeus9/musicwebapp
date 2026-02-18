{{--
    Global Confirmation Modal — matches auth modal structure
    JS API: confirmAction({ message, onConfirm, title?, confirmLabel?, danger? })
    HTML:   data-confirm="..." data-confirm-title="..." data-confirm-form="form-id"
--}}

<div id="confirm-modal"
    onclick="closeConfirmModal(event)"
    class="hidden fixed inset-0 bg-black/50 z-[200] flex items-start justify-center pt-64 p-4 transition-opacity">

    <div onclick="event.stopPropagation()"
        class="w-[350px] bg-[#1a2730] shadow-2xl rounded-[3px] overflow-hidden border border-[#53a1b3]/10 flex flex-col">

        {{-- Header --}}
        <div class="px-2 pt-2 border-b border-[#53a1b3]/10">
            <div class="flex items-center gap-2 pb-2">
                <ion-icon id="confirm-icon" name="trash-outline" class="w-4 h-4 text-red-500"></ion-icon>
                <span id="confirm-title" class="text-sm font-medium text-white uppercase tracking-wider">Are you sure?</span>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-2">
            <p id="confirm-message" class="text-[#53a1b3] text-xs leading-relaxed">
                This action cannot be undone.
            </p>
        </div>

        {{-- Footer --}}
        <div class="p-2 border-t border-[#53a1b3]/10 flex items-center justify-end gap-2">
            <button onclick="closeConfirmModal()"
                class="px-4 py-2 text-xs font-medium uppercase tracking-wider text-[#53a1b3] border border-[#53a1b3]/20 hover:border-[#53a1b3]/50 hover:text-white transition rounded-[3px]">
                Cancel
            </button>
            <button id="confirm-btn"
                class="px-4 py-2 text-xs font-medium uppercase tracking-wider text-red-500 border border-red-500/40 hover:bg-red-500 hover:text-white transition rounded-[3px]">
                <span id="confirm-btn-label">Delete</span>
            </button>
        </div>

    </div>
</div>

<script>
    (function () {
        var _confirmCallback = null;

        window.confirmAction = function (opts) {
            var message      = opts.message      || 'This action cannot be undone.';
            var onConfirm    = opts.onConfirm    || null;
            var title        = opts.title        || 'Are you sure?';
            var confirmLabel = opts.confirmLabel || 'Delete';
            var icon         = opts.icon         || 'trash-outline';
            var danger       = opts.danger !== false;

            _confirmCallback = onConfirm;

            document.getElementById('confirm-title').textContent     = title;
            document.getElementById('confirm-message').textContent   = message;
            document.getElementById('confirm-btn-label').textContent = confirmLabel;
            document.getElementById('confirm-icon').setAttribute('name', icon);

            var btn = document.getElementById('confirm-btn');
            if (danger) {
                btn.className = 'px-4 py-2 text-xs font-medium uppercase tracking-wider text-red-500 border border-red-500/40 hover:bg-red-500 hover:text-white transition rounded-[3px]';
            } else {
                btn.className = 'px-4 py-2 text-xs font-medium uppercase tracking-wider text-[#e96c4c] border border-[#e96c4c]/40 hover:bg-[#e96c4c] hover:text-white transition rounded-[3px]';
            }

            document.getElementById('confirm-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeConfirmModal = function (event) {
            if (event && event.target.id !== 'confirm-modal' && !event.target.closest('button')) return;
            document.getElementById('confirm-modal').classList.add('hidden');
            document.body.style.overflow = '';
            _confirmCallback = null;
        };

        // Wire confirm button
        document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('confirm-btn');
            if (btn) {
                btn.addEventListener('click', function () {
                    if (typeof _confirmCallback === 'function') _confirmCallback();
                    document.getElementById('confirm-modal').classList.add('hidden');
                    document.body.style.overflow = '';
                    _confirmCallback = null;
                });
            }
        });

        // Escape key closes modal
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.getElementById('confirm-modal').classList.add('hidden');
                document.body.style.overflow = '';
                _confirmCallback = null;
            }
        });

        // data-confirm attribute pattern
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-confirm]');
            if (!btn) return;
            e.preventDefault();
            var message = btn.getAttribute('data-confirm');
            var title   = btn.getAttribute('data-confirm-title') || 'Are you sure?';
            var formId  = btn.getAttribute('data-confirm-form');
            window.confirmAction({
                title: title,
                message: message,
                onConfirm: function () {
                    if (formId) {
                        var form = document.getElementById(formId);
                        if (form) form.submit();
                    }
                }
            });
        });
    })();
</script>
