@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">Tentang Kami</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tentang Kami</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('images/about-header.jpg') }}" alt="Tentang Tamacafe" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Tentang Kami Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title">Selamat Datang di Tamacafe</h2>
                    <p>Tamacafe adalah destinasi utama untuk acara dan pertemuan istimewa di kota. Sejak didirikan pada tahun 2010, kami telah berkomitmen untuk memberikan pengalaman kuliner yang tak terlupakan dan layanan yang luar biasa bagi setiap tamu kami.</p>
                    <p>Dengan suasana yang nyaman dan menu yang lezat, Tamacafe menjadi pilihan utama untuk berbagai acara mulai dari pertemuan bisnis, ulang tahun, pernikahan, hingga acara keluarga.</p>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/about-1.jpg') }}" alt="Tentang Kami" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="h-100 p-4 bg-white rounded shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-primary text-white rounded-circle me-3">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="h4 mb-0">Visi</h3>
                        </div>
                        <p class="mb-0">Menjadi kafe dan tempat acara terdepan yang memberikan pengalaman tak terlupakan melalui layanan terbaik, hidangan berkualitas, dan suasana yang hangat.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h-100 p-4 bg-white rounded shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-primary text-white rounded-circle me-3">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h3 class="h4 mb-0">Misi</h3>
                        </div>
                        <ul class="mb-0 ps-4">
                            <li>Menyajikan hidangan berkualitas tinggi dengan bahan-bahan terbaik</li>
                            <li>Memberikan pelayanan yang ramah dan profesional</li>
                            <li>Menciptakan suasana yang nyaman dan berkesan</li>
                            <li>Mengutamakan kepuasan pelanggan dalam setiap aspek layanan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galeri Section -->
    <section class="py-5" id="galeri">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Galeri Kami</h2>
                <p class="section-subtitle">Lihat momen-momen istimewa di Tamacafe</p>
            </div>
            
            <div class="row g-4">
                <!-- Gambar 1 -->
                <div class="col-md-4 col-lg-3">
                    <a href="{{ asset('images/wedding3.jpg') }}" class="gallery-item" data-lightbox="gallery" data-title="Galeri Tamacafe">
                        <img src="{{ asset('images/wedding3.jpg') }}" alt="Galeri 1" class="img-fluid rounded shadow-sm" loading="lazy">
                    </a>
                </div>
                
                <!-- Gambar 2 -->
                <div class="col-md-4 col-lg-3">
                    <a href="{{ asset('images/wedding5.jpg') }}" class="gallery-item" data-lightbox="gallery" data-title="Galeri Tamacafe">
                        <img src="{{ asset('images/wedding5.jpg') }}" alt="Galeri 2" class="img-fluid rounded shadow-sm" loading="lazy">
                    </a>
                </div>
                
                <!-- Gambar 3 -->
                <div class="col-md-4 col-lg-3">
                    <a href="{{ asset('images/wedding7.jpg') }}" class="gallery-item" data-lightbox="gallery" data-title="Galeri Tamacafe">
                        <img src="{{ asset('images/wedding7.jpg') }}" alt="Galeri 3" class="img-fluid rounded shadow-sm" loading="lazy">
                    </a>
                </div>
                
                <!-- Gambar 4 -->
                <div class="col-md-4 col-lg-3">
                    <a href="{{ asset('images/wedding9.jpg') }}" class="gallery-item" data-lightbox="gallery" data-title="Galeri Tamacafe">
                        <img src="{{ asset('images/wedding9.jpg') }}" alt="Galeri 4" class="img-fluid rounded shadow-sm" loading="lazy">
                    </a>
                </div>
                
                <!-- Gambar 5 -->
                <div class="col-md-4 col-lg-3">
                    <a href="{{ asset('images/wedding13.jpg') }}" class="gallery-item" data-lightbox="gallery" data-title="Galeri Tamacafe">
                        <img src="{{ asset('images/wedding13.jpg') }}" alt="Galeri 5" class="img-fluid rounded shadow-sm" loading="lazy">
                    </a>
                </div>
                
                <!-- Gambar 6 -->
                <div class="col-md-4 col-lg-3">
                    <a href="{{ asset('images/wedding14.jpg') }}" class="gallery-item" data-lightbox="gallery" data-title="Galeri Tamacafe">
                        <img src="{{ asset('images/wedding14.jpg') }}" alt="Galeri 6" class="img-fluid rounded shadow-sm" loading="lazy">
                    </a>
                </div>
                
                <!-- Gambar 7 -->
                <div class="col-md-4 col-lg-3">
                    <a href="{{ asset('images/wedding12.jpg') }}" class="gallery-item" data-lightbox="gallery" data-title="Galeri Tamacafe">
                        <img src="{{ asset('images/wedding12.jpg') }}" alt="Galeri 7" class="img-fluid rounded shadow-sm" loading="lazy">
                    </a>
                </div>
                
                <!-- Gambar 8 -->
                <div class="col-md-4 col-lg-3">
                    <a href="{{ asset('images/wedding16.jpg') }}" class="gallery-item" data-lightbox="gallery" data-title="Galeri Tamacafe">
                        <img src="{{ asset('images/wedding16.jpg') }}" alt="Galeri 8" class="img-fluid rounded shadow-sm" loading="lazy">
                    </a>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="https://www.instagram.com/tamarincafe" target="_blank" class="btn btn-outline-primary">
                    <i class="fab fa-instagram me-2"></i> Lihat Lebih Banyak di Instagram
                </a>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section class="py-5 bg-light" id="kontak">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title mb-4">Hubungi Kami</h2>
                    <p class="mb-4">Kami siap membantu Anda. Jangan ragu untuk menghubungi kami untuk informasi lebih lanjut atau untuk membuat reservasi.</p>
                    
                    <div class="contact-info">
                        <div class="d-flex mb-3">
                            <div class="icon-box bg-primary text-white rounded-circle me-3 flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h5 class="h6 mb-1">Alamat</h5>
                                <p class="mb-0">Jl. Manggala No.161, RT.007/RW.007, Deplu, Cipadu Jaya, Kec. Larangan, Kota Tangerang, Banten 15155</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="icon-box bg-primary text-white rounded-circle me-3 flex-shrink-0">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h5 class="h6 mb-1">Telepon</h5>
                                <p class="mb-0">0813-1828-3874</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="icon-box bg-primary text-white rounded-circle me-3 flex-shrink-0">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h5 class="h6 mb-1">Email</h5>
                                <p class="mb-0">info@tamacafe.com</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="icon-box bg-primary text-white rounded-circle me-3 flex-shrink-0">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h5 class="h6 mb-1">Jam Operasional</h5>
                                <p class="mb-0">Senin - Minggu: 11:00 - 22:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <h3 class="h4 mb-4">Kirim Pesan</h3>
                            <form action="{{ route('company.contact.send') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subjek</label>
                                    <input type="text" class="form-control" id="subject" name="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tim Kami Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Tim Kami</h2>
                <p class="section-subtitle">Orang-orang berbakat di balik kesuksesan Tamacafe</p>
            </div>
            
            <div class="row">
                @for ($i = 1; $i <= 4; $i++)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="team-member text-center">
                            <div class="member-image mb-3">
                                <img src="{{ asset('images/team-' . $i . '.jpg') }}" alt="Team Member {{ $i }}" class="img-fluid rounded-circle">
                            </div>
                            <h4 class="h5 mb-1">Nama Anggota {{ $i }}</h4>
                            <p class="text-muted mb-2">Jabatan</p>
                            <div class="social-links">
                                <a href="#" class="text-primary me-2"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-primary me-2"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-primary me-2"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Apa Kata Mereka</h2>
                <p class="section-subtitle">Testimoni dari pelanggan kami</p>
            </div>
            
            <div class="row">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('images/user-' . $i . '.jpg') }}" alt="User" class="rounded-circle me-3" width="60">
                                    <div>
                                        <h5 class="mb-0">Nama Pelanggan {{ $i }}</h5>
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0">"Pelayanan di Tamacafe sangat memuaskan. Makanannya enak dan tempatnya nyaman. Sangat direkomendasikan untuk acara keluarga atau pertemuan bisnis."</p>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .section-title {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background-color: #b9c24b;
    }
    
    .section-title.text-center:after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .section-subtitle {
        color: #6c757d;
        margin-bottom: 0;
    }
    
    .icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
    }
    
    .gallery-item {
        display: block;
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover {
        transform: translateY(-5px);
    }
    
    .gallery-item img {
        transition: transform 0.5s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.05);
    }
    
    .team-member .member-image {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid #f8f9fa;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    .social-links a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .social-links a:hover {
        background-color: #b9c24b;
        color: #fff !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush
