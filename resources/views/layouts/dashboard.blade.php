<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard - Room Booking Platform')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div id="app">
        @yield('content')
    </div>

    <!-- Toast Notifications -->
    <div x-data="{ 
        show: false, 
        message: '', 
        type: 'success',
        showToast(msg, toastType = 'success') {
            this.message = msg;
            this.type = toastType;
            this.show = true;
            setTimeout(() => this.show = false, 3000);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="fixed top-4 right-4 z-50 max-w-sm w-full"
    style="display: none;">
        <div class="rounded-lg shadow-lg p-4"
             :class="{
                'bg-green-500 text-white': type === 'success',
                'bg-red-500 text-white': type === 'error',
                'bg-yellow-500 text-white': type === 'warning',
                'bg-blue-500 text-white': type === 'info'
             }">
            <div class="flex items-center justify-between">
                <span x-text="message"></span>
                <button @click="show = false" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Session Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('toast').showToast('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('toast').showToast('{{ session('error') }}', 'error');
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('toast').showToast('{{ $errors->first() }}', 'error');
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>