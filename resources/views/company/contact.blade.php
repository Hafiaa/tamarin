@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">Contact Us</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                        </ol>
                    </nav>
                    <p class="lead mt-3">We'd love to hear from you! Reach out to us with any questions, feedback, or inquiries.</p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('images/contact-header.jpg') }}" alt="Contact Us" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information Section -->
    <section class="contact-info-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="contact-card h-100 p-4 bg-white rounded shadow-sm">
                        <div class="contact-icon mb-3 text-primary">
                            <i class="fas fa-map-marker-alt fa-3x"></i>
                        </div>
                        <h4 class="contact-title mb-3">Our Location</h4>
                        <p class="contact-text mb-0">{{ setting('address', 'Jl. Manggala No.161, RT.007/RW.007, Deplu, Cipadu Jaya, Kec. Larangan, Kota Tangerang, Banten 15155') }}</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="contact-card h-100 p-4 bg-white rounded shadow-sm">
                        <div class="contact-icon mb-3 text-primary">
                            <i class="fas fa-phone-alt fa-3x"></i>
                        </div>
                        <h4 class="contact-title mb-3">Phone</h4>
                        <p class="contact-text mb-2">
                            <strong>Phone:</strong> {{ setting('phone', '0813-1828-3874') }}
                        </p>
                        <!-- <p class="contact-text mb-0">
                            <strong>Email:</strong> <a href="mailto:{{ setting('email', 'info@tamacafe.com') }}" class="text-decoration-none">{{ setting('email', 'info@tamacafe.com') }}</a>
                        </p> -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-card h-100 p-4 bg-white rounded shadow-sm">
                        <div class="contact-icon mb-3 text-primary">
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                        <h4 class="contact-title mb-3">Business Hours</h4>
                        <p class="contact-text mb-0">{{ $contactInfo['hours'] ?? 'Monday - Sunday: 11:00 AM - 10:00 PM' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Map Section -->
    <section class="contact-form-map-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="contact-form-wrapper p-4 bg-white rounded shadow-sm h-100">
                        <h3 class="form-title mb-4">Send Us a Message</h3>
                        <form action="{{ route('company.contact.send') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="map-wrapper p-4 bg-white rounded shadow-sm h-100">
                        <h3 class="map-title mb-4">Find Us</h3>
                        <div class="map-container">
                            @if(isset($contactInfo['google_maps_embed']) && !empty($contactInfo['google_maps_embed']))
                                {!! $contactInfo['google_maps_embed'] !!}
                            @else
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.1179797985196!2d106.7464471!3d-6.2481807!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f092d71129ab%3A0x5722837cd22ca824!2sTamarin%20Nurseries%20Garden%20%26%20Cafe!5e0!3m2!1sid!2sid!4v1750335274927!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="section-subtitle">Find answers to common questions about Tamacafe</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-3 border rounded shadow-sm">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How far in advance should I book an event?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>We recommend booking your event at least 2-3 months in advance to ensure availability, especially for weekend dates and peak seasons. For larger events like weddings, 6-12 months advance booking is advisable. However, we do accommodate last-minute bookings based on availability.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border rounded shadow-sm">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Can I customize the menu for my event?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Absolutely! We offer customizable menu options for all events. Our chef can work with you to create a menu that suits your preferences, dietary requirements, and budget. We also offer tastings for larger events to ensure you're completely satisfied with your menu selections.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border rounded shadow-sm">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    What is your cancellation policy?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Our cancellation policy varies depending on the event size and timing. Generally, cancellations made 30+ days before the event receive a full refund of the deposit. Cancellations made 14-29 days before receive a 50% refund. Cancellations less than 14 days before the event are non-refundable. Please refer to your event contract for specific terms.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border rounded shadow-sm">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Do you accommodate dietary restrictions?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Yes, we can accommodate various dietary restrictions including vegetarian, vegan, gluten-free, and allergies. Please inform us of any dietary requirements when planning your event or making a reservation, and our chef will prepare suitable options.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded shadow-sm">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Is parking available?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Yes, we offer complimentary parking for our guests. Our parking lot can accommodate up to 50 vehicles. For larger events, we can arrange valet parking service at an additional cost.</p>
                                </div>
                            </div>
                        </div>
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
                    <h3 class="cta-title mb-2">Ready to Book Your Event?</h3>
                    <p class="cta-text mb-0">Contact us today to schedule your next memorable event at Tamacafe.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('reservations.create') }}" class="btn btn-light btn-lg">Book Now</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .contact-card {
        transition: all 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .map-container {
        height: 400px;
        width: 100%;
    }
    
    .map-container iframe {
        height: 100%;
        width: 100%;
        border: 0;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(0, 123, 255, 0.1);
        color: #B9C24B
    }
    
    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endpush
