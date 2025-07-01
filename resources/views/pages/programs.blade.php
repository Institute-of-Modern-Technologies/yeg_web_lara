@extends('layouts.app')

@section('content')
<!-- Font Import -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&family=Fredoka:wght@400;500;600;700&display=swap');
    
    .title-font {
        font-family: 'Fredoka One', cursive;
    }
    .body-font {
        font-family: 'Fredoka', sans-serif;
    }
    
    /* Program card animations */
    .program-card {
        transition: all 0.3s ease;
    }
    
    .program-card:hover {
        transform: translateY(-10px);
    }
    
    /* Custom graphics */
    .shape-1 {
        position: absolute;
        top: 40px;
        right: 5%;
        width: 80px;
        height: 80px;
        background-color: #ffcb05;
        border-radius: 50%;
        z-index: -1;
        animation: float 6s ease-in-out infinite;
    }
    
    .shape-2 {
        position: absolute;
        bottom: 80px;
        left: 10%;
        width: 60px;
        height: 60px;
        background-color: #FF00FF;
        border-radius: 15px;
        transform: rotate(45deg);
        z-index: -1;
        animation: float 8s ease-in-out infinite 1s;
    }
    
    .shape-3 {
        position: absolute;
        top: 30%;
        left: 8%;
        width: 40px;
        height: 40px;
        border: 4px solid #950713;
        border-radius: 50%;
        z-index: -1;
        animation: float 7s ease-in-out infinite 0.5s;
    }
    
    @keyframes float {
        0% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-20px) rotate(5deg); }
        100% { transform: translateY(0) rotate(0); }
    }
</style>

<!-- Hero Section with Background Image that fills entire section -->
<div class="relative overflow-hidden">
    <!-- Background image covering entire hero section -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.pexels.com/photos/5621952/pexels-photo-5621952.jpeg" alt="Kids with computer and teacher" class="w-full h-full object-cover" />
        <!-- Overlay to ensure text is readable -->
        <div class="absolute inset-0 bg-[#950713]/70"></div>
    </div>
    
    <div class="container mx-auto px-6 py-24 relative z-10">
        <div class="max-w-xl">
            <h1 class="text-5xl md:text-6xl font-bold text-white leading-tight title-font mb-6">
                Our Programs
            </h1>
            <p class="text-white text-xl body-font mb-8">
                Discover our engaging and educational tech programs designed specifically for young minds to explore, create, and innovate!
            </p>
            <a href="{{ route('student.registration.step1') }}" class="bg-[#ffcb05] hover:bg-[#ffcb05]/90 text-[#950713] font-bold py-4 px-8 rounded-full text-lg inline-block transition-all duration-300 shadow-lg body-font">
                Enroll Your Child Today!
            </a>
        </div>
    </div>
    
    <!-- Floating shapes for visual interest -->
    <div class="shape-1"></div>
    <div class="shape-2"></div>
    <div class="shape-3"></div>
</div>

<!-- Welcome Section -->
<div class="py-16 bg-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold mb-3 text-[#950713] title-font">
            Welcome to <span class="text-[#ffcb05]">Young Experts Group</span> Programs
        </h2>
        <div class="w-24 h-1 bg-[#FF00FF] mx-auto my-6"></div>
        <p class="text-gray-700 max-w-3xl mx-auto text-lg body-font">
            We provide innovative learning experiences designed to inspire, challenge, and engage young minds. Our programs blend creativity, technology, and hands-on learning to prepare children for the digital future.
        </p>
    </div>
</div>

