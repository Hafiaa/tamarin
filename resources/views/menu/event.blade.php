@extends('layouts.app')

@push('styles')
<style>
    .menu-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        background: white;
        margin-bottom: 2rem;
    }
    
    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .menu-header {
        background: linear-gradient(135deg, #b9c24b 0%, #8a9e2b 100%);
        color: white;
        padding: 1.5rem;
        margin: 0;
    }
    
    .menu-body {
        padding: 2rem;
    }
    
    .price-tag {
        background: #f8f9fa;
        border-left: 4px solid #b9c24b;
        padding: 0.5rem 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0 5px 5px 0;
    }
    
    .menu-item {
        border-left: 3px solid #b9c24b;
        padding-left: 15px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .menu-item:hover {
        background-color: #f8f9fa;
        padding-left: 20px;
    }
    
    .food-stall-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #eee;
        transition: all 0.2s ease;
    }
    
    .food-stall-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .food-stall-price {
        font-weight: 600;
        color: #b9c24b;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold mb-3">Menu Acara</h1>
            <div class="divider mx-auto" style="width: 100px; height: 4px; background: #b9c24b; margin: 0 auto 1.5rem;"></div>
            <p class="lead text-muted">Menu spesial untuk acara & gathering di Tamacafe</p>
        </div>
    </div>

    <!-- Menu Buffet -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="menu-card">
                <div class="menu-header">
                    <h3 class="h4 mb-0">Paket Buffet Lengkap</h3>
                </div>
                <div class="menu-body">
                    <div class="price-tag">
                        <span class="h5 mb-0">Hanya Rp103.500/orang</span>
                        <small class="text-muted ms-2">*Termasuk Nasi Putih, Air Mineral, Kerupuk, & Dessert</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="menu-item">
                                <h6 class="fw-bold mb-1">Pilihan 1</h6>
                                <p class="mb-0 text-muted">Mie G Jawa, Nasi G Filipina, Nasi G Hongkong</p>
                            </div>
                            
                            <div class="menu-item">
                                <h6 class="fw-bold mb-1">Pilihan 2</h6>
                                <p class="mb-0 text-muted">Soup Ayam Jagung, Soup Kimlo, Soup Ayam Bakso</p>
                            </div>
                            
                            <div class="menu-item">
                                <h6 class="fw-bold mb-1">Pilihan 3</h6>
                                <p class="mb-0 text-muted">Ayam Mentega, Ayam Rica-Rica, Ayam Teriyaki, Sapi Lada Hitam, Sapi Teriyaki</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="menu-item">
                                <h6 class="fw-bold mb-1">Pilihan 4</h6>
                                <p class="mb-0 text-muted">Dory Asam Manis, Dory Sambal Matah, Dory Saus Lemon, Dory Saus Padang</p>
                            </div>
                            
                            <div class="menu-item">
                                <h6 class="fw-bold mb-1">Pilihan 5</h6>
                                <p class="mb-0 text-muted">Capcay, Salad Bangkok, Asinan Betawi</p>
                            </div>
                            
                            <div class="menu-item">
                                <h6 class="fw-bold mb-1">Pilihan 6</h6>
                                <p class="mb-0 text-muted">Jus Jambu, Jus Jeruk, Jus Semangka, Lemon Tea, Softdrink</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Food Stall -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="menu-card">
                <div class="menu-header">
                    <h3 class="h4 mb-0">Menu Food Stall</h3>
                    <p class="mb-0">*Minimum pemesanan 100 pax per item</p>
                </div>
                <div class="menu-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="food-stall-item">
                                <span>Bakso</span>
                                <span class="food-stall-price">Rp17.250</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Cilok</span>
                                <span class="food-stall-price">Rp14.950</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Coffe Break + Snack</span>
                                <span class="food-stall-price">Rp26.450</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Dimsum</span>
                                <span class="food-stall-price">Rp23.000</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Es Cendol Dawet</span>
                                <span class="food-stall-price">Rp14.950</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Es Krim</span>
                                <span class="food-stall-price">Rp6.900</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Kambing Guling</span>
                                <span class="food-stall-price">Rp4.600.000</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="food-stall-item">
                                <span>Kebab</span>
                                <span class="food-stall-price">Rp23.000</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Sate + Lontong</span>
                                <span class="food-stall-price">Rp17.250</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Siomay</span>
                                <span class="food-stall-price">Rp17.250</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Soto Ayam + Nasi</span>
                                <span class="food-stall-price">Rp17.250</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Soto Betawi</span>
                                <span class="food-stall-price">Rp20.700</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Spaghetti Bolognesse</span>
                                <span class="food-stall-price">Rp17.250</span>
                            </div>
                            <div class="food-stall-item">
                                <span>Zuppa Soup</span>
                                <span class="food-stall-price">Rp28.750</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<section class="py-5" style="background: linear-gradient(135deg, #b9c24b 0%, #8a9e2b 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <h3 class="mb-2 text-white">Siap Merencanakan Acara Anda?</h3>
                <p class="mb-0 text-white-50">Hubungi kami sekarang untuk berdiskusi tentang kebutuhan katering dan buat menu khusus untuk acara spesial Anda.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('reservations.create') }}" class="btn btn-light btn-lg px-4" style="border-radius: 50px; font-weight: 600; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <i class="fas fa-calendar-plus me-2"></i> Pesan Sekarang
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
