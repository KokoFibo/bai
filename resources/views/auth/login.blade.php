@extends('layouts.app_login')

@section('content')
    <div class="container">
        <div class="row justify-content-center py-5">

            <img src="{{ asset('images/logo-only.png') }}" alt="Yifang Logo" style="opacity: .8; width:150px">
        </div>
        <div class="row justify-content-center ">
            <div class="col-xl-6 ">
                <div class="card">
                    <div class="card-header text-light text-center" style="background-color: #073256; ">
                        <h5>{{ __('Login') }}</h5>
                    </div>
                    {{-- <div class="card-body pt-4" style="background-color: #E5E7EB;">
                        <form method="POST" action="{{ route('login') }}"> --}}
                    <div x-data="{ submitButtonDisabled: false }" class="card-body pt-4" style="background-color: #E5E7EB;">
                        <form method="POST" action="{{ route('login') }}" x-on:submit="submitButtonDisabled = true">

                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('No ID') }}</label>

                                <div class="col-md-6">

                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror @error('email') is-invalid @enderror"
                                        name="username" value="{{ old('username') }}" required autocomplete="username"
                                        autofocus>

                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div x-data="{ showPassword: false }" class="col-md-6" style="position: relative; ">
                                    <input id="password" :type="showPassword ? 'text' : 'password'"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">
                                    <svg @click="showPassword = !showPassword" xmlns="http://www.w3.org/2000/svg"
                                        width="18" height="18" fill="gray"
                                        style="position: absolute; transform: translateY(-50%); top: 50%; right:2rem"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                        <path
                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                    </svg>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    {{-- <button type="submit" class="btn btn-primary"  --}}
                                    <button type="submit" class="btn btn-primary" x-bind:disabled="submitButtonDisabled"
                                        style="background-color: #073256; border-color: #073256;">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Lupa Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
