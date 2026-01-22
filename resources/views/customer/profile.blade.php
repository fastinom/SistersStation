@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-person fs-1 text-white"></i>
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small">{{ $user->email }}</p>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a href="{{ route('customer.dashboard') }}" class="nav-link">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('customer.profile') }}" class="nav-link active">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                        <a href="{{ route('customer.orders') }}" class="nav-link">
                            <i class="bi bi-box me-2"></i> Orders
                        </a>
                        <a href="{{ route('customer.addresses') }}" class="nav-link">
                            <i class="bi bi-geo-alt me-2"></i> Addresses
                        </a>
                        <a href="{{ route('customer.reviews') }}" class="nav-link">
                            <i class="bi bi-star me-2"></i> Reviews
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">My Profile</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h6 class="mb-3">Personal Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ $user->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ $user->email }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="{{ $user->phone }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                           value="{{ $user->date_of_birth }}">
                                </div>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <h6 class="mb-3">About Me</h6>
                            <textarea class="form-control" id="bio" name="bio" rows="4" 
                                      placeholder="Tell us about yourself...">{{ $user->bio }}</textarea>
                        </div>

                        <!-- Account Information -->
                        <div class="mb-4">
                            <h6 class="mb-3">Account Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Account Type</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->user_type) }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Member Since</label>
                                    <input type="text" class="form-control" value="{{ $user->created_at->format('M d, Y') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
