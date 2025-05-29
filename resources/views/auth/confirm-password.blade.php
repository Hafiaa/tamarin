@extends('layouts.app')

@section('title', 'Confirm Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Confirm Password</h4>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <p>This is a secure area of the application. Please confirm your password before continuing.</p>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Confirm Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
