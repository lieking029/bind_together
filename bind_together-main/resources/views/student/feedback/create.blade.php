@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-3">Contact us</h1>
        <div class="row card p-3 justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <!-- Contact Information -->
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="text-center">
                            <h2 class="fw-bold">Bind TOGETHER</h2>
                            <p class="mb-0">Bataan Peninsula State University</p>
                            <p>bpsu.bindtogether@gmail.com</p>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="col-md-6">
                        <form action="{{ route('feedback.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name"
                                    value="{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-Mail</label>
                                <input type="email" class="form-control" id="email"
                                    value="{{ auth()->user()->email }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject"
                                    placeholder="Enter your subject">
                            </div>
                            @error('subject')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter Text..."></textarea>
                            </div>
                            @error('message')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="btn btn-danger">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
