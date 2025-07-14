@extends('layouts.app')

@section('content')
<!-- Font Import -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&family=Fredoka:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap');
    
    /* Force Montserrat font on every element in the about page */
    #about-page *:not(.fas):not(.far):not(.fa):not(.fab) {
        font-family: 'Montserrat', sans-serif !important;
    }
    
    .title-font {
        font-family: 'Montserrat', sans-serif !important;
    }
    .body-font {
        font-family: 'Montserrat', sans-serif !important;
    }
    .montserrat-font {
        font-family: 'Montserrat', sans-serif;
    }
</style>
<div id="about-page" class="font-montserrat">
<!-- Full Page Background with Image -->
<div class="fixed inset-0 -z-10">
    <img src="{{ asset('images/image.png') }}" alt="Background" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/60"></div>
    <!-- Modern animated gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-tr from-[#950713]/10 via-transparent to-[#0f5687]/20 opacity-75 animate-pulse"></div>
</div>

<!-- Header Section -->
<div class="relative">
    <div class="container mx-auto px-6 pt-16 pb-32">
        <div class="text-left max-w-3xl">
            <h1 class="text-white leading-tight montserrat-font font-bold">
                <span class="block text-5xl md:text-6xl">Young Experts</span>
                <span class="block text-4xl md:text-5xl mt-1">Group</span>
            </h1>
            <p class="text-white text-lg md:text-xl mt-4 mb-6 max-w-xl body-font">
                Empowering Tomorrow's Digital Leaders Today
            </p>
            <a href="{{ url('schools/register') }}" class="inline-block px-8 py-3 bg-primary hover:bg-primary/90 text-white body-font font-medium rounded-md transition-all duration-300">
                JOIN THE MOVEMENT
            </a>
        </div>
    </div>
</div>

<!-- Information Cards -->
<div class="container mx-auto px-4 pb-24 relative">
    <!-- Section Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#0f5687]/10 to-transparent rounded-xl -z-10"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- About Us Card -->
        <div class="bg-[#0f5687] rounded-sm p-6">
            <h2 class="text-2xl font-bold text-white mb-5 montserrat-font"><i class="fas fa-users mr-2"></i>About Us</h2>
            <p class="text-white text-sm mb-4 body-font">
                The Young Experts Group (YEG) is more than just a tech club — it's a movement. We are a hands-on, practical program designed to help children and teens unlock their creative potential, master 21st-century digital skills, and step confidently into the future.
            </p>
            <p class="text-white text-sm body-font">
                Rooted in innovation, creativity, and problem-solving, YEG empowers young minds to become the next generation of digital creators, thinkers, and leaders — starting right from the classroom.
            </p>
        </div>
        
        <!-- Our Mission & Vision Card -->
        <div class="bg-[#0f5687] rounded-sm p-6">
            <h2 class="text-2xl font-bold text-white mb-5 montserrat-font"><i class="fas fa-compass mr-2"></i>Our Mission & Vision</h2>
            <div class="flex items-start mb-4">
                <div class="mr-3 mt-1 text-sky-300">
                    <i class="fas fa-rocket"></i>
                </div>
                <p class="text-white text-sm body-font"><strong>Our Mission:</strong> To equip young people with future-ready skills in technology, entrepreneurship, and creative expression, enabling them to lead change and create impact.</p>
            </div>
            <div class="flex items-start">
                <div class="mr-3 mt-1 text-sky-300">
                    <i class="fas fa-globe"></i>
                </div>
                <p class="text-white text-sm body-font"><strong>Our Vision:</strong> To raise a generation of innovators, problem-solvers, and ethical digital leaders who will shape the future of Ghana — and the world.</p>
            </div>
        </div>
        
        <!-- Who Can Join Card -->
        <div class="bg-[#0f5687] rounded-sm p-6">
            <h2 class="text-2xl font-bold text-white mb-5 montserrat-font"><i class="fas fa-child mr-2"></i>Who Can Join</h2>
            <p class="text-white text-sm mb-4 body-font">
                YEG is open to students ages 6–16 through the following pathways:
            </p>
            <div class="grid grid-cols-1 gap-2 mb-5">
                <div class="text-white text-sm body-font">• After-School & Weekend Programs</div>
                <div class="text-white text-sm body-font">• School Club Partnerships</div>
                <div class="text-white text-sm body-font">• Holiday Camps & Bootcamps</div>
                <div class="text-white text-sm body-font">• Instructor-Led In-School Programs</div>
            </div>
            <a href="{{ url('schools/register') }}" class="inline-block w-full bg-primary hover:bg-primary/90 text-white text-center py-2 font-medium rounded-sm transition-all duration-300 body-font">
                JOIN YEG TODAY
            </a>
        </div>
    </div>
</div>

<!-- YEG Program Structure Section -->
<div class="container mx-auto px-4 py-12 relative">
    <!-- Section Overlay -->
    <div class="absolute inset-0 bg-gradient-to-l from-[#950713]/10 to-transparent rounded-xl -z-10"></div>
    <h2 class="text-3xl font-bold text-white mb-8 montserrat-font text-center"><i class="fas fa-tools mr-2"></i>How YEG Works</h2>
    <p class="text-white text-center mb-8 body-font bg-[#0f5687]/50 py-3 px-4 rounded-md font-bold inline-block mx-auto">Our program operates across schools, learning centers, and bootcamps, structured into three progressive phases:</p>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <!-- Phase 1 -->
        <div class="bg-[#0f5687] rounded-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16">
                <div class="bg-primary text-white absolute transform rotate-45 text-center text-sm font-bold py-1 right-[-35px] top-[20px] w-[120px]">Phase 1</div>
            </div>
            <h3 class="text-xl font-bold text-white mb-3 title-font">Discover</h3>
            <p class="text-white text-sm body-font">Explore the world of technology through exciting, hands-on experiences.</p>
        </div>
        
        <!-- Phase 2 -->
        <div class="bg-[#0f5687] rounded-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16">
                <div class="bg-primary text-white absolute transform rotate-45 text-center text-sm font-bold py-1 right-[-35px] top-[20px] w-[120px]">Phase 2</div>
            </div>
            <h3 class="text-xl font-bold text-white mb-3 title-font">Build</h3>
            <p class="text-white text-sm body-font">Dive deeper: code apps, design websites, and develop creative projects.</p>
        </div>
        
        <!-- Phase 3 -->
        <div class="bg-[#0f5687] rounded-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16">
                <div class="bg-primary text-white absolute transform rotate-45 text-center text-sm font-bold py-1 right-[-35px] top-[20px] w-[120px]">Phase 3</div>
            </div>
            <h3 class="text-xl font-bold text-white mb-3 title-font">Mastery</h3>
            <p class="text-white text-sm body-font">Apply skills in real-world simulations, complete capstone projects, and earn industry-relevant certifications.</p>
        </div>
    </div>
    
    <!-- What We Teach Section -->
    <h2 class="text-3xl font-bold text-white mb-8 montserrat-font text-center"><i class="fas fa-lightbulb mr-2"></i>What We Teach</h2>
    <p class="text-white text-center mb-8 body-font bg-[#0f5687]/50 py-3 px-4 rounded-md font-bold inline-block mx-auto">YEG students gain expertise in a wide range of 21st-century fields, including:</p>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-16">
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">Graphic Design & Branding</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">Coding & App Development</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">3D Modeling & Animation</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">Video Creation & Editing</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">AI & Prompt Engineering</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">Web Design</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">Cloud Computing</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">Digital Marketing</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">Entrepreneurship & Publishing</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">Public Speaking & Presentation</p>
        </div>
        <div class="bg-[#0f5687]/80 rounded-sm p-4 text-center">
            <p class="text-white body-font text-sm">Data Analysis</p>
        </div>
    </div>
</div>

<!-- What Makes YEG Different Section -->
<div class="container mx-auto px-4 py-12 relative">
    <!-- Section Overlay -->
    <div class="absolute inset-0 bg-gradient-to-tr from-[#0f5687]/15 via-transparent to-[#950713]/10 rounded-xl -z-10"></div>
    <h2 class="text-3xl font-bold text-white mb-8 montserrat-font text-center"><i class="fas fa-star mr-2"></i>What Makes YEG Different?</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
        <div class="bg-[#0f5687]/90 rounded-sm p-5">
            <div class="flex items-start">
                <div class="mr-3 text-primary">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold montserrat-font">Project-Based Learning</h3>
                    <p class="text-white text-sm body-font">Every student learns by doing, not just listening.</p>
                </div>
            </div>
        </div>
        
        <div class="bg-[#0f5687]/90 rounded-sm p-5">
            <div class="flex items-start">
                <div class="mr-3 text-primary">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold montserrat-font">Career-Driven Exposure</h3>
                    <p class="text-white text-sm body-font">Beyond coding, we teach problem-solving and future-thinking.</p>
                </div>
            </div>
        </div>
        
        <div class="bg-[#0f5687]/90 rounded-sm p-5">
            <div class="flex items-start">
                <div class="mr-3 text-primary">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold montserrat-font">AI + Creativity</h3>
                    <p class="text-white text-sm body-font">From book creation to ad design, we integrate AI tools in every module.</p>
                </div>
            </div>
        </div>
        
        <div class="bg-[#0f5687]/90 rounded-sm p-5">
            <div class="flex items-start">
                <div class="mr-3 text-primary">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold montserrat-font">Montessori-Inspired</h3>
                    <p class="text-white text-sm body-font">A student-centered approach that supports learning at each child's pace.</p>
                </div>
            </div>
        </div>
        
        <div class="bg-[#0f5687]/90 rounded-sm p-5">
            <div class="flex items-start">
                <div class="mr-3 text-primary">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold montserrat-font">Workplace Simulation</h3>
                    <p class="text-white text-sm body-font">Our learning spaces mimic office environments, building confidence and professionalism early on.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Call to Action -->
    <div class="text-center">
        <h2 class="text-3xl font-bold text-white mb-5 font-montserrat"><i class="fas fa-comments mr-2"></i>Join the Movement</h2>
        <p class="text-white mb-8 body-font bg-[#0f5687]/50 py-3 px-4 rounded-md font-bold inline-block mx-auto">Whether you're a parent, educator, or school administrator — YEG is your partner in building Ghana's future-ready youth. Let's shape the next generation together.</p>
        <a href="{{ url('schools/register') }}" class="inline-block px-8 py-4 bg-primary hover:bg-primary/90 text-white body-font font-medium rounded-md transition-all duration-300">
            ENROLL TODAY
        </a>
    </div>
</div>

<!-- Certifications & Recognition Section -->
<div class="container mx-auto px-4 py-12 relative">
    <!-- Section Overlay -->
    <div class="absolute inset-0 bg-gradient-to-bl from-[#950713]/15 via-transparent to-[#0f5687]/15 rounded-xl -z-10"></div>
    <h2 class="text-3xl font-bold text-white mb-8 montserrat-font text-center"><i class="fas fa-medal mr-2"></i>Certifications & Recognition</h2>
    <p class="text-white text-center mb-8 body-font bg-[#0f5687]/50 py-3 px-4 rounded-md font-bold inline-block mx-auto">Each YEG phase ends with:</p>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-[#0f5687]/80 rounded-sm p-5 text-center">
            <div class="text-white text-3xl mb-4">
                <i class="fas fa-certificate"></i>
            </div>
            <h3 class="text-white font-bold montserrat-font mb-2">Official Certificates</h3>
            <p class="text-white text-sm body-font">Recognized achievements at the end of each program phase</p>
        </div>
        
        <div class="bg-[#0f5687]/80 rounded-sm p-5 text-center">
            <div class="text-white text-3xl mb-4">
                <i class="fas fa-comments"></i>
            </div>
            <h3 class="text-white font-bold montserrat-font mb-2">Project Feedback</h3>
            <p class="text-white text-sm body-font">Detailed guidance and assessment on student work</p>
        </div>
        
        <div class="bg-[#0f5687]/80 rounded-sm p-5 text-center">
            <div class="text-white text-3xl mb-4">
                <i class="fas fa-laptop-code"></i>
            </div>
            <h3 class="text-white font-bold montserrat-font mb-2">Digital Portfolios</h3>
            <p class="text-white text-sm body-font">Comprehensive showcase of each student's projects and skills</p>
        </div>
        
        <div class="bg-[#0f5687]/80 rounded-sm p-5 text-center">
            <div class="text-white text-3xl mb-4">
                <i class="fas fa-award"></i>
            </div>
            <h3 class="text-white font-bold montserrat-font mb-2">Public Exhibitions</h3>
            <p class="text-white text-sm body-font">Opportunities to showcase work at school and community events</p>
        </div>
    </div>
</div>

<!-- Padding for bottom spacing -->
<div class="pb-16"></div>


<!-- Animation styles -->
<style>
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
</div>

@endsection
