@extends('layouts.auth')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-4">

            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">OTP Verification</h5>
                    <p class="text-muted">Please enter the OTP to verify your account. <br>A Code has been sent to <span class="text-dark">@if(auth()->user()->two_auth_method=="SMS"){{  str_repeat('*', 10).''.substr(str_replace('+', '', auth()->user()->phone), -2)  }}@else {{ maskEmail(auth()->user()->email) }} @endif</span></p>
                </div>
                <div class="p-2 mt-4">
                    <form action="{{ route('otp.verify') }}" method="POST">
                    @csrf

                        <div class="mb-3">
                            <label for="otp" class="form-label">One-Time Password</label>
                            <input type="text" class="form-control @error('otp') is-invalid @enderror" id="otp" name="otp" placeholder="123456" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            @if (Route::has('otp.resend'))
                                <div class="float-end">
                                    <p><a href="{{ route('otp.resend') }}" class="text-muted">{{ __('Resend code') }}</a></p>
                                </div>
                            @endif
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
            <p class="mb-0">{{ __('otp.otp_not_received') }} <a href="{{ route('logout') }}"
     onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();" class="fw-semibold text-primary text-decoration-underline"> {{ __('otp.otp_logout') }} </a> </p>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </div>
</div>

@endsection
    {{-- <div class="col-sm text-right">
      <span id="timer" class="text-success"><span id="time">30</span> seconds</span>
      @if (Route::has('otp.resend'))
          <a class="btn-link" href="{{ route('otp.resend') }}">
              {{ __('Resend code') }}
          </a>
      @endif
  </div> --}}
@section('scripts')
  
  <script>
    var counter = 30;
    var interval = setInterval(function() {
        counter--;
        // Display 'counter' wherever you want to display it.
        if (counter <= 0) {
            clearInterval(interval);
            $('#timer').html('<span class="text-danger">Didn\'t receive the SMS?</span> ');  
            return;
        }else{
          $('#time').text(counter);
          // console.log("Timer --> " + counter);
        }
    }, 1000);
  </script>
@endsection
                
