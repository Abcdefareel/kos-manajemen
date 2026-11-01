import './bootstrap';

document.addEventListener('submit', (event) => {
    const form = event.target.closest('[data-confirm-delete]');

    if (!form) {
        return;
    }

    event.preventDefault();
    window.dispatchEvent(new CustomEvent('confirm-delete:open', {
        detail: { form, description: form.dataset.confirmDelete },
    }));
});

document.addEventListener('click', (event) => {
    const closeButton = event.target.closest('[data-modal-close]');
    if (closeButton) {
        closeButton.closest('[data-modal]')?.classList.add('hidden');
    }

    const toastClose = event.target.closest('[data-toast-close]');
    if (toastClose) {
        toastClose.closest('[data-toast]')?.remove();
    }
});

window.addEventListener('confirm-delete:open', (event) => {
    const modal = document.querySelector('[data-confirm-delete-modal]');
    if (!modal) {
        return;
    }

    const action = event.detail.form.dataset.confirmAction || 'Hapus';
    const verb = event.detail.form.dataset.confirmVerb || 'menghapus';
    modal.querySelector('[data-confirm-delete-title]').textContent = `${action} ${event.detail.description}?`;
    modal.querySelector('[data-confirm-delete-description]').textContent = `Apakah Anda yakin ingin ${verb} ${event.detail.description} ini?`;
    modal.querySelector('[data-confirm-delete-submit]').textContent = action;
    modal.querySelector('[data-confirm-delete-submit]').onclick = () => event.detail.form.submit();
    modal.classList.remove('hidden');
    modal.querySelector('[data-modal-close]').focus();
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        document.querySelectorAll('[data-modal]:not(.hidden)').forEach((modal) => modal.classList.add('hidden'));
    }
});

window.setTimeout(() => {
    document.querySelectorAll('[data-toast]').forEach((toast) => {
        toast.classList.add('opacity-0', 'translate-y-2');
        window.setTimeout(() => toast.remove(), 300);
    });
}, 5000);
