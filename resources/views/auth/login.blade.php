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
                        <form action="{{ url('auth/login') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="username" class="form-label">Enter Email or Phone number</label>
                                <input type="text" value="{{ old('username') }}"
                                    class="form-control @error('username') is-invalid @enderror" id="username"
                                    name="username" placeholder="Enter email">

                                @if ($errors->has('email'))
                                    @foreach ($errors->get('email') as $message)
                                        <label class="control-label text-danger" for="inputError"><i
                                                class="fa fa-times-circle-o"></i>
                                            <li>{{ $message }}</li>
                                        </label><br>
                                    @endforeach
                                @endif

                            </div>

                            <div class="mb-3">

                                @if (Route::has('password.request'))
                                    {{-- <div class="float-end">
                                        <a href="{{ route('password.request') }}"
                                            class="text-muted">{{ __('Forgot Your Password?') }}</a>
                                    </div> --}}
                                @endif

                                <label class="form-label" for="password-input">Password</label>
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                    <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror"
                                        placeholder="Enter password" id="password-input" name="password">
                                    @if ($errors->has('password'))
                                        @foreach ($errors->get('password') as $message)
                                            <label class="control-label text-danger" for="inputError"><i
                                                    class="fa fa-times-circle-o"></i>
                                                <li>{{ $message }}</li>
                                            </label><br>
                                        @endforeach
                                    @endif
                                    <button
                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                        type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                </div>
                            </div>

                            {{-- did you forget password? --}}
                            <div class="d-flex justify-content-between">
                                <div></div>
                                <div>
                                    <a href="{{ url('password-request') }}" class="text-muted">Forgot password?</a>
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
                <p class="mb-0">Download Agrihub App!<a target="_blank"
                        href="https://play.google.com/store/apps/details?id=m.omulimisa.uganda"
                        class="fw-semibold text-primary text-decoration-underline"> Now on Playstore </a> </p>
            </div>

        </div>
    </div>

@endsection
