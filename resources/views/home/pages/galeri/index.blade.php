@extends('home.layouts.master')
@section("title","Galeri | EMPATRA DIGITECH")
@section('css')
    <link href="{{ URL::to('/') }}/assets/css/galery/style.css" rel="stylesheet">
@endsection

@section('content')

<!-- Page Header -->
<section class="galeri-page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="galeri-page-header-content">
                    <h1 class="galeri-page-title">Galeri</h1>
                    <p class="galeri-page-subtitle">Dokumentasi kegiatan, pengumuman, dan promosi kami</p>
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
                @php
                    $extension = pathinfo($row->image, PATHINFO_EXTENSION);
                    $isVideo = in_array($extension, ['wmv', 'mkv', 'mp4', 'avi']);
                @endphp
                <a class="gallery-card" data-aos="fade-up" data-aos-delay="{{ ($index % 8) * 60 }}"
                   href="#galeriShowModal" data-bs-toggle="modal" id="show-galeri"
                   data-url="{{ route('home.galeri.show', $row->id) }}">

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
                            <video muted>
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
            @empty
                <div class="no-data-message">
                    <i class="bi bi-images"></i>
                    <h3>Belum Ada Galeri</h3>
                    <p>Saat ini belum ada item galeri yang tersedia.</p>
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

        $('body').on('click', '#show-galeri', function() {
            var galeriURL = $(this).data('url');

            $.get(galeriURL, function(data) {
                var data_image = data.image;
                var data_date = new Date(data.date);

                $('#galeriTitleHeader').text(data.title);
                $('#title').text(data.title);

                var options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                var formattedDate = data_date.toLocaleDateString('id-ID', options);
                $('#date').text(formattedDate);

                var fileExtension = data.image.split('.').pop().toLowerCase();
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    $('#modal-content').html('<img src="{{ asset('storage/') }}/' + data_image + '" alt="' + data.title + '">');
                } else if (['wmv', 'mkv', 'mp4', 'avi'].includes(fileExtension)) {
                    $('#modal-content').html('<video controls><source src="{{ asset('storage/') }}/' + data_image + '" type="video/' + fileExtension + '">Your browser does not support the video tag.</video>');
                }

                $('#description').text(data.description);
            });
        });

        // Clear modal media when closed so a paused video doesn't keep playing in the background
        $('#galeriShowModal').on('hidden.bs.modal', function() {
            $('#modal-content').empty();
        });
    });
</script>
@endsection
