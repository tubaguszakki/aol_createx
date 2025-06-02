@extends('layouts.app')

@section('title', 'Room Booking Platform - Professional Spaces for Creators')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-gray-50 to-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="space-y-8">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                    Expand the Limits of Your Brand
                </h1>
                <h2 class="text-xl md:text-2xl text-gray-700 font-medium">
                    Empower Businesses to Create, Launch, and Grow
                </h2>
                <p class="text-lg text-gray-600 max-w-lg">
                    Unlock your brand's creative potential with our fully-equipped content studios, 
                    streaming rooms, editing suites, and event space.
                </p>
                
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-8 py-4 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        Start Creating
                        <svg class="ml-2 h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center px-8 py-4 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 hover:shadow-lg transform hover:scale-105 transition-all duration-300 group">
                        Start Creating
                        <svg class="ml-2 h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                @endauth
            </div>
            
            <!-- Right Image -->
            <div class="relative">
                <img src="{{ asset('images/first.png') }}" 
                     alt="Creative professionals working with digital tools" 
                     class="w-full h-auto max-w-lg mx-auto transform hover:scale-105 transition-transform duration-500 ease-in-out">
            </div>
        </div>
    </div>
</section>

<!-- Purpose Section -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left Image -->
            <div class="order-2 lg:order-1">
                <img src="{{ asset('images/second.png') }}" 
                     alt="Content creation tools and creative process" 
                     class="w-full h-auto max-w-lg transform hover:scale-105 transition-transform duration-500 ease-in-out">
            </div>
            
            <!-- Right Content -->
            <div class="order-1 lg:order-2 space-y-6">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                    Built for Creators, Backed by Purpose
                </h2>
                
                <div class="space-y-4 text-gray-600">
                    <p>
                        In an increasingly digital world, we believe that <strong class="text-gray-900">every business deserves the tools to stand out online</strong>, not just the big players.
                    </p>
                    <p>
                        That's why we created a space where creativity meets capability, a <strong class="text-gray-900">home for brands to tell their stories</strong> without worrying about tight timelines, or lack of expertise to still produce professional-grade content.
                    </p>
                    <p>
                        Our mission is clear: <strong class="text-gray-900">Empowering businesses to create content that builds trust, drives engagement, and grows sales.</strong>
                    </p>
                    <p>
                        With our end-to-end content infrastructure from studio lighting to live broadcast setups, we simplify the process so you can focus on what matters: <strong class="text-gray-900">telling your story.</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Differentiators Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-16">
            What makes us different?
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl p-6 border border-red-200 hover:shadow-lg hover:border-red-300 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-200 transition-colors duration-200">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-4 font-semibold text-gray-900">No setup stress, walk in and start shooting</h3>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white rounded-xl p-6 border border-red-200 hover:shadow-lg hover:border-red-300 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-200 transition-colors duration-200">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="ml-4 font-semibold text-gray-900">Affordable pricing with quality you can rely on</h3>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="bg-white rounded-xl p-6 border border-red-200 hover:shadow-lg hover:border-red-300 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-200 transition-colors duration-200">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-4 font-semibold text-gray-900">Flexible rental hours and custom packages</h3>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="bg-white rounded-xl p-6 border border-red-200 hover:shadow-lg hover:border-red-300 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-200 transition-colors duration-200">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="ml-4 font-semibold text-gray-900">Fully equipped rooms ready for any digital format</h3>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <p class="text-lg font-medium text-gray-700">
                We don't just rent out rooms. We empower your brand's digital presence.
            </p>
        </div>
    </div>
</section>

<!-- Trusted Brands Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">
                    Trusted by Brands<br>
                    Who Dare to Grow
                </h2>
                
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="w-full bg-black rounded-full h-2">
                        <div class="bg-gradient-to-r from-red-500 to-pink-500 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Right Image - Single Trusted Brands Image -->
            <div>
                <img src="{{ asset('images/brand.png') }}" 
                     alt="Trusted by leading brands including Intel Evo, Logitech, Cermati, Biznet, DOLab, RSMC, Indo, Kulkul, Portal, and FoodEh" 
                     class="w-full h-auto">
            </div>
        </div>
    </div>
