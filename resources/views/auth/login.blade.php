@extends('layouts.auth')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-4">

            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">Welcome Back !</h5>
                    <p class="text-muted">Sign in to continue to {{ config('app.name', 'Laravel') }}.</p>
                </div>
                <div class="p-2 mt-4">
                    <form action="{{ route('login') }}" method="POST">
                    @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label">Email</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="username" name="email" placeholder="Enter email">
                        </div>

                        <div class="mb-3">

                            @if (Route::has('password.request'))
                                <div class="float-end">
                                    <a href="{{ route('password.request') }}" class="text-muted">{{ __('Forgot Your Password?') }}</a>
                                </div>
                            @endif

                            <label class="form-label" for="password-input">Password</label>
                            <div class="position-relative auth-pass-inputgroup mb-3">
                                <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror" placeholder="Enter password" id="password-input" name="password">
                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-success w-100" type="submit">{{ __('Login') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        <div class="mt-4 text-center">
            <p class="mb-0">Don't have an account ? <a href="#" class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
        </div>

    </div>
</div>

@endsection
