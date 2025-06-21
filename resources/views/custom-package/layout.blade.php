@extends('layouts.app')

@push('styles')
<style>
    /* Reset and base styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    /* Custom styles for the wizard */
    .step-indicator {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin: 2rem auto;
        max-width: 900px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .step {
        flex: 1;
        position: relative;
        text-align: center;
        padding: 0 10px;
        z-index: 1;
    }
    
    .step-number {
        width: 36px;
        height: 36px;
        line-height: 34px;
        border-radius: 50%;
        background: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        margin: 0 auto 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        font-size: 15px;
    }
    
    .step.active .step-number,
    .step.completed .step-number {
        background: #5b7917;
        color: white;
        border-color: #5b7917;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(91, 121, 23, 0.2);
    }
    
    .step.completed .step-number::after {
        content: '✓';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .step.completed .step-number::after {
        content: '✓';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .step-title {
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0;
        transition: all 0.3s ease;
        white-space: nowrap;
        line-height: 1.4;
        padding: 0 4px;
    }
    
    .step.active .step-title,
    .step.completed .step-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 15px;
    }
    
    .step-connector {
        flex: 1;
        height: 2px;
        background: #e9ecef;
        margin: 0 8px;
        position: relative;
        top: 16px;
        border-radius: 2px;
        overflow: hidden;
    }
    
    .step-connector::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0;
        background: #5b7917;
        transition: width 0.3s ease;
    }
    
    .step-connector.active::after {
        width: 100%;
    }
    
    /* Form styles */
    .form-container {
        background: white;
        border-radius: 12px;
        padding: 2.5rem 3rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin: 1.5rem auto 3rem;
        max-width: 1000px;
        border: 1px solid #e8e8e8;
    }
    
    @media (min-width: 768px) {
        .form-container {
            padding: 2.5rem 3rem;
        }
    }
    
    .form-label {
        font-weight: 500;
    }
    
    .form-control, .form-select {
        border-radius: 6px;
        padding: 0.6rem 0.9rem;
        border: 1px solid #dee2e6;
        transition: all 0.15s;
        font-size: 0.9rem;
        height: auto;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #5b7917;
        box-shadow: 0 0 0 0.25rem rgba(91, 121, 23, 0.2);
    }
    
    .btn-primary {
        background-color: #5b7917;
        border-color: #5b7917;
        padding: 0.55rem 1.5rem;
        font-weight: 500;
        font-size: 0.9rem;
        border-radius: 6px;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-primary:hover, .btn-primary:focus {
        background-color: #4a6313;
        border-color: #4a6313;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(91, 121, 23, 0.2);
    }
    
    .btn-outline-secondary {
        border-radius: 6px;
        padding: 0.55rem 1.5rem;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-width: 1px;
    }
    
    .btn-outline-secondary:hover, .btn-outline-secondary:focus {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    /* Navigation buttons */
    .navigation-buttons {
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }
    
    @media (max-width: 575.98px) {
        .navigation-buttons {
            flex-direction: column-reverse;
            gap: 0.75rem;
        }
        
        .navigation-buttons .btn {
            width: 100%;
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .step-indicator {
            padding: 1.25rem 1rem;
            margin-bottom: 2rem;
            border-radius: 10px;
        }
        
        .step {
            padding: 0 5px;
        }
        
        .step-title {
            font-size: 11px;
            white-space: normal;
            line-height: 1.2;
            height: 2.4em;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .step-number {
            width: 28px;
            height: 28px;
            line-height: 26px;
            font-size: 13px;
            margin-bottom: 6px;
        }
        
        .step-connector {
            top: 14px;
            height: 2px;
            margin: 0 5px;
        }
        
        .form-container {
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .btn {
            padding: 0.6rem 1.25rem;
            font-size: 0.9rem;
            width: 100%;
        }
        
        .btn i {
            margin: 0 0.25rem;
        }
    }
    
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>
@endpush

@section('content')
<div class="py-5 py-md-6" style="background-color: #f9fafb;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="d-flex justify-content-between position-relative">
                    <!-- Progress Bar -->
                    <div class="progress position-absolute w-100" style="height: 2px; top: 15px; z-index: 0;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%; transition: width 0.3s ease;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <!-- Step 1 -->
                    <div class="step {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
                        <div class="step-number d-flex align-items-center justify-content-center">
                            {{ $currentStep > 1 ? '✓' : '1' }}
                        </div>
                        <div class="step-title">
                            Detail Acara
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="step {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}">
                        <div class="step-number d-flex align-items-center justify-content-center">
                            {{ $currentStep > 2 ? '✓' : '2' }}
                        </div>
                        <div class="step-title">
                            Pilih Layanan
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="step {{ $currentStep >= 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}">
                        <div class="step-number d-flex align-items-center justify-content-center">
                            {{ $currentStep > 3 ? '✓' : '3' }}
                        </div>
                        <div class="step-title">
                            Review
                        </div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="step {{ $currentStep >= 4 ? 'active' : '' }}">
                        <div class="step-number d-flex align-items-center justify-content-center">
                            4
                        </div>
                        <div class="step-title">
                            Selesai
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Content -->
            <div class="form-container">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>
                                {{ session('error') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                            <div>
                                <strong>Terjadi kesalahan:</strong>
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('step-content')
            </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Add any custom scripts here if needed -->
@endpush

@endsection
