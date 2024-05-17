@extends('layouts.auth')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card mt-4">

                <div class="card-body p-4">
                    <div class="text-center mt-2">
                        <h5 class="text-primary">Password Reset</h5>
                        <p class="text-muted">Forget password reset form.</p>
                    </div>
                    <div class="p-2 mt-4">
                        <form action="{{ url('password-request') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="username">Email or Phone number (username).</label>
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                    <input type="text"  class="form-control pe-5 @error('username') is-invalid @enderror"
                                        placeholder="Enter username" id="username" name="username" required 
                                        value="{{ old('username') }}">
                                    @if ($errors->has('username'))
                                        @foreach ($errors->get('username') as $message)
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
