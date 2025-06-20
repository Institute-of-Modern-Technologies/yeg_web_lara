<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Primary Meta Tags for SEO -->
    <title>{{ isset($metaTitle) ? $metaTitle : 'Young Experts Group - Technology, Entrepreneurship & Creativity Skills for Youth' }}</title>
    <meta name="title" content="{{ isset($metaTitle) ? $metaTitle : 'Young Experts Group - Technology, Entrepreneurship & Creativity Skills for Youth' }}">
    <meta name="description" content="{{ isset($metaDescription) ? $metaDescription : 'Young Experts Group partners with schools to provide innovative, engaging, and practical tech learning experiences that prepare students for a digital future.' }}">
    <meta name="keywords" content="{{ isset($metaKeywords) ? $metaKeywords : 'coding for kids, robotics, young entrepreneurs, digital skills, technology education, youth innovation, STEM, tech workshops, school programs' }}">
    <meta name="author" content="Young Experts Group">
    
    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ isset($metaTitle) ? $metaTitle : 'Young Experts Group - Technology, Entrepreneurship & Creativity Skills for Youth' }}">
    <meta property="og:description" content="{{ isset($metaDescription) ? $metaDescription : 'Young Experts Group partners with schools to provide innovative, engaging, and practical tech learning experiences that prepare students for a digital future.' }}">
    <meta property="og:image" content="{{ isset($metaImage) ? asset($metaImage) : asset('favicon-large.png') }}">
    
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ isset($metaTitle) ? $metaTitle : 'Young Experts Group - Technology, Entrepreneurship & Creativity Skills for Youth' }}">
    <meta name="twitter:description" content="{{ isset($metaDescription) ? $metaDescription : 'Young Experts Group partners with schools to provide innovative, engaging, and practical tech learning experiences that prepare students for a digital future.' }}">
    <meta name="twitter:image" content="{{ isset($metaImage) ? asset($metaImage) : asset('favicon-large.png') }}">
    
    <!-- Enhanced Favicon Implementation using Largest Available Files -->
    <!-- Primary favicon for most modern browsers (use existing large favicon) -->
    <link rel="icon" href="{{ asset('favicon-large.png') }}" type="image/png">
    
    <!-- Backup favicon.ico format for maximum browser compatibility -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Apple Touch Icons (use the largest available) -->
    <link rel="apple-touch-icon" href="{{ asset('favicon-large.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('favicon-large.png') }}">
    
    <!-- Microsoft Tile with largest icon -->
    <meta name="msapplication-TileImage" content="{{ asset('favicon-large.png') }}">
    <meta name="msapplication-TileColor" content="#c50000">
    
    <!-- Force favicon to be displayed larger -->
    <link rel="icon" href="{{ asset('favicon-large.png') }}" sizes="any">
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <meta name="theme-color" content="#c50000">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#c50000',
                        secondary: '#ffcb05',
                        'neon-pink': '#FF00FF'
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sticky-headers.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @yield('styles')
</head>
<body class="antialiased">
    @include('partials._navigation')
    
    @yield('content')
    
    @include('partials._footer')
    
    <!-- Sticky Header JS -->
    <script src="{{ asset('js/sticky-header.js') }}"></script>
    
    <!-- Mobile Menu Toggle Script (No longer needed for registration dropdown) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle could go here if needed
            console.log('Scripts loaded successfully');
            
            // Add click support for the register button (in addition to hover)
            const registerButton = document.getElementById('registerButton');
            if (registerButton) {
                registerButton.addEventListener('click', function(e) {
                    // This helps with mobile devices
                    e.preventDefault();
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