<!-- Our Classes/Programs Section -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-[#950713] title-font">Our Classes</h2>
            <div class="w-24 h-1 bg-[#ffcb05] mx-auto my-6"></div>
            <p class="text-gray-700 max-w-3xl mx-auto body-font">
                We provide three main program levels based on age groups and skill development, each tailored to meet the appropriate learning needs of your child.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($programCategories as $program)
            <div class="program-card bg-white rounded-xl overflow-hidden shadow-xl">
                <div class="relative">
                    <img 
                        src="{{ asset($program['image']) }}" 
                        alt="{{ $program['name'] }}" 
                        class="w-full h-64 object-cover object-center"
                    />
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent">
                        <span class="inline-block {{ $program['color'] }} text-white text-sm font-bold py-1 px-3 rounded-full body-font">
                            {{ $program['age'] }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-[#950713] mb-3 title-font">{{ $program['name'] }}</h3>
                    <p class="text-gray-700 mb-4 body-font">{{ $program['description'] }}</p>
                    <a href="{{ route('student.registration.step1') }}" class="inline-block bg-[#950713] hover:bg-[#950713]/90 text-white py-2 px-4 rounded-full text-sm font-medium transition-all duration-300 body-font">
                        Learn More &amp; Register
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- What Children Learn Section -->
<div class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-[#950713] title-font">What Children Learn</h2>
            <div class="w-24 h-1 bg-[#ffcb05] mx-auto my-6"></div>
            <p class="text-gray-700 max-w-3xl mx-auto body-font">
                Our curriculum is designed to build both technical skills and creative problem-solving abilities that prepare young minds for the digital future.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-center">
            <!-- Technology Track -->
            <div class="p-6 bg-blue-50 rounded-xl hover:shadow-lg transition-all duration-300 border border-blue-100">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-laptop-code text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#950713] mb-3 title-font">Technology</h3>
                <ul class="text-left space-y-2 text-gray-700 body-font">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Web Development:</strong> Frontend and backend development</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Programming:</strong> Block-based coding and text-based programming</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>App Development:</strong> Mobile application design and development</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Digital Literacy:</strong> Computing concepts and online safety</span>
                    </li>
                </ul>
            </div>
            
            <!-- Entrepreneurship Track -->
            <div class="p-6 bg-yellow-50 rounded-xl hover:shadow-lg transition-all duration-300 border border-yellow-100">
                <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lightbulb text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#950713] mb-3 title-font">Entrepreneurship</h3>
                <ul class="text-left space-y-2 text-gray-700 body-font">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Problem Solving:</strong> Design thinking and solution development</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Project Management:</strong> Planning, execution, and teamwork</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Business Fundamentals:</strong> Basics of startup development</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Presentation Skills:</strong> Pitching ideas and public speaking</span>
                    </li>
                </ul>
            </div>
            
            <!-- Creativity Track -->
            <div class="p-6 bg-pink-50 rounded-xl hover:shadow-lg transition-all duration-300 border border-pink-100">
                <div class="w-16 h-16 bg-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-palette text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#950713] mb-3 title-font">Creativity</h3>
                <ul class="text-left space-y-2 text-gray-700 body-font">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Digital Design:</strong> Graphics and UI/UX principles</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Content Creation:</strong> Digital storytelling and media</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Innovation:</strong> Creative thinking and ideation techniques</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span><strong>Digital Art:</strong> Introduction to digital tools and mediums</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Additional Skills Badge -->
        <div class="mt-12 text-center">
            <span class="inline-block bg-[#950713] text-white text-lg font-bold py-3 px-6 rounded-full shadow-md">
                All courses include: Critical thinking, Communication, Collaboration, and Digital citizenship
            </span>
        </div>
    </div>
</div>

<!-- Teaching Approach Section -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <img src="https://images.pexels.com/photos/5621949/pexels-photo-5621949.jpeg" alt="Teaching approach with students" class="rounded-lg shadow-xl" />
            </div>
            <div class="md:w-1/2 md:pl-10">
                <h2 class="text-4xl font-bold text-[#950713] mb-6 title-font">Our Teaching Approach</h2>
                <div class="w-24 h-1 bg-[#ffcb05] mb-6"></div>
                
                <div class="mb-6">
                    <div class="flex items-start">
                        <div class="bg-[#950713] p-2 rounded-full mr-4 mt-1">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-[#950713] mb-2 title-font">Hands-on Learning</h3>
                            <p class="text-gray-700 body-font">Children learn by doing, creating real projects they can be proud of.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-start">
                        <div class="bg-[#950713] p-2 rounded-full mr-4 mt-1">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-[#950713] mb-2 title-font">Small Class Sizes</h3>
                            <p class="text-gray-700 body-font">Personalized attention ensures each child gets the guidance they need.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-start">
                        <div class="bg-[#950713] p-2 rounded-full mr-4 mt-1">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-[#950713] mb-2 title-font">Expert Instructors</h3>
                            <p class="text-gray-700 body-font">Our teachers are passionate about technology and working with children.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="py-16 bg-[#950713]">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-white mb-6 title-font">Ready to Enroll Your Child?</h2>
        <p class="text-white text-xl max-w-3xl mx-auto mb-8 body-font">
            Give your child the gift of future-ready skills in a fun, engaging environment.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('student.registration.step1') }}" class="bg-[#ffcb05] hover:bg-[#ffcb05]/90 text-[#950713] font-bold py-4 px-8 rounded-full text-lg inline-block transition-all duration-300 shadow-lg body-font">
                Register Now
            </a>
            <a href="#" class="bg-transparent hover:bg-white/10 text-white border-2 border-white font-bold py-4 px-8 rounded-full text-lg inline-block transition-all duration-300 body-font">
                Learn More
            </a>
        </div>
    </div>
</div>

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
@endsection
