<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Room Booking Platform')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50">
    <!-- Updated Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo_black.png') }}" alt="Createx" class="h-8 w-auto mr-3">
                        <span class="text-xl font-bold text-gray-900">Createx</span>
                    </a>
                </div>
                
                <!-- Navigation Links - Only show full nav on home page -->
                <div class="hidden md:flex items-center space-x-8">
                    @if(request()->routeIs('home'))
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">Home</a>
                        <a href="#about" class="text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200 scroll-smooth">About us</a>
                        <a href="#services" class="text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200 scroll-smooth">Services</a>
                        <a href="#contact" class="text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200 scroll-smooth">Contact us</a>
                    @endif
                </div>
                
                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">
                                Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">
                                Dashboard
                            </a>
                        @endif
                        
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900 font-medium">
                                {{ auth()->user()->name }}
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium border border-gray-300 rounded-lg hover:border-gray-400 hover:shadow-md transition-all duration-200">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-black text-white font-medium rounded-lg hover:bg-gray-800 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            Register
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button x-data @click="$dispatch('toggle-mobile-menu')" class="text-gray-700 hover:text-gray-900">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" class="md:hidden border-t border-gray-200">
            <div class="px-4 py-4 space-y-2">
                @if(request()->routeIs('home'))
                    <a href="{{ route('home') }}" class="block text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200">Home</a>
                    <a href="#about" class="block text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200">About us</a>
                    <a href="#services" class="block text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200">Services</a>
                    <a href="#contact" class="block text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200">Contact us</a>
                @else
                    <a href="{{ route('home') }}" class="block text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200">Home</a>
                @endif
                
                @guest
                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 text-gray-700 border border-gray-300 rounded-lg mb-2 hover:border-gray-400 hover:shadow-md transition-all duration-200">Login</a>
                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 hover:shadow-lg transition-all duration-200">Register</a>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-4 mt-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-4 mt-4">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-4 mt-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Logo & Description -->
                <div>
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('images/logo_white.png') }}" alt="Createx" class="h-8 w-auto mr-3">
                        <span class="text-xl font-bold text-white">Createx</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        The complete self-service ecosystem for creators
                    </p>
                </div>
                
                <!-- Services -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Services</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('dashboard') }}?type=studio" class="hover:text-white transition-colors duration-200">Photo/Video Studio</a></li>
                        <li><a href="{{ route('dashboard') }}?type=live+streaming" class="hover:text-white transition-colors duration-200">Live Streaming Room</a></li>
                        <li><a href="{{ route('dashboard') }}?type=editing+room" class="hover:text-white transition-colors duration-200">Editing Café</a></li>
                        <li><a href="{{ route('dashboard') }}?type=event+hall" class="hover:text-white transition-colors duration-200">Event Hall</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Feel free to reach us!</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transform hover:scale-110 transition-all duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transform hover:scale-110 transition-all duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transform hover:scale-110 transition-all duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.017 0H7.982C3.58 0 0 3.58 0 7.982v4.035C0 16.42 3.58 20 7.982 20h4.035C16.42 20 20 16.42 20 12.017V7.982C20 3.58 16.42 0 12.017 0zm3.982 7.982a.997.997 0 11-1.993 0 .997.997 0 011.993 0zM10 5.077c2.717 0 4.923 2.206 4.923 4.923S12.717 14.923 10 14.923 5.077 12.717 5.077 10 7.283 5.077 10 5.077zm0 1.077A3.846 3.846 0 106.154 10 3.846 3.846 0 0010 6.154z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transform hover:scale-110 transition-all duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017,6c0,0,1.98,0,3.983,0c0,0,0,4.083,0,6.006c1.834,0,3.994,0,5.981,0c0,2.004,0,3.986,0,5.994c-1.987,0-4.147,0-5.981,0c0,2.027,0,4.047,0,6c-2.003,0-3.983,0-3.983,0c0-1.953,0-3.973,0-6c-1.848,0-3.983,0-3.983,0c0-2.008,0-3.99,0-5.994c1.135,0,2.135,0,3.983,0C12.017,10.083,12.017,8,12.017,6z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400 text-sm">&copy; 2025 Createx. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
