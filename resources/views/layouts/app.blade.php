<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Complete favicon implementation for maximum size -->    
    <link rel="icon" href="{{ asset('images/favicon.png') }}?v=3" type="image/png" sizes="512x512">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}?v=3" sizes="512x512">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}?v=3" type="image/png">
    
    <!-- Force browser to reload favicon with cache busting -->
    <script>
    // This forces browsers to reload the favicon
    (function() {
        var link = document.querySelector("link[rel~='icon']");
        if (!link) {
            link = document.createElement('link');
            link.rel = 'icon';
            document.head.appendChild(link);
        }
        link.href = '{{ asset("images/favicon.png") }}?v=' + new Date().getTime();
    })();
    </script>
    
    <title>YEG</title>

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
    @yield('content')
    
    <!-- Sticky Header JS -->
    <script src="{{ asset('js/sticky-header.js') }}"></script>
    
    @yield('scripts')
</body>
</html>
