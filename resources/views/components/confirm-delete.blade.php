<div data-confirm-delete-modal data-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-ink/30 p-4 backdrop-blur-sm">
    <section role="alertdialog" aria-modal="true" aria-labelledby="delete-title" class="w-full max-w-md rounded-modal bg-surface p-7 text-center shadow-soft">
        <span class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-pink/30 text-primary-dark">
            <svg viewBox="0 0 24 24" class="size-7" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3"/></svg>
        </span>
        <h2 id="delete-title" data-confirm-delete-title class="mt-5 text-xl font-extrabold text-primary-dark">Hapus data ini?</h2>
        <p data-confirm-delete-description class="mt-2 text-sm leading-6 text-muted">Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="mt-6 flex items-center justify-center gap-3">
            <button type="button" data-modal-close class="rounded-full px-5 py-3 text-sm font-bold text-muted hover:bg-surface-alt">Batal</button>
            <button type="button" data-confirm-delete-submit class="rounded-full bg-primary px-6 py-3 text-sm font-bold text-white shadow-primary hover:bg-primary-dark">Hapus</button>
        </div>
    </section>
</div>
