@extends('layouts.app')

@section('content')
<!-- Google Fonts Import for Kid-friendly fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Fredoka:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Custom Font Styles -->
<style>
    .title-font {
        font-family: 'Fredoka One', cursive;
    }
    .body-font {
        font-family: 'Fredoka', sans-serif;
    }
    .montserrat-font {
        font-family: 'Montserrat', sans-serif;
    }
    @keyframes float {
        0% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0); }
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
</style>

<!-- Full Page Background with Image -->
<div class="fixed inset-0 -z-10">
    <img src="{{ asset('images/image2.png') }}" alt="Background" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/60"></div>
    <!-- Modern animated gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-tr from-[#950713]/10 via-transparent to-[#0f5687]/20 opacity-75 animate-pulse"></div>
</div>

<!-- Header Section -->
<div class="relative">
    <div class="container mx-auto px-6 pt-16 pb-24">
        <div class="text-left max-w-3xl">
            <h1 class="text-white leading-tight montserrat-font font-bold">
                <span class="block text-5xl md:text-6xl">Career</span>
                <span class="block text-4xl md:text-5xl mt-1">Guidance</span>
            </h1>
            <p class="text-white text-lg md:text-xl mt-4 mb-6 max-w-xl body-font">
                Helping Young Minds Discover Their Future Pathways
            </p>
            <a href="{{ url('schools/register') }}" class="inline-block px-8 py-3 bg-primary hover:bg-primary/90 text-white body-font font-medium rounded-md transition-all duration-300">
                START YOUR JOURNEY
            </a>
        </div>
    </div>
</div>

<!-- Introduction Cards -->
<div class="container mx-auto px-4 pb-24 relative">
    <!-- Section Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#0f5687]/10 to-transparent rounded-xl -z-10"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Career Exploration Card -->
        <div class="bg-[#0f5687] rounded-sm p-6">
            <h2 class="text-2xl font-bold text-white mb-5 montserrat-font"><i class="fas fa-compass mr-2"></i>Career Exploration</h2>
            <p class="text-white text-sm mb-4 body-font">
                At YEG, we believe career guidance should start early. Our program introduces children to a wide range of tech-related careers through hands-on experiences, guest speakers, and project-based learning.
            </p>
            <p class="text-white text-sm body-font">
                Students discover career possibilities they might never have considered, expanding their vision of the future while developing practical skills.
            </p>
        </div>
        
        <!-- Skills Development Card -->
        <div class="bg-[#0f5687] rounded-sm p-6">
            <h2 class="text-2xl font-bold text-white mb-5 montserrat-font"><i class="fas fa-laptop-code mr-2"></i>Future Skills</h2>
            <div class="flex items-start mb-4">
                <div class="mr-3 mt-1 text-sky-300">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p class="text-white text-sm body-font"><strong>Digital Literacy:</strong> Essential tech skills for any future career path</p>
            </div>
            <div class="flex items-start mb-4">
                <div class="mr-3 mt-1 text-sky-300">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p class="text-white text-sm body-font"><strong>Problem Solving:</strong> Analytical thinking and creative solutions</p>
            </div>
            <div class="flex items-start">
                <div class="mr-3 mt-1 text-sky-300">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p class="text-white text-sm body-font"><strong>Collaboration:</strong> Working together effectively in teams</p>
            </div>
        </div>
        
        <!-- Industry Connection Card -->
        <div class="bg-[#0f5687] rounded-sm p-6">
            <h2 class="text-2xl font-bold text-white mb-5 montserrat-font"><i class="fas fa-handshake mr-2"></i>Industry Connections</h2>
            <p class="text-white text-sm mb-4 body-font">
                Our students connect with real-world professionals through:
            </p>
            <div class="grid grid-cols-1 gap-2 mb-5">
                <div class="text-white text-sm body-font">• Virtual Tours of Tech Companies</div>
                <div class="text-white text-sm body-font">• Professional Mentorship Programs</div>
                <div class="text-white text-sm body-font">• Industry Expert Workshops</div>
                <div class="text-white text-sm body-font">• Career Day Events</div>
            </div>
            <a href="{{ url('schools/register') }}" class="inline-block w-full bg-primary hover:bg-primary/90 text-white text-center py-2 font-medium rounded-sm transition-all duration-300 body-font">
                GET CONNECTED
            </a>
        </div>
    </div>
</div>

<!-- Career Pathways Section -->
<div class="container mx-auto px-4 py-12 relative">
    <!-- Section Overlay -->
    <div class="absolute inset-0 bg-gradient-to-l from-[#950713]/10 to-transparent rounded-xl -z-10"></div>
    <h2 class="text-3xl font-bold text-white mb-8 montserrat-font text-center"><i class="fas fa-road mr-2"></i>Career Pathways</h2>
    <p class="text-white text-center mb-8 body-font bg-[#0f5687]/50 py-3 px-4 rounded-md font-bold inline-block mx-auto">YEG helps students explore these exciting tech career paths:</p>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-16">
        <!-- Software Developer Path -->
        <div class="bg-[#0f5687] rounded-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16">
                <div class="bg-primary text-white absolute transform rotate-45 text-center text-sm font-bold py-1 right-[-35px] top-[20px] w-[120px]">Popular!</div>
            </div>
            <div class="text-white text-3xl mb-4 text-center">
                <i class="fas fa-code"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-3 montserrat-font text-center">Software Developer</h3>
            <p class="text-white text-sm body-font">Create apps, websites, and programs that change how we live, work, and play.</p>
        </div>
        
        <!-- Digital Artist Path -->
        <div class="bg-[#0f5687] rounded-sm p-6">
            <div class="text-white text-3xl mb-4 text-center">
                <i class="fas fa-paint-brush"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-3 montserrat-font text-center">Digital Artist</h3>
            <p class="text-white text-sm body-font">Design amazing graphics, animations, and visual experiences for games, movies, and more.</p>
        </div>
        
        <!-- Data Scientist Path -->
        <div class="bg-[#0f5687] rounded-sm p-6">
            <div class="text-white text-3xl mb-4 text-center">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-3 montserrat-font text-center">Data Scientist</h3>
            <p class="text-white text-sm body-font">Analyze information to solve problems and make predictions about our world.</p>
        </div>
        
        <!-- Entrepreneur Path -->
        <div class="bg-[#0f5687] rounded-sm p-6">
            <div class="text-white text-3xl mb-4 text-center">
                <i class="fas fa-lightbulb"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-3 montserrat-font text-center">Tech Entrepreneur</h3>
            <p class="text-white text-sm body-font">Start your own tech company and turn your ideas into products that help people.</p>
        </div>
    </div>
</div>

<!-- Career Resources Section -->
<div class="container mx-auto px-4 py-12 relative">
    <!-- Section Overlay -->
    <div class="absolute inset-0 bg-gradient-to-tr from-[#0f5687]/15 via-transparent to-[#950713]/10 rounded-xl -z-10"></div>
    <h2 class="text-3xl font-bold text-white mb-8 montserrat-font text-center"><i class="fas fa-book mr-2"></i>Career Resources</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
        <!-- Career Assessment -->
        <div class="bg-[#0f5687]/90 rounded-sm p-5">
            <div class="flex items-start">
                <div class="mr-3 text-primary text-2xl">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold montserrat-font">Career Assessment</h3>
                    <p class="text-white text-sm body-font">Our fun, age-appropriate quizzes help students discover careers that match their interests and talents.</p>
                </div>
            </div>
        </div>
        
        <!-- Portfolio Development -->
        <div class="bg-[#0f5687]/90 rounded-sm p-5">
            <div class="flex items-start">
                <div class="mr-3 text-primary text-2xl">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold montserrat-font">Digital Portfolio</h3>
                    <p class="text-white text-sm body-font">Students build impressive digital portfolios showcasing their projects and skills for future opportunities.</p>
                </div>
            </div>
        </div>
        
        <!-- Parent Workshops -->
        <div class="bg-[#0f5687]/90 rounded-sm p-5">
            <div class="flex items-start">
                <div class="mr-3 text-primary text-2xl">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold montserrat-font">Parent Workshops</h3>
                    <p class="text-white text-sm body-font">We equip parents with knowledge about future careers and how to support their children's interests.</p>
                </div>
            </div>
        </div>
        
        <!-- Learning Pathways -->
        <div class="bg-[#0f5687]/90 rounded-sm p-5">
            <div class="flex items-start">
                <div class="mr-3 text-primary text-2xl">
                    <i class="fas fa-map-signs"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold montserrat-font">Learning Roadmaps</h3>
                    <p class="text-white text-sm body-font">Personalized learning paths that guide students toward their career interests through targeted skill development.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Stories Section -->
<div class="container mx-auto px-4 py-12 relative">
    <!-- Section Overlay -->
    <div class="absolute inset-0 bg-gradient-to-bl from-[#950713]/15 via-transparent to-[#0f5687]/15 rounded-xl -z-10"></div>
    <h2 class="text-3xl font-bold text-white mb-8 montserrat-font text-center"><i class="fas fa-star mr-2"></i>Success Stories</h2>
    <p class="text-white text-center mb-8 body-font bg-[#0f5687]/50 py-3 px-4 rounded-md font-bold inline-block mx-auto">Meet some of our students who discovered their passion through YEG:</p>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-[#0f5687]/80 rounded-sm p-5 text-center">
            <div class="text-white text-3xl mb-4">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h3 class="text-white font-bold montserrat-font mb-2">Kofi, 14</h3>
            <p class="text-white text-sm body-font">"Before YEG, I didn't know what I wanted to do. Now I'm building my own mobile games and planning to become a game developer!"</p>
        </div>
        
        <div class="bg-[#0f5687]/80 rounded-sm p-5 text-center">
            <div class="text-white text-3xl mb-4">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h3 class="text-white font-bold montserrat-font mb-2">Ama, 12</h3>
            <p class="text-white text-sm body-font">"I discovered I love designing websites. My teacher says I have a natural talent for user experience design!"</p>
        </div>
        
        <div class="bg-[#0f5687]/80 rounded-sm p-5 text-center">
            <div class="text-white text-3xl mb-4">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h3 class="text-white font-bold montserrat-font mb-2">Kwame, 15</h3>
            <p class="text-white text-sm body-font">"Through YEG's industry connections, I got to meet real data scientists and now I'm learning advanced math to prepare for my future career!"</p>
        </div>
    </div>
    
    <!-- Call to Action -->
    <div class="text-center">
        <h2 class="text-3xl font-bold text-white mb-5 title-font"><i class="fas fa-rocket mr-2"></i>Launch Your Future</h2>
        <p class="text-white mb-8 body-font bg-[#0f5687]/50 py-3 px-4 rounded-md font-bold inline-block mx-auto">The careers of tomorrow start with the skills we build today. Let YEG help guide your child's journey!</p>
        <a href="{{ url('schools/register') }}" class="inline-block px-8 py-4 bg-primary hover:bg-primary/90 text-white body-font font-medium rounded-md transition-all duration-300">
            BEGIN CAREER EXPLORATION
        </a>
    </div>
</div>

<!-- Padding for bottom spacing -->
<div class="pb-16"></div>
@endsection
