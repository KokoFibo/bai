<div>
    @section('title', 'User Settings')

    <div class="container">

        <div class="mx-auto col-xl-8 col-12 pt-4">
            {{-- <h5>
                Hello, {{ namaDiAside($name) }} selamat datang di menu User Setting.
            </h5> --}}
            <button class="mx-auto col-12 btn btn-success btn-large  nightowl-daylight">
                <h3 class="px-3">{{ __('User Settings') }}</h3>
            </button>
        </div>



        <div class="card mt-5 col-xl-8 col-12 mx-auto">
            <div class="card-header">
                <h5>{{ __('Rubah Email') }}</h5>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror">

                    @error('email')
                        <div class="invalid-feedback">
                            {{ __('Format email salah') }}.
                        </div>
                    @enderror
                </div>

                <button wire:click="changeEmail"
                    class="btn btn-outline-success  nightowl-daylight">{{ __('Simpan') }}</button>
            </div>
        </div>
        <div class="card mt-5 col-xl-8 col-12 mx-auto">
            <div class="card-header">
                <h5>{{ __('Rubah Password') }}</h5>
            </div>
            <div class="card-body">
                <div x-data="{ showPassword: false }" class="input-group mb-3">
                    <input wire:model="old_password" :type="showPassword ? 'text' : 'password'"
                        class="form-control @error('old_password') is-invalid @enderror"
                        placeholder="{{ __('Password lama') }}">

                    <svg @click="showPassword = !showPassword" xmlns="http://www.w3.org/2000/svg" width="18"
                        height="18" fill="gray"
                        style="position: absolute; transform: translateY(-50%); top: 50%; right: 0.75rem;"
                        viewBox="0 0 16 16">
                        <path
                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                        <path
                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                    </svg>

                    @error('old_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div x-data="{ showPassword: false }" class="input-group mb-3">
                    <input wire:model="new_password" :type="showPassword ? 'text' : 'password'"
                        class="form-control @error('new_password') is-invalid @enderror"
                        placeholder="{{ __('Password baru') }}">
                    <svg @click="showPassword = !showPassword" xmlns="http://www.w3.org/2000/svg" width="18"
                        height="18" fill="gray"
                        style="position: absolute; transform: translateY(-50%); top: 50%; right: 0.75rem;"
                        viewBox="0 0 16 16">
                        <path
                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                        <path
                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                    </svg>
                    @error('new_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div x-data="{ showPassword: false }" class="input-group mb-3">
                    <input wire:model="confirm_password" :type="showPassword ? 'text' : 'password'"
                        class="form-control @error('confirm_password') is-invalid @enderror"
                        placeholder="{{ __('Konfirmasi password') }}">
                    <svg @click="showPassword = !showPassword" xmlns="http://www.w3.org/2000/svg" width="18"
                        height="18" fill="gray"
                        style="position: absolute; transform: translateY(-50%); top: 50%; right: 0.75rem;"
                        viewBox="0 0 16 16">
                        <path
                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                        <path
                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                    </svg>
                    @error('confirm_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button wire:click="changePassword"
                    class="btn btn-outline-success  nightowl-daylight">{{ __('Simpan') }}</button>

            </div>


        </div>
        <div class="card mt-5 col-xl-8 col-12 mx-auto">
            <div class="card-header">
                <h5>{{ __('') }}Rubah Bahasa</h5>
            </div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input wire:model="language" value="Id" class="form-check-input" type="radio"
                        name="flexRadioDefault" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">{{ __('Indonesia') }}</label>
                </div>
                <div class="form-check mb-4">
                    <input wire:model="language" value="Cn" class="form-check-input" type="radio"
                        name="flexRadioDefault" id="flexRadioDefault2">
                    <label class="form-check-label" for="flexRadioDefault2">{{ __('Mandarin') }}</label>
                </div>

                <button wire:click="changeLanguage"
                    class="btn btn-outline-success nightowl-daylight">{{ __('Simpan') }}</button>

            </div>


        </div>
    </div>
</div>
