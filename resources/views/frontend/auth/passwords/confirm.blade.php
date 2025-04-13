@extends('frontend.layouts.master')

@section('title')
Confirm Password-Ecommerce
@endsection
@section('content')

<section class="py-5 bg-light">
    <div class="container">
      <div class="row px-4 px-lg-5 py-lg-4 align-items-center">
        <div class="col-lg-6">
          <h1 class="h2 text-uppercase mb-0">Confirm Password</h1>
        </div>
        <div class="col-lg-6 text-lg-right">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-lg-end mb-0 px-0">
              <li class="breadcrumb-item"><a href="{{ asset('frontend/index.html') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Confirm-Password</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
</section>

  <section class="py-5">
    <div class="row">
        <div class="col-6 offset-3">
            <h2 class="h5 text-uppercase mb-4">{{ __('Confirm Password') }}</h2>
            {{ __('Please confirm your password before continuing.') }}
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="col-12">
                    <div class="form-group">
                        <label class="text-small text-uppercase" for="password">Password</label>
                        <input class="form-control form-control-lg" name="password" type="password" placeholder="Enter your password">
                        @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-dark" type="submit">{{ __('Confirm Password') }}</button>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
