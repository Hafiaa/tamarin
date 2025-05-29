@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<!-- Page Header -->
<div class="bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-0">My Profile</h1>
                <p class="text-muted mb-0">Manage your account information</p>
            </div>
        </div>
    </div>
</div>

<!-- Profile Content -->
<div class="container py-5">
    <div class="row">
        <!-- Left Column - Profile Information -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <!-- Profile Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    @if(session('profile_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('profile_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('customer.dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-3 text-center mb-3 mb-md-0">
                                <div class="avatar-wrapper">
                                    @if($user->getFirstMediaUrl('avatar'))
                                        <img src="{{ $user->getFirstMediaUrl('avatar') }}" alt="{{ $user->name }}" class="rounded-circle avatar-img">
                                    @else
                                        <div class="avatar-placeholder rounded-circle">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="avatar-edit">
                                        <label for="avatar" class="btn btn-sm btn-primary">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                        <input type="file" id="avatar" name="avatar" class="d-none">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                                    <div class="form-text">Email cannot be changed. Contact support if you need to update your email.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                            @error('city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="state" class="form-label">State/Province</label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $user->state) }}">
                                @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
                                @error('postal_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" name="country">
                                <option value="">Select Country</option>
                                <option value="Indonesia" {{ old('country', $user->country) == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                <option value="Malaysia" {{ old('country', $user->country) == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                <option value="Singapore" {{ old('country', $user->country) == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                <!-- Add more countries as needed -->
                            </select>
                            @error('country')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Password Change Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    @if(session('password_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('password_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('customer.dashboard.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key me-2"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Account Settings -->
        <div class="col-lg-4">
            <!-- Account Settings Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Account Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="fw-bold">Email Notifications</h6>
                        <form action="{{ route('customer.dashboard.profile.notifications') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_reservation_updates" name="notify_reservation_updates" value="1" {{ $user->notify_reservation_updates ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_reservation_updates">
                                    Reservation updates
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_payment_updates" name="notify_payment_updates" value="1" {{ $user->notify_payment_updates ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_payment_updates">
                                    Payment updates
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_promotions" name="notify_promotions" value="1" {{ $user->notify_promotions ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_promotions">
                                    Promotions and special offers
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="notify_newsletters" name="notify_newsletters" value="1" {{ $user->notify_newsletters ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_newsletters">
                                    Newsletters
                                </label>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Save Preferences
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">Account Status</h6>
                        <p class="mb-2">
                            <span class="text-muted">Member Since:</span>
                            <span class="fw-medium">{{ $user->created_at->format('F d, Y') }}</span>
                        </p>
                        <p class="mb-0">
                            <span class="text-muted">Account Status:</span>
                            <span class="badge bg-success">Active</span>
                        </p>
                    </div>
                    
                    <hr>
                    
                    <div>
                        <h6 class="fw-bold text-danger">Danger Zone</h6>
                        <p class="small text-muted">Permanently delete your account and all associated data.</p>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash-alt me-2"></i> Delete Account
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quick Links</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <a href="{{ route('customer.dashboard.reservations') }}" class="text-decoration-none">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i> My Reservations
                            </a>
                        </li>
                        <li class="list-group-item px-0">
                            <a href="{{ route('customer.dashboard.payments') }}" class="text-decoration-none">
                                <i class="fas fa-credit-card me-2 text-primary"></i> Payment History
                            </a>
                        </li>
                        <li class="list-group-item px-0">
                            <a href="{{ route('customer.dashboard.testimonials') }}" class="text-decoration-none">
                                <i class="fas fa-star me-2 text-primary"></i> My Reviews
                            </a>
                        </li>
                        <li class="list-group-item px-0">
                            <a href="{{ route('company.contact') }}" class="text-decoration-none">
                                <i class="fas fa-headset me-2 text-primary"></i> Contact Support
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i> Warning: This action cannot be undone!
                </div>
                <p>Deleting your account will:</p>
                <ul>
                    <li>Permanently remove all your personal information</li>
                    <li>Cancel any upcoming reservations</li>
                    <li>Remove all your reviews and feedback</li>
                    <li>Delete your payment history</li>
                </ul>
                <p>Are you absolutely sure you want to proceed?</p>
                
                <form id="deleteAccountForm" action="{{ route('customer.dashboard.profile.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-3">
                        <label for="delete_confirmation" class="form-label">Type "DELETE" to confirm</label>
                        <input type="text" class="form-control" id="delete_confirmation" name="delete_confirmation" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Enter your password</label>
                        <input type="password" class="form-control" id="delete_password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Account</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    
    .avatar-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
    }
    
    .avatar-placeholder {
        width: 120px;
        height: 120px;
        background-color: #6c757d;
        color: white;
        font-size: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-edit {
        position: absolute;
        right: 0;
        bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview avatar image before upload
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatar');
        const avatarImg = document.querySelector('.avatar-img');
        const avatarPlaceholder = document.querySelector('.avatar-placeholder');
        
        if (avatarInput) {
            avatarInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        if (avatarImg) {
                            avatarImg.src = e.target.result;
                        } else if (avatarPlaceholder) {
                            // Create new image element
                            const newAvatar = document.createElement('img');
                            newAvatar.src = e.target.result;
                            newAvatar.classList.add('rounded-circle', 'avatar-img');
                            
                            // Replace placeholder with image
                            avatarPlaceholder.parentNode.replaceChild(newAvatar, avatarPlaceholder);
                        }
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
    
    // Confirm account deletion
    function confirmDelete() {
        const confirmation = document.getElementById('delete_confirmation').value;
        const password = document.getElementById('delete_password').value;
        
        if (confirmation === 'DELETE' && password) {
            document.getElementById('deleteAccountForm').submit();
        } else {
            alert('Please type "DELETE" and enter your password to confirm account deletion.');
        }
    }
</script>
@endpush
