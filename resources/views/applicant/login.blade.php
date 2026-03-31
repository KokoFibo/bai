@extends('layouts.applicant_layout')
@section('content')
    <section class="gradient-form h-screen bg-neutral-200 dark:bg-neutral-700">
        <div class="container h-full p-10">
            <div class="flex h-full flex-wrap items-center justify-center text-neutral-800 dark:text-neutral-200">
                <div class="w-full">
                    <div class="block rounded-lg bg-white shadow-lg dark:bg-neutral-800">
                        <div class="g-0 lg:flex lg:flex-wrap">
                            <!-- Left column container-->
                            <div class="px-4 md:px-0 lg:w-6/12">
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
                                    <p class="bg-indigo-300 text-white py-2 px-3 rounded-xl my-3">Data berhasil di register
                                    </p>
                                    <form>
                                        <p class="mb-4">Please login to your account</p>
                                        <!--Email input-->
                                        <div class="flex flex-col mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" name="email" id="email"
                                                class="bg-blue-100 rounded-lg py-1 px-3 mt-2">
                                        </div>

                                        <!--Password input-->
                                        <div class="flex flex-col mb-5">
                                            <label for="password">Password</label>
                                            <input type="text" name="password" id="password"
                                                class="bg-blue-100 rounded-lg py-1 px-3 mt-2">
                                        </div>

                                        <!--Submit button-->
                                        <div class="mb-12 pb-1 pt-1 text-center">
                                            <button
                                                class="mb-3 inline-block w-full rounded px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-dark-3 transition duration-150 ease-in-out hover:shadow-dark-2 focus:shadow-dark-2 focus:outline-none focus:ring-0 active:shadow-dark-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                                type="button" data-twe-ripple-init data-twe-ripple-color="light"
                                                style="
                        background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
                      ">
                                                Log in
                                            </button>

                                            <!--Forgot password link-->
                                            <a href="#!">Forgot password?</a>
                                        </div>

                                        <!--Register button-->
                                        <div class="flex items-center justify-between pb-6">
                                            <p class="mb-0 me-2">Don't have an account?</p>
                                            <a href="/applicant/registration">
                                                <button type="button"
                                                    class="inline-block rounded border-2 border-danger px-6 pb-[6px] pt-2 text-xs font-medium uppercase leading-normal text-danger transition duration-150 ease-in-out hover:border-danger-600 hover:bg-danger-50/50 hover:text-danger-600 focus:border-danger-600 focus:bg-danger-50/50 focus:text-danger-600 focus:outline-none focus:ring-0 active:border-danger-700 active:text-danger-700 dark:hover:bg-rose-950 dark:focus:bg-rose-950"
                                                    data-twe-ripple-init data-twe-ripple-color="light">
                                                    Register
                                                </button>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Right column container with background and description-->
                            <div class="flex items-center rounded-b-lg lg:w-6/12 lg:rounded-e-lg lg:rounded-bl-none"
                                style="background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593)">
                                <div class="px-4 py-6 text-white md:mx-6 md:p-12">
                                    <h4 class="mb-6 text-xl font-semibold">
                                        We are more than just a company
                                    </h4>
                                    <p class="text-sm">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing
                                        elit, sed do eiusmod tempor incididunt ut labore et
                                        dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud exercitation ullamco laboris nisi ut aliquip ex
                                        ea commodo consequat.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