</section>

<!-- Partnership CTA -->
<section class="py-20 bg-gray-900 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            Want to partner with us?
        </h2>
        <p class="text-xl text-gray-300 mb-8">
            We're open to co-promotions, sponsored content collaborations,<br>
            and long-term rental partnerships.
        </p>
        <a href="{{ route('register') }}" 
           class="inline-flex items-center px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
            Become a Partner
        </a>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Our Services
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Book, access, and use our creative spaces without any human interaction. Everything you need, available through our web.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $services = [
                    [
                        'icon' => 'camera',
                        'title' => 'Photo/Video Studio',
                        'description' => 'Professional photography and video studios with premium lighting.',
                        'features' => [
                            'Advanced lighting set with diffusers',
                            'Smart lock access with PIN',
                            'Auto lighting and camera controls',
                            'File auto upload to cloud'
                        ],
                        'rooms' => $rooms->where('type', 'studio')
                    ],
                    [
                        'icon' => 'video',
                        'title' => 'Live Streaming Room',
                        'description' => 'Dedicated setup for live streaming content.',
                        'features' => [
                            'Preset room settings',
                            'One click start streaming',
                            'Automatic noise and cloud backup',
                            'Auto tagging after session'
                        ],
                        'rooms' => $rooms->where('type', 'live streaming')
                    ],
                    [
                        'icon' => 'edit',
                        'title' => 'Editing Café',
                        'description' => 'High-performance workstations for content creators.',
                        'features' => [
                            'Customized login to workstation',
                            'Auto start time and software',
                            'Add time via app without interruption',
                            'Order edit files'
                        ],
                        'rooms' => $rooms->where('type', 'editing room')
                    ],
                    [
                        'icon' => 'users',
                        'title' => 'Event Hall',
                        'description' => 'Spacious venue for your events and workshops.',
                        'features' => [
                            'Book and Setup via web',
                            'Smart lock access with PIN',
                            'Auto lighting and camera controls',
                            'File auto upload to cloud'
                        ],
                        'rooms' => $rooms->where('type', 'event hall')
                    ]
                ];
            @endphp
            
            @foreach($services as $service)
                <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-red-200 transform hover:scale-105 transition-all duration-300 group">
                    <!-- Icon -->
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-red-200 transition-colors duration-200">
                        @if($service['icon'] === 'camera')
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        @elseif($service['icon'] === 'video')
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        @elseif($service['icon'] === 'edit')
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        @endif
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service['title'] }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $service['description'] }}</p>
                    
                    <!-- Features -->
                    <ul class="space-y-2 mb-6">
                        @foreach($service['features'] as $feature)
                            <li class="flex items-start text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    
                    @auth
                        <a href="{{ route('dashboard') }}?type={{ str_replace(' ', '+', $service['rooms']->first()->type ?? '') }}" 
                           class="w-full bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-800 hover:shadow-lg transform hover:scale-105 transition-all duration-300 text-center block">
                            Book Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="w-full bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-800 hover:shadow-lg transform hover:scale-105 transition-all duration-300 text-center block">
                            Book Now
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="contact" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-16">
            How It Works
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-red-200 transform group-hover:scale-110 transition-all duration-300">
                    <span class="text-2xl font-bold text-red-600">1</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Book online</h3>
                <p class="text-gray-600">
                    Choose your service, date, and time through our website.
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-red-200 transform group-hover:scale-110 transition-all duration-300">
                    <span class="text-2xl font-bold text-red-600">2</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Receive Access</h3>
                <p class="text-gray-600">
                    Get your PIN after payment.
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-red-200 transform group-hover:scale-110 transition-all duration-300">
                    <span class="text-2xl font-bold text-red-600">3</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Use & Enjoy</h3>
                <p class="text-gray-600">
                    Everything is automated - just show up and start creating!
                </p>
            
        </div>
    </div>
</section>
@endsection