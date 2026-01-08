<div class="modal fade" id="userShowModal" aria-hidden="true" aria-labelledby="galeriModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="galeriModalLabel">Detail Galeri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="gallery-modal-content">
                    <!-- Title -->
                    <h3 class="gallery-modal-title" id="title"></h3>

                    <!-- Date -->
                    <div class="gallery-modal-meta">
                        <i class="bi bi-calendar-event"></i>
                        <span id="date"></span>
                    </div>

                    <!-- Media Content -->
                    <div class="gallery-modal-media" id="modal-content">
                        <!-- Content will be dynamically inserted here -->
                    </div>

                    <!-- Description -->
                    <div class="gallery-modal-description">
                        <h6 class="description-label">Deskripsi:</h6>
                        <p id="description"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Styling */
    .modal-content {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 30px;
    }

    .modal-title {
        color: #ffffff;
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
    }

    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 30px;
    }

    .gallery-modal-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .gallery-modal-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
        line-height: 1.3;
    }

    .gallery-modal-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #718096;
        font-size: 0.95rem;
        padding: 10px 0;
        border-bottom: 2px solid #e2e8f0;
    }

    .gallery-modal-meta i {
        font-size: 1.1rem;
        color: #667eea;
    }

    .gallery-modal-media {
        width: 100%;
        margin: 10px 0;
        border-radius: 10px;
        overflow: hidden;
        background: #f7fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 300px;
    }

    .gallery-modal-media img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: contain;
        display: block;
    }

    .gallery-modal-media video {
        width: 100%;
        height: auto;
        max-height: 500px;
        border-radius: 10px;
    }

    .gallery-modal-description {
        background: #f7fafc;
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }

    .description-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .gallery-modal-description p {
        color: #4a5568;
        line-height: 1.6;
        margin: 0;
        font-size: 0.95rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 10px;
        }

        .modal-body {
            padding: 20px;
        }

        .gallery-modal-title {
            font-size: 1.5rem;
        }

        .gallery-modal-media {
            min-height: 200px;
        }

        .gallery-modal-media img,
        .gallery-modal-media video {
            max-height: 350px;
        }
    }

    /* Animation */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: scale(0.9);
    }

    .modal.show .modal-dialog {
        transform: scale(1);
    }
</style>
