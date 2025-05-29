@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Reset Password</h4>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <p>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Email Password Reset Link
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light">
                    <p class="mb-0 text-center"><a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
