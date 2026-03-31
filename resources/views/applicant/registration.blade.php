@extends('layouts.applicant_layout')
@section('content')
    <section class="gradient-form h-screen bg-neutral-200 dark:bg-neutral-700">
        <div class="container h-full p-10">
            <div class="flex h-full flex-wrap items-center justify-center text-neutral-800 dark:text-neutral-200">
                <div class="w-full lg:w-1/2">
                    <div class="block rounded-lg bg-white shadow-lg dark:bg-neutral-800">
                        {{-- <div class="g-0 lg:flex lg:flex-wrap"> --}}
                        <div>
                            <!-- Left column container-->
                            <div class="px-4 md:px-0 lg:w-full">
                                <div class="md:mx-6 md:p-12">
                                    <!--Logo-->
                                    <div class="text-center">
                                        <img class="mx-auto w-48"
                                            src="https://tecdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp"
                                            alt="logo" />
                                        <h4 class="mb-12 mt-1 pb-1 text-xl font-semibold">
                                            We are The Lotus Team
                                        </h4>
                                    </div>

                                    <form action="/applicant/register" method="post">
                                        @csrf
                                        <!--Nama input-->
                                        <div class="flex flex-col mb-3">
                                            <label for="nama">Nama</label>
                                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                                class="bg-blue-100 rounded-lg py-1 px-3 mt-2">
                                            @error('nama')
                                                <div class="text-red-500">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!--Email input-->
                                        <div class="flex flex-col mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" name="email" id="email" value="{{ old('email') }}"
                                                class="bg-blue-100 rounded-lg py-1 px-3 mt-2">
                                            @error('email')
                                                <div class="text-red-500">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!--Password input-->
                                        <div class="flex flex-col mb-5">
                                            <label for="password">Password</label>
                                            <input type="text" name="password" id="password"
                                                value="{{ old('password') }}" class="bg-blue-100 rounded-lg py-1 px-3 mt-2">
                                            @error('password')
                                                <div class="text-red-500">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>



                                        <!--Submit button-->
                                        <div class="mb-12 pb-1 pt-1 text-center">

                                            <button
                                                class="mb-3 inline-block w-full rounded px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-dark-3 transition duration-150 ease-in-out hover:shadow-dark-2 focus:shadow-dark-2 focus:outline-none focus:ring-0 active:shadow-dark-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                                type="submit" data-twe-ripple-init data-twe-ripple-color="light"
                                                style="background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);">
                                                Register
                                            </button>


                                            <!--Forgot password link-->
                                            <a href="#!">Forgot password?</a>
                                        </div>

                                        <!--Register button-->
                                        <div class="flex items-center justify-between pb-6">
                                            <p class="mb-0 me-2">Already have an account?</p>

                                            <a href="/applicant">
                                                <button type="button"
                                                    class="inline-block rounded border-2 border-danger px-6 pb-[6px] pt-2 text-xs font-medium uppercase leading-normal text-danger transition duration-150 ease-in-out hover:border-danger-600 hover:bg-danger-50/50 hover:text-danger-600 focus:border-danger-600 focus:bg-danger-50/50 focus:text-danger-600 focus:outline-none focus:ring-0 active:border-danger-700 active:text-danger-700 dark:hover:bg-rose-950 dark:focus:bg-rose-950"
                                                    data-twe-ripple-init data-twe-ripple-color="light">
                                                    Back to login
                                                </button>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
