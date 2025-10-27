@extends('themes.auth-core')
@section('content')
    <div>
        <div class="mb-8 sm:mb-10">
            <h1 class="mb-3 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                Complete Your Profile
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Please fill in your personal information to complete account setup.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-error-500/10 border border-error-500/20 dark:bg-error-500/5">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-error-500">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div>
            <form action="{{ route('auth.complete-setup') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <!-- First Name & Last Name Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                First Name<span class="text-error-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="nama_depan" 
                                placeholder="Enter your first name" 
                                value="{{ old('nama_depan') }}" 
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('nama_depan') border-error-500 dark:border-error-500/30 @enderror"
                                required
                            />
                            @error('nama_depan')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Last Name
                            </label>
                            <input 
                                type="text" 
                                name="nama_belakang" 
                                placeholder="Enter your last name" 
                                value="{{ old('nama_belakang') }}" 
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('nama_belakang') border-error-500 dark:border-error-500/30 @enderror"
                            />
                            @error('nama_belakang')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Place of Birth & Date of Birth Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Place of Birth -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Place of Birth<span class="text-error-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="tempat_lahir" 
                                placeholder="City/Province" 
                                value="{{ old('tempat_lahir') }}" 
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('tempat_lahir') border-error-500 dark:border-error-500/30 @enderror"
                                required
                            />
                            @error('tempat_lahir')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Date of Birth<span class="text-error-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="tanggal_lahir" 
                                value="{{ old('tanggal_lahir') }}" 
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('tanggal_lahir') border-error-500 dark:border-error-500/30 @enderror"
                                required
                            />
                            @error('tanggal_lahir')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Gender<span class="text-error-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <div class="flex items-center">
                                <input 
                                    type="radio" 
                                    id="male" 
                                    name="jenis_kelamin" 
                                    value="L" 
                                    {{ old('jenis_kelamin') === 'L' ? 'checked' : '' }}
                                    class="h-4 w-4 border-gray-300 text-brand-500"
                                    required
                                />
                                <label for="male" class="ml-2 text-sm text-gray-700 dark:text-gray-400">
                                    Male
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input 
                                    type="radio" 
                                    id="female" 
                                    name="jenis_kelamin" 
                                    value="P" 
                                    {{ old('jenis_kelamin') === 'P' ? 'checked' : '' }}
                                    class="h-4 w-4 border-gray-300 text-brand-500"
                                    required
                                />
                                <label for="female" class="ml-2 text-sm text-gray-700 dark:text-gray-400">
                                    Female
                                </label>
                            </div>
                        </div>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Religion & Blood Type Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Religion -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Religion
                            </label>
                            <select 
                                name="agama" 
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('agama') border-error-500 dark:border-error-500/30 @enderror"
                            >
                                <option value="">-- Select Religion --</option>
                                <option value="Islam" {{ old('agama') === 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama') === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Hindu" {{ old('agama') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama') === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama') === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>

                        <!-- Blood Type -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Blood Type
                            </label>
                            <select 
                                name="gol_darah" 
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('gol_darah') border-error-500 dark:border-error-500/30 @enderror"
                            >
                                <option value="">-- Select Blood Type --</option>
                                <option value="A" {{ old('gol_darah') === 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('gol_darah') === 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('gol_darah') === 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('gol_darah') === 'O' ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                    </div>

                    <!-- Height & Weight Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Height -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Height (cm)
                            </label>
                            <input 
                                type="number" 
                                name="tinggi_badan" 
                                placeholder="e.g. 170" 
                                value="{{ old('tinggi_badan') }}" 
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('tinggi_badan') border-error-500 dark:border-error-500/30 @enderror"
                            />
                            @error('tinggi_badan')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Weight -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Weight (kg)
                            </label>
                            <input 
                                type="number" 
                                name="berat_badan" 
                                placeholder="e.g. 65" 
                                value="{{ old('berat_badan') }}" 
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('berat_badan') border-error-500 dark:border-error-500/30 @enderror"
                            />
                            @error('berat_badan')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="pt-4">
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                            Complete Setup & Verify Email
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
