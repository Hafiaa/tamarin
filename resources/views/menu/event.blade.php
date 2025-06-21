@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4">Event Menu</h1>
            <p class="lead">Menu khusus untuk event & gathering di Tamacafe</p>
        </div>
    </div>

    <!-- Menu Buffet -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-5">
                <h4 class="mb-3">Menu Buffet <span class="text-success">Rp103.500</span> <small>(Include Nasi Putih, Mineral, Kerupuk, Dessert)</small></h4>
                <table class="table table-bordered">
                    <tbody>
                        <tr><td><strong>Pilihan 1</strong></td><td>Mie G Jawa, Nasi G Filipina, Nasi G Hongkong</td></tr>
                        <tr><td><strong>Pilihan 2</strong></td><td>Soup Ayam Jagung, Soup Kimlo, Soup Ayam Bakso</td></tr>
                        <tr><td><strong>Pilihan 3</strong></td><td>Ayam Mentega, Ayam Rica-Rica, Ayam Teriyaki, Sapi Lada Hitam, Sapi Teriyaki</td></tr>
                        <tr><td><strong>Pilihan 4</strong></td><td>Dory Asam Manis, Dory Sambal Matah, Dory Saus Lemon, Dory Saus Padang</td></tr>
                        <tr><td><strong>Pilihan 5</strong></td><td>Capcay, Salad bangkok, Asinan Betawi</td></tr>
                        <tr><td><strong>Pilihan 6</strong></td><td>Jus Jambu, Jus Jeruk, Jus Semangka, Lemon Tea, Softdrink</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Food Stall -->
            <div class="mb-5">
                <h4 class="mb-3">Food Stall <span class="text-muted">(Minimum 100pax/Item)</span></h4>
                <table class="table table-striped">
                    <thead>
                        <tr><th>Menu</th><th>Harga</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Bakso</td><td>Rp17.250</td></tr>
                        <tr><td>Cilok</td><td>Rp14.950</td></tr>
                        <tr><td>Coffe Break + Snack</td><td>Rp26.450</td></tr>
                        <tr><td>Dimsum</td><td>Rp23.000</td></tr>
                        <tr><td>Es Cendol Dawet</td><td>Rp14.950</td></tr>
                        <tr><td>Es Krim</td><td>Rp6.900</td></tr>
                        <tr><td>Kambing Guling</td><td>Rp4.600.000</td></tr>
                        <tr><td>Kebab</td><td>Rp23.000</td></tr>
                        <tr><td>Sate + Lontong</td><td>Rp17.250</td></tr>
                        <tr><td>Siomay</td><td>Rp17.250</td></tr>
                        <tr><td>Soto Ayam + Nasi</td><td>Rp17.250</td></tr>
                        <tr><td>Soto Betawi</td><td>Rp20.700</td></tr>
                        <tr><td>Spaghetti Bolognesse</td><td>Rp17.250</td></tr>
                        <tr><td>Zuppa Soup</td><td>Rp28.750</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <h3 class="mb-2">Siap Merencanakan Acara Anda?</h3>
                <p class="mb-0">Hubungi kami sekarang untuk berdiskusi tentang kebutuhan katering dan buat menu khusus untuk acara spesial Anda.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('reservations.create') }}" class="btn btn-light btn-lg">Pesan Sekarang</a>
            </div>
        </div>
    </div>
</section>
@endsection
