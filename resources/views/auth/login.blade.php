@extends('layouts.guest')
@section('content')
    <div class="container" style="font-family: 'Poppins', sans-serif;">
        <div class="row justify-content-center">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="bg-white rounded p-4 w-100 fmxw-500">
                    <div class="text-center mb-4">
                    </div>

                    <form class="mt-4" action="{{ route('login') }}" method="POST">
                        @csrf
                        <h2 style="text-align:center; font-weight:600;">WELCOME!</h2>
                        <p style="text-align:center;">Login to start your session</p>
                        <!-- Email Input -->
                        <div class="form-group mb-4">
                            <label for="email" class="form-label">{{ __('Your Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light" id="basic-addon1">
                                    <i class="fa fa-envelope text-muted"></i>
                                </span>
                                <input name="email" type="email" class="form-control" placeholder="{{ __('Email') }}"
                                    id="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="form-group mb-4">
                            <label for="password" class="form-label">{{ __('Your Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light" id="basic-addon2">
                                    <i class="fa fa-lock text-muted"></i>
                                </span>
                                <input name="password" type="password" placeholder="{{ __('Password') }}" class="form-control" id="password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>

                        <!-- Show Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="showPassword">
                                <label class="form-check-label mb-0" for="showPassword">
                                    {{ __('Show Password') }}
                                </label>
                            </div>
                            <div>
                                <a href="{{ route('password.request') }}" class="small text-muted">
                                    {{ __('Forgot password?') }}
                                </a>
                            </div>
                        </div>

                        <!-- Sign In Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn text-white" style="background-color: #800000;">
                                {{ __('Sign in') }}
                            </button>
                        </div>
                    </form>

                    <!-- Register Link -->
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <span class="text-muted">
                            {{ __('Not registered?') }}
                            <a href="{{ route('register') }}" class="fw-bold text-primary">{{ __('Create account') }}</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showPasswordCheckbox = document.getElementById('showPassword');
        if (showPasswordCheckbox) {
            showPasswordCheckbox.addEventListener('click', function() {
                const passwordField = document.getElementById('password');
                if (passwordField) {  // Check if passwordField exists
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                } else {
                    console.error('Password field not found');
                }
            });
        }
    });
</script>