<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="mb-3">Tamacafe</h5>
                <p>Your perfect venue for events and gatherings. Enjoy our delicious food and warm atmosphere for any occasion.</p>
                <div class="social-icons mt-3">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="{{ route('events.index') }}" class="text-white text-decoration-none">Event Packages</a></li>
                    <li class="mb-2"><a href="{{ route('menu.cafe') }}" class="text-white text-decoration-none">Cafe Menu</a></li>
                    <li class="mb-2"><a href="{{ route('company.about') }}" class="text-white text-decoration-none">About Us</a></li>
                    <li><a href="{{ route('company.contact') }}" class="text-white text-decoration-none">Contact Us</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="mb-3">Contact Info</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> {{ \App\Models\Setting::get('company_address', 'Jl. Manggala No.161, RT.007/RW.007, Deplu, Cipadu Jaya, Kec. Larangan, Kota Tangerang, Banten 15155') }}</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> {{ \App\Models\Setting::get('company_phone', '0813-1828-3874') }}</li>
                    <!-- <li class="mb-2"><i class="fas fa-envelope me-2"></i> {{ \App\Models\Setting::get('company_email', 'info@tamacafe.com') }}</li> -->
                    <li><i class="fas fa-clock me-2"></i> {{ \App\Models\Setting::get('business_hours', 'Mon-Sun: 11:00 AM - 10:00 PM') }}</li>
                </ul>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <p class="mb-0">&copy; {{ date('Y') }} Tamacafe. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-white text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="text-white text-decoration-none">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
