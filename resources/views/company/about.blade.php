@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">About Tamacafe</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('images/about-header.jpg') }}" alt="About Tamacafe" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Our Story Section -->
    <section class="our-story-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="story-image position-relative">
                        <img src="{{ asset('images/our-story.jpg') }}" alt="Our Story" class="img-fluid rounded shadow">
                        <div class="experience-badge position-absolute bg-primary text-white p-3 rounded">
                            <span class="experience-years display-4 fw-bold">10+</span>
                            <span class="experience-text d-block">Years of Excellence</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-header mb-4">
                        <h2 class="section-title">Our Story</h2>
                        <div class="section-divider"></div>
                    </div>
                    <div class="story-content">
                        {!! $aboutContent ?? '<p class="mb-4">Tamacafe was founded in 2015 with a simple vision: to create a warm, inviting space where people could enjoy exceptional food and memorable events. What started as a small cafe has grown into a beloved venue for both casual dining and special celebrations.</p>
                        <p class="mb-4">Our journey began when our founder, inspired by travels across Asia and Europe, wanted to bring diverse culinary experiences to our local community. The name "Tamacafe" combines "Tama" (meaning "perfect" in Japanese) and "Cafe," reflecting our commitment to excellence in everything we do.</p>
                        <p>Over the years, we\'ve had the privilege of hosting countless weddings, birthdays, corporate events, and gatherings. Each event has contributed to our story, and we\'re grateful for the trust our customers place in us to make their special moments truly unforgettable.</p>' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section class="vision-mission-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="vision-card h-100 p-4 bg-white rounded shadow-sm">
                        <div class="card-icon mb-3 text-primary">
                            <i class="fas fa-eye fa-3x"></i>
                        </div>
                        <h3 class="card-title mb-3">Our Vision</h3>
                        <div class="card-content">
                            {!! $companyVision ?? '<p>To be the premier destination for exceptional dining experiences and unforgettable events, known for our culinary excellence, warm hospitality, and creating lasting memories for our guests.</p>' !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mission-card h-100 p-4 bg-white rounded shadow-sm">
                        <div class="card-icon mb-3 text-primary">
                            <i class="fas fa-bullseye fa-3x"></i>
                        </div>
                        <h3 class="card-title mb-3">Our Mission</h3>
                        <div class="card-content">
                            {!! $companyMission ?? '<p>To deliver exceptional food, service, and ambiance that exceed our customers\' expectations. We are committed to:</p>
                            <ul class="mt-3">
                                <li>Using fresh, high-quality ingredients in all our dishes</li>
                                <li>Providing attentive, personalized service</li>
                                <li>Creating a warm, welcoming atmosphere</li>
                                <li>Supporting our local community and suppliers</li>
                                <li>Continuously innovating our menu and event offerings</li>
                            </ul>' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team Section -->
    <section class="our-team-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Meet Our Team</h2>
                <p class="section-subtitle">The talented people behind Tamacafe's success</p>
            </div>
            <div class="row">
                @forelse($teamMembers as $member)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="team-member-card text-center h-100">
                            <div class="member-image mb-3">
                                <img src="{{ $member->getFirstMediaUrl('avatar') ?: asset('images/team-placeholder.jpg') }}" 
                                     alt="{{ $member->name }}" class="img-fluid rounded-circle">
                            </div>
                            <h5 class="member-name">{{ $member->name }}</h5>
                            <p class="member-position text-primary mb-2">{{ $member->position }}</p>
                            <p class="member-bio mb-3">{{ $member->bio }}</p>
                            <div class="member-social">
                                @if($member->social_links)
                                    @foreach($member->social_links as $platform => $link)
                                        <a href="{{ $link }}" class="social-link me-2" target="_blank">
                                            <i class="fab fa-{{ $platform }}"></i>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-3 mb-4">
                        <div class="team-member-card text-center h-100">
                            <div class="member-image mb-3">
                                <img src="{{ asset('images/team-1.jpg') }}" alt="John Doe" class="img-fluid rounded-circle">
                            </div>
                            <h5 class="member-name">John Doe</h5>
                            <p class="member-position text-primary mb-2">Founder & CEO</p>
                            <p class="member-bio mb-3">With over 15 years of culinary experience, John brings passion and innovation to every aspect of Tamacafe.</p>
                            <div class="member-social">
                                <a href="#" class="social-link me-2"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-link me-2"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="team-member-card text-center h-100">
                            <div class="member-image mb-3">
                                <img src="{{ asset('images/team-2.jpg') }}" alt="Jane Smith" class="img-fluid rounded-circle">
                            </div>
                            <h5 class="member-name">Jane Smith</h5>
                            <p class="member-position text-primary mb-2">Executive Chef</p>
                            <p class="member-bio mb-3">Jane's culinary expertise and creativity ensure that every dish at Tamacafe is exceptional and memorable.</p>
                            <div class="member-social">
                                <a href="#" class="social-link me-2"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-link me-2"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="team-member-card text-center h-100">
                            <div class="member-image mb-3">
                                <img src="{{ asset('images/team-3.jpg') }}" alt="David Johnson" class="img-fluid rounded-circle">
                            </div>
                            <h5 class="member-name">David Johnson</h5>
                            <p class="member-position text-primary mb-2">Event Manager</p>
                            <p class="member-bio mb-3">David's attention to detail and organizational skills ensure that every event at Tamacafe runs smoothly.</p>
                            <div class="member-social">
                                <a href="#" class="social-link me-2"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-link me-2"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="team-member-card text-center h-100">
                            <div class="member-image mb-3">
                                <img src="{{ asset('images/team-4.jpg') }}" alt="Sarah Lee" class="img-fluid rounded-circle">
                            </div>
                            <h5 class="member-name">Sarah Lee</h5>
                            <p class="member-position text-primary mb-2">Customer Relations</p>
                            <p class="member-bio mb-3">Sarah's warm personality and dedication to customer satisfaction make every guest feel welcome at Tamacafe.</p>
                            <div class="member-social">
                                <a href="#" class="social-link me-2"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-link me-2"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us-section py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Why Choose Tamacafe</h2>
                <p class="section-subtitle">What sets us apart from the rest</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="feature-icon mb-3 text-primary">
                            <i class="fas fa-utensils fa-3x"></i>
                        </div>
                        <h4 class="feature-title">Exceptional Cuisine</h4>
                        <p class="feature-text">Our talented chefs create delicious dishes using only the freshest, highest-quality ingredients.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="feature-icon mb-3 text-primary">
                            <i class="fas fa-star fa-3x"></i>
                        </div>
                        <h4 class="feature-title">Personalized Service</h4>
                        <p class="feature-text">We pride ourselves on attentive, personalized service that makes every guest feel special.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="feature-icon mb-3 text-primary">
                            <i class="fas fa-map-marker-alt fa-3x"></i>
                        </div>
                        <h4 class="feature-title">Beautiful Venue</h4>
                        <p class="feature-text">Our thoughtfully designed space provides the perfect backdrop for any occasion.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="feature-icon mb-3 text-primary">
                            <i class="fas fa-clipboard-list fa-3x"></i>
                        </div>
                        <h4 class="feature-title">Customizable Packages</h4>
                        <p class="feature-text">We offer flexible event packages that can be tailored to meet your specific needs and preferences.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="feature-icon mb-3 text-primary">
                            <i class="fas fa-heart fa-3x"></i>
                        </div>
                        <h4 class="feature-title">Passionate Team</h4>
                        <p class="feature-text">Our dedicated team is passionate about creating memorable experiences for all our guests.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="feature-icon mb-3 text-primary">
                            <i class="fas fa-thumbs-up fa-3x"></i>
                        </div>
                        <h4 class="feature-title">Proven Track Record</h4>
                        <p class="feature-text">With hundreds of successful events under our belt, you can trust us to deliver excellence.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h3 class="cta-title mb-2">Ready to Experience Tamacafe?</h3>
                    <p class="cta-text mb-0">Visit us today or book your next event with us.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('events.index') }}" class="btn btn-light btn-lg me-2 mb-2 mb-md-0">Book an Event</a>
                    <a href="{{ route('company.contact') }}" class="btn btn-outline-light btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Connect With Us Section -->
    <section class="connect-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Connect With Us</h2>
                <p class="section-subtitle">Follow us on Instagram for the latest updates and events</p>
            </div>
            <div class="row justify-content-center">
                @foreach($socialMedia['instagram'] as $account)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="instagram-card text-center p-4 bg-white rounded shadow-sm h-100">
                        <div class="instagram-icon mb-3">
                            <i class="fab fa-instagram fa-3x" style="color: #E1306C;"></i>
                        </div>
                        <h4 class="mb-3">{{ $account['name'] }}</h4>
                        <p class="text-muted mb-4">{{ $account['handle'] }}</p>
                        <a href="{{ $account['url'] }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fab fa-instagram me-2"></i> Follow Us
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .section-divider {
        width: 50px;
        height: 3px;
        background-color: #007bff;
        margin-bottom: 20px;
    }
    
    .experience-badge {
        bottom: -20px;
        right: 30px;
        min-width: 120px;
        text-align: center;
    }
    
    .team-member-card {
        transition: all 0.3s ease;
    }
    
    .team-member-card:hover {
        transform: translateY(-10px);
    }
    
    .member-image img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 5px solid #f8f9fa;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #f8f9fa;
        color: #b9c24b;
        transition: all 0.3s ease;
    }
    
    .social-link:hover {
        background-color: #b9c24b;
        color: #fff;
    }
    
    .feature-card {
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush
