@push('style')
<style>
    .empty-state {
        padding: 2rem 1rem;
        text-align: center;
        background-color: #f9f9f9;
        border: 2px dashed #ED7423;
        border-radius: 12px;
        transition: background-color 0.3s ease;
    }

    .empty-state:hover {
        background-color: #fff8f3;
    }

    .empty-state i {
        font-size: 3rem;
        color: #ED7423;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #444;
    }

    .empty-state p {
        font-size: 0.95rem;
        color: #777;
        margin-bottom: 0;
    }

    @media (max-width: 767.98px) {
        .empty-state {
            padding: 1.5rem 1rem;
        }

        .empty-state i {
            font-size: 2.5rem;
        }

        .empty-state h5 {
            font-size: 1.125rem;
        }
    }
</style>
@endpush

<div class="empty-state mb-4">
    <i class="{{ $icon ?? 'fa-solid fa-warning' }}"></i>
    <h5>{{ $dataTitle ?? 'Tidak Ada Data' }}</h5>
    <p>{{ $message ?? 'Belum ada data yang tersedia.' }}</p>
</div>