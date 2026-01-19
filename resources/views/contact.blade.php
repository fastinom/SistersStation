@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Contact Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">Contact Us</h1>
                <p class="lead text-muted">We'd love to hear from you! Get in touch with any questions or feedback.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Contact Form -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('contact.submit') }}">
                                @csrf
                                
                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Subject -->
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Message -->
                                <div class="mb-4">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-send me-2"></i>Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Get in Touch</h5>
                            
                            <!-- Email -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-envelope-fill text-primary me-3"></i>
                                    <strong>Email</strong>
                                </div>
                                <p class="text-muted mb-0">support@sistersstation.com</p>
                            </div>

                            <!-- Phone -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-telephone-fill text-primary me-3"></i>
                                    <strong>Phone</strong>
                                </div>
                                <p class="text-muted mb-0">+1 (555) 123-4567</p>
                            </div>

                            <!-- Hours -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-clock-fill text-primary me-3"></i>
                                    <strong>Business Hours</strong>
                                </div>
                                <p class="text-muted mb-1">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                <p class="text-muted mb-0">Saturday - Sunday: 10:00 AM - 4:00 PM</p>
                            </div>

                            <!-- Social Media -->
                            <div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-share-fill text-primary me-3"></i>
                                    <strong>Follow Us</strong>
                                </div>
                                <div class="d-flex gap-3">
                                    <a href="#" class="text-primary fs-5"><i class="bi bi-facebook"></i></a>
                                    <a href="#" class="text-primary fs-5"><i class="bi bi-instagram"></i></a>
                                    <a href="#" class="text-primary fs-5"><i class="bi bi-twitter"></i></a>
                                    <a href="#" class="text-primary fs-5"><i class="bi bi-pinterest"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
