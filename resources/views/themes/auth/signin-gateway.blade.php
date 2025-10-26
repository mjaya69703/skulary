@extends('themes.auth-core')
@section('content')
    <div x-data="{ selectedRole: null }">
        <div class="mb-8 sm:mb-10">
            <h1 class="mb-3 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                Choose Your Role
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                You have multiple roles. Please select one to continue.
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
            <form action="{{ route('auth.gateway-set') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <!-- Roles -->
                    <div>
                        <label class="mb-4 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Select Role<span class="text-error-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @foreach ($roles as $role)
                                <div class="group">
                                    <input 
                                        type="radio" 
                                        id="role_{{ $role->id }}" 
                                        name="role" 
                                        value="{{ $role->name }}" 
                                        class="sr-only" 
                                        required 
                                        @change="selectedRole = '{{ $role->name }}'"
                                    />
                                    <label 
                                        for="role_{{ $role->id }}" 
                                        class="flex items-start gap-5 p-5 rounded-lg border-2 cursor-pointer transition-all duration-200"
                                        :class="selectedRole === '{{ $role->name }}' 
                                            ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' 
                                            : 'border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-900 hover:border-brand-300 dark:hover:border-brand-600'"
                                    >
                                        <div class="relative flex-shrink-0 mt-1">
                                            <div 
                                                class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-colors duration-200"
                                                :class="selectedRole === '{{ $role->name }}'
                                                    ? 'border-brand-500 bg-brand-500'
                                                    : 'border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800'"
                                            >
                                                <span 
                                                    class="transition-opacity duration-200"
                                                    :class="selectedRole === '{{ $role->name }}' ? 'opacity-100' : 'opacity-0'"
                                                >
                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10 3L4.5 9L2 6.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-1">
                                                {{ ucfirst($role->name) }}
                                            </p>
                                            @if ($role->description)
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $role->description }}
                                                </p>
                                            @else
                                                <p class="text-xs text-gray-500 dark:text-gray-500">
                                                    Access as {{ ucfirst($role->name) }}
                                                </p>
                                            @endif
                                        </div>
                                        @if ($role->icon)
                                            <div class="flex-shrink-0 ml-4">
                                                <i class="{{ $role->icon }} text-lg transition-colors duration-200"
                                                   :class="selectedRole === '{{ $role->name }}'
                                                       ? 'text-brand-500'
                                                       : 'text-gray-400 dark:text-gray-500'"
                                                ></i>
                                            </div>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            :disabled="!selectedRole"
                            class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg shadow-theme-xs disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="selectedRole 
                                ? 'bg-brand-500 hover:bg-brand-600 cursor-pointer' 
                                : 'bg-gray-400 dark:bg-gray-700'"
                        >
                            Continue as Selected Role
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
