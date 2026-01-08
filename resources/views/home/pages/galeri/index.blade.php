@extends('home.layouts.master')
@section("title","Galeri | EMPATRA DIGITECH")
@section('css')
    <link href="{{ URL::to('/') }}/assets/css/galery/style.css" rel="stylesheet">
    <style>
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0 60px;
            margin-top: 70px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="%23ffffff" fill-opacity="0.1"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        .page-header-content {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
        }

        /* Gallery Grid */
        .gallery-section {
            padding: 50px 0;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .gallery-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .gallery-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .gallery-card-image {
            width: 100%;
            height: 250px;
            overflow: hidden;
            position: relative;
            background: #f8f9fa;
        }

        .gallery-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-card-image video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-card:hover .gallery-card-image img {
            transform: scale(1.1);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.7));
            display: flex;
            align-items: flex-end;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            padding: 20px;
        }

        .gallery-card:hover .image-overlay {
            opacity: 1;
        }

        .view-badge {
            background: #667eea;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .gallery-card-body {
            padding: 20px;
        }

        .gallery-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
            line-height: 1.4;
        }

        .media-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.95);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #667eea;
            z-index: 2;
        }

        /* No Data Message */
        .no-data-message {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }

        .no-data-message i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-data-message h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .page-subtitle {
                font-size: 1rem;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }

            .gallery-card-image {
                height: 200px;
            }
        }
    </style>
@endsection

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header-content">
                    <h1 class="page-title">Galeri</h1>
                    <p class="page-subtitle">Dokumentasi kegiatan dan karya kami</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Grid Section -->
<section class="gallery-section">
    <div class="container">
        <div class="gallery-grid">
            @forelse($table as $index => $row)
                <div class="gallery-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <a class="text-decoration-none" data-bs-toggle="modal" href="#userShowModal" id="show-user"
                       data-url="{{ route('home.galeri.show', $row->id) }}">

                        @php
                            $extension = pathinfo($row->image, PATHINFO_EXTENSION);
                            $isVideo = in_array($extension, ['wmv', 'mkv', 'mp4', 'avi']);
                        @endphp

                        <span class="media-type-badge">
                            @if($isVideo)
                                <i class="bi bi-play-circle-fill"></i> Video
                            @else
                                <i class="bi bi-image-fill"></i> Gambar
                            @endif
                        </span>

                        <div class="gallery-card-image">
                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}">
                            @elseif ($isVideo)
                                <video>
                                    <source src="{{ asset('storage/' . $row->image) }}" type="video/{{ $extension }}">
                                </video>
                            @endif

                            <div class="image-overlay">
                                <span class="view-badge">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </span>
                            </div>
                        </div>

                        <div class="gallery-card-body">
                            <h3 class="gallery-title">{{ $row->title }}</h3>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-data-message">
                        <i class="bi bi-images"></i>
                        <h3>Belum Ada Galeri</h3>
                        <p>Saat ini belum ada item galeri yang tersedia.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($table->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="pagination-wrapper">
                        {!! $table->links() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@include('home.pages.galeri.modal.index')

@endsection

@section('script')
<script>
    $(document).ready(function() {

        // When click show user
        $('body').on('click', '#show-user', function() {
            var userURL = $(this).data('url');

            $.get(userURL, function(data) {
                var data_image = data.image;
                var data_date = new Date(data.date);

                $('#userShowModal').modal('show');
                $('#title').text(data.title);

                var options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                var formattedDate = data_date.toLocaleDateString('id-ID', options);
                $('#date').text(formattedDate);

                // Display image or video in modal
                var fileExtension = data.image.split('.').pop().toLowerCase();
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    $('#modal-content').html('<img src="{{ asset('storage/') }}/' + data_image + '" alt="' + data.title + '" class="img-fluid rounded">');
                } else if (['wmv', 'mkv', 'mp4', 'avi'].includes(fileExtension)) {
                    $('#modal-content').html('<video width="100%" height="100%" controls class="rounded"><source src="{{ asset('storage/') }}/' + data_image + '" type="video/' + fileExtension + '">Your browser does not support the video tag.</video>');
                }

                $('#description').text(data.description);
            });
        });

        // Scroll Reveal Animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        const galleryCards = document.querySelectorAll('.gallery-card');
        galleryCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `all 0.6s ease-out ${index * 0.1}s`;
            observer.observe(card);
        });
    });
</script>
@endsection
