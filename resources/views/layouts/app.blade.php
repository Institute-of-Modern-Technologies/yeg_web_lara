<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Primary Meta Tags for Enhanced SEO -->
    <title>{{ isset($metaTitle) ? $metaTitle : 'Young Experts Group - Technology, Entrepreneurship & Creativity Skills for Youth' }}</title>
    <meta name="title" content="{{ isset($metaTitle) ? $metaTitle : 'Young Experts Group - Technology, Entrepreneurship & Creativity Skills for Youth' }}">
    <meta name="description" content="{{ isset($metaDescription) ? $metaDescription : 'Young Experts Group partners with schools to provide innovative, engaging, and practical tech learning experiences that prepare students for a digital future.' }}">
    <meta name="keywords" content="{{ isset($metaKeywords) ? $metaKeywords : 'coding for kids, robotics, young entrepreneurs, digital skills, technology education, youth innovation, STEM, tech workshops, school programs, Nigeria tech education, Africa tech skills' }}">
    <meta name="author" content="Young Experts Group">
    
    <!-- Additional SEO Meta Tags -->
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    <meta name="geo.region" content="NG">
    <meta name="geo.placename" content="Nigeria">
    
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
    
    <!-- Enhanced Large Favicon Implementation using existing favicon from images folder -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}?v={{ rand(1000,9999) }}" sizes="512x512">
    
    <!-- Force favicon to display as large as possible -->
    <style>
        /* Force browsers to display favicon at maximum possible size */
        link[rel="icon"] {
            width: 64px !important; 
            height: 64px !important;
        }
    </style>
    
    <!-- Security Headers for SEO and Protection -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="referrer" content="no-referrer-when-downgrade">
    
    <!-- Social Media and SEO Verification -->
    <meta property="og:site_name" content="Young Experts Group">
    <meta property="og:locale" content="en_NG">
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}">
    
    <!-- Browser Config for SEO and Mobile -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <meta name="theme-color" content="#c50000">
    
    <!-- JSON-LD Structured Data for Enhanced SEO -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "Young Experts Group",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/favicon.png') }}",
      "description": "Young Experts Group partners with schools to provide innovative, engaging, and practical tech learning experiences that prepare students for a digital future.",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "Nigeria"
      },
      "offers": {
        "@type": "Offer",
        "category": "Technology Education Programs"
      },
      "areaServed": "Nigeria",
      "sameAs": [
        "https://www.facebook.com/youngexpertsgroup",
        "https://www.youtube.com/shorts/x_kUqKoTZR8",
        "https://www.tiktok.com/@youngexpertsgroup",
        "https://www.instagram.com/youngexpertsgroup"
      ]
    }
    </script>

    <!-- Resource Hints for Performance Optimization -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://code.jquery.com" crossorigin>
    
    <!-- Preload Critical Assets -->
    <link rel="preload" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    
    <!-- Fonts with Display Swap for Faster Rendering -->
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS with async loading -->
    <script src="https://cdn.tailwindcss.com" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#950713',
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
    <!-- Add custom styling for fixed header padding -->
    <style>
        body {
            padding-top: 76px; /* Height of the navbar + some extra padding */
        }
        /* Ensure mobile menus appear properly with fixed header */
        #mobile-menu {
            top: 76px;
            height: calc(100vh - 76px);
        }
    </style>

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
