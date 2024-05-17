@extends('layouts.auth')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card mt-4">

                <div class="card-body p-4">
                    <div class="text-center mt-2">
                        <h5 class="text-primary">Password Reset</h5>
                        <p class="text-muted">Reset your password to proceed.</p>
                    </div>
                    <div class="p-2 mt-4">
                        <form action="{{ url('auth/password-reset-form') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="token" class="form-label">Token</label>
                                <input type="text" name="token" value="{{ $token }}" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">

                                @if (Route::has('password.request'))
                                    {{-- <div class="float-end">
                                        <a href="{{ route('password.request') }}"
                                            class="text-muted">{{ __('Forgot Your Password?') }}</a>
                                    </div> --}}
                                @endif

                                <label class="form-label" for="password-input">Enter New Password</label>
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                    <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror"
                                        placeholder="Enter password" id="password-input" name="password"
                                        value="{{ old('password') }}">
                                    @if ($errors->has('password'))
                                        @foreach ($errors->get('password') as $message)
                                            <label class="control-label text-danger" for="inputError"><i
                                                    class="fa fa-times-circle-o"></i>
                                                <li>{{ $message }}</li>
                                            </label><br>
                                        @endforeach
                                    @endif
                                </div>


                                <label class="form-label" for="password-input">Re-Enter Password</label>
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                    <input type="password"
                                        class="form-control pe-5 @error('password_1') is-invalid @enderror"
                                        placeholder="Enter password" id="password-input" name="password_1"
                                        value="{{ old('password_1') }}">
                                    @if ($errors->has('password_1'))
                                        @foreach ($errors->get('password_1') as $message)
                                            <label class="control-label text-danger" for="inputError"><i
                                                    class="fa fa-times-circle-o"></i>
                                                <li>{{ $message }}</li>
                                            </label><br>
                                        @endforeach
                                    @endif
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
