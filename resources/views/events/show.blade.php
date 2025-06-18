@extends('layouts.app')

@section('content')
    <style>
        /* Hero section styles */
        .event-hero {
            position: relative;
            height: 70vh;
            min-height: 400px;
            overflow: hidden;
        }
        
        /* Badge styles */
        .badge-modern {
            display: inline-flex;
            align-items: center;
            padding: 0.35em 0.65em;
            font-size: 0.85em;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
        }
        
        .badge-modern:hover {
            transform: scale(1.05);
        }
        
        /* Card animation */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Button animations */
        .btn-animated {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-animated:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }
        
        .btn-animated::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }
        
        .btn-animated:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }
        
        /* Feature card hover effect */
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-3px);
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .feature-icon {
            transition: all 0.3s ease;
        }
        
        /* Social button hover effect */
        .social-btn {
            transition: all 0.3s ease;
        }
        
        .social-btn:hover {
            transform: scale(1.15);
        }
    </style>

    <!-- Event Hero Section -->
    <div class="event-hero">
        <!-- Hero Image Background -->
        <div class="absolute inset-0">
            @if($event->media_type == 'image')
                <img src="{{ asset('storage/' . $event->media_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            @else
                <div class="relative w-full h-full bg-gray-900">
                    <video class="absolute inset-0 w-full h-full object-cover" autoplay loop muted>
                        <source src="{{ asset('storage/' . $event->media_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @endif
            <!-- Overlay for better text visibility -->
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
        </div>
        
        <!-- Hero Content -->
        <div class="container mx-auto px-6 relative h-full flex flex-col justify-end pb-16">
            <div class="max-w-3xl text-white">
                <!-- Event Badges -->
                <div class="flex flex-wrap gap-3 mb-4">
                    @if($event->level)
                        <span class="badge-modern text-xs" style="background-color: {{ $event->level_color }}; color: {{ $event->level_color == '#ffffff' ? '#000000' : '#ffffff' }};">
                            {{ $event->level }}
                        </span>
                    @endif
                    @if($event->duration)
                        <span class="badge-modern bg-blue-500 text-white">
                            <i class="far fa-clock mr-1"></i> {{ $event->duration }}
                        </span>
                    @endif
                    <span class="badge-modern bg-purple-600 text-white">
                        <i class="fas fa-users mr-1"></i> Young Experts Event
                    </span>
                </div>
                
                <!-- Event Title -->
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow-lg">{{ $event->title }}</h1>
                
                <!-- Event Brief -->
                <p class="text-lg mb-6 text-gray-100">Empowering the next generation of innovators and leaders through engaging educational programs.</p>
                
                <!-- Call to Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="#event-content" class="btn-animated bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 text-white font-medium rounded-md px-6 py-3 transition duration-300 shadow-lg flex items-center">
                        <i class="fas fa-info-circle mr-2"></i> Learn More
                    </a>
                    <a href="/students/register" class="btn-animated bg-white text-primary hover:bg-gray-100 font-medium rounded-md px-6 py-3 transition duration-300 shadow-lg flex items-center">
                        <i class="fas fa-user-plus mr-2"></i> Register Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <section id="event-content" class="py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6 lg:p-8">
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">About This Event</h2>
                            
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                            
                            <hr class="my-8 border-gray-200">
                            
                            <!-- Event Features -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                                <!-- Feature 1 -->
                                <div class="feature-card bg-blue-50 border border-blue-100 rounded-lg p-4 flex">
                                    <div class="feature-icon bg-blue-500 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0 mr-4">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 mb-1">Interactive Learning</h3>
                                        <p class="text-sm text-gray-600">Engaging activities that promote active participation and deep learning.</p>
                                    </div>
                                </div>
                                
                                <!-- Feature 2 -->
                                <div class="feature-card bg-purple-50 border border-purple-100 rounded-lg p-4 flex">
                                    <div class="feature-icon bg-purple-500 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0 mr-4">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 mb-1">Collaborative Environment</h3>
                                        <p class="text-sm text-gray-600">Work together with peers on exciting projects and challenges.</p>
                                    </div>
                                </div>
                                
                                <!-- Feature 3 -->
                                <div class="feature-card bg-green-50 border border-green-100 rounded-lg p-4 flex">
                                    <div class="feature-icon bg-green-500 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0 mr-4">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 mb-1">Skill Certification</h3>
                                        <p class="text-sm text-gray-600">Earn certificates recognizing your newly acquired skills and knowledge.</p>
                                    </div>
                                </div>
                                
                                <!-- Feature 4 -->
                                <div class="feature-card bg-orange-50 border border-orange-100 rounded-lg p-4 flex">
                                    <div class="feature-icon bg-orange-500 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0 mr-4">
                                        <i class="fas fa-project-diagram"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 mb-1">Project-Based</h3>
                                        <p class="text-sm text-gray-600">Apply what you learn through real-world projects with practical applications.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- CTA Section -->
                            <div class="bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-lg p-6 mt-10">
                                <div class="flex flex-col md:flex-row items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold mb-2">Ready to join this event?</h3>
                                        <p class="text-gray-100">Secure your spot today and begin your journey with Young Experts Group.</p>
                                    </div>
                                    <a href="/students/register" class="btn-animated mt-4 md:mt-0 bg-white text-primary hover:bg-gray-100 font-medium rounded-md px-6 py-3 transition duration-300 shadow-lg flex items-center">
                                        <i class="fas fa-user-plus mr-2"></i> Register Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Event Info Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="border-b border-gray-200">
                            <h3 class="text-lg font-bold p-4">Event Information</h3>
                        </div>
                        <div class="p-4">
                            <ul class="space-y-4">
                                @if($event->level)
                                <li class="flex items-start">
                                    <div class="bg-gray-100 rounded-full p-2 mr-3 flex-shrink-0">
                                        <i class="fas fa-layer-group text-gray-600"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm text-gray-500">Level</span>
                                        <span class="font-medium" style="color: {{ $event->level_color }};">{{ $event->level }}</span>
                                    </div>
                                </li>
                                @endif
                                
                                @if($event->duration)
                                <li class="flex items-start">
                                    <div class="bg-gray-100 rounded-full p-2 mr-3 flex-shrink-0">
                                        <i class="far fa-clock text-gray-600"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm text-gray-500">Duration</span>
                                        <span class="font-medium">{{ $event->duration }}</span>
                                    </div>
                                </li>
                                @endif
                                
                                <li class="flex items-start">
                                    <div class="bg-gray-100 rounded-full p-2 mr-3 flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-gray-600"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm text-gray-500">Location</span>
                                        <span class="font-medium">Young Experts Campus</span>
                                    </div>
                                </li>
                                
                                <li class="flex items-start">
                                    <div class="bg-gray-100 rounded-full p-2 mr-3 flex-shrink-0">
                                        <i class="fas fa-calendar-alt text-gray-600"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm text-gray-500">Starts</span>
                                        <span class="font-medium">Next intake coming soon</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Event FAQ Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="border-b border-gray-200">
                            <h3 class="text-lg font-bold p-4">Frequently Asked Questions</h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-4">
                                <!-- FAQ Item 1 -->
                                <div class="border-b border-gray-100 pb-3">
                                    <h4 class="font-semibold text-gray-800 mb-1">What should I bring to the event?</h4>
                                    <p class="text-sm text-gray-600">A notebook, pen, and your curiosity! Any additional materials needed will be provided at the event.</p>
                                </div>
                                
                                <!-- FAQ Item 2 -->
                                <div class="border-b border-gray-100 pb-3">
                                    <h4 class="font-semibold text-gray-800 mb-1">Is there a minimum age requirement?</h4>
                                    <p class="text-sm text-gray-600">The event is designed for students aged 8-18, varying by program level.</p>
                                </div>
                                
                                <!-- FAQ Item 3 -->
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-1">Will I receive a certificate?</h4>
                                    <p class="text-sm text-gray-600">Yes! All participants who complete the program will receive a YEG certificate of achievement.</p>
                                </div>
                            </div>
                            
                            <a href="#" class="btn-animated mt-4 inline-block text-sm text-primary hover:text-primary-dark font-medium">
                                <span class="flex items-center">View all questions <i class="fas fa-chevron-right ml-1 text-xs"></i></span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Calendar/Schedule Widget -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="border-b border-gray-200">
                            <h3 class="text-lg font-bold p-4">Upcoming Sessions</h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                <!-- Session 1 -->
                                <div class="flex items-start">
                                    <div class="bg-purple-100 text-purple-600 rounded-md min-w-[45px] h-12 flex flex-col items-center justify-center mr-3">
                                        <span class="text-xs font-medium">JUL</span>
                                        <span class="font-bold">15</span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Orientation Day</h4>
                                        <p class="text-sm text-gray-500">10:00 AM - 12:00 PM</p>
                                    </div>
                                </div>
                                
                                <!-- Session 2 -->
                                <div class="flex items-start">
                                    <div class="bg-blue-100 text-blue-600 rounded-md min-w-[45px] h-12 flex flex-col items-center justify-center mr-3">
                                        <span class="text-xs font-medium">JUL</span>
                                        <span class="font-bold">22</span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">First Workshop</h4>
                                        <p class="text-sm text-gray-500">2:00 PM - 4:30 PM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Related Events Section -->
    @if($relatedEvents->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-10">Similar Events You May Like</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedEvents as $relatedEvent)
                <a href="{{ route('events.show', $relatedEvent->id) }}" class="card-hover bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $relatedEvent->media_path) }}" alt="{{ $relatedEvent->title }}" class="w-full h-48 object-cover">
                        @if($relatedEvent->level)
                        <span class="absolute top-3 right-3 badge-modern text-xs" style="background-color: {{ $relatedEvent->level_color }}; color: {{ $relatedEvent->level_color == '#ffffff' ? '#000000' : '#ffffff' }};">
                            {{ $relatedEvent->level }}
                        </span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $relatedEvent->title }}</h3>
                        <p class="text-gray-600 text-sm mb-3">
                            {{ $relatedEvent->getShortDescription(80) }}
                        </p>
                        <div class="flex justify-between items-center">
                            @if($relatedEvent->duration)
                            <span class="text-xs text-gray-500">{{ $relatedEvent->duration }}</span>
                            @else
                            <span class="text-xs text-gray-500">Coming soon</span>
                            @endif
                            <span class="text-primary flex items-center text-sm font-medium">
                                View Details
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const target = document.querySelector(this.getAttribute('href'));
                
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
@endpush
