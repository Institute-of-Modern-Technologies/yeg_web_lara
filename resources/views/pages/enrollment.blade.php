@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<div class="relative">
    <!-- Hero Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.pexels.com/photos/7948058/pexels-photo-7948058.jpeg" alt="Young students learning technology" class="w-full h-full object-cover object-center brightness-50">
    </div>
    
    <!-- Content Overlay -->
    <div class="relative z-10 bg-gradient-to-r from-[#950713]/90 to-transparent py-32 md:py-40">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl font-bold mb-6 text-white font-montserrat">Enrollment & Rates</h1>
                <p class="text-xl text-white/90 mb-8 max-w-xl">
                    Give your child the advantage of tech education with our age-appropriate, engaging programs designed to build future-ready skills.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('student.registration.step1') }}" class="bg-[#ffcb05] hover:bg-[#ffcb05]/90 text-[#950713] py-3 px-8 rounded-full font-bold transition-all shadow-lg">
                        Enroll Today
                    </a>
                    <a href="#program-rates" class="border-2 border-white text-white py-3 px-8 rounded-full font-bold transition-all hover:bg-white/10">
                        View Rates
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enrollment Section -->
<section class="py-16 bg-gradient-to-r from-blue-50 to-pink-50">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left Column - Image -->
            <div class="relative">
                <div class="bg-[#ffcb05] absolute -top-6 -left-6 w-full h-full rounded-3xl"></div>
                <div class="bg-[#950713] absolute -bottom-6 -right-6 w-full h-full rounded-3xl"></div>
                <img src="https://images.pexels.com/photos/5665500/pexels-photo-5665500.jpeg" alt="Teacher teaching kids technology" class="relative z-10 w-full h-auto rounded-3xl shadow-xl object-cover">
                
                <!-- Floating Elements -->
                <div class="absolute -top-10 -right-10 w-20 h-20 bg-[#FF00FF] rounded-full flex items-center justify-center text-white z-20 shadow-lg animate-bounce-slow">
                    <i class="fas fa-laptop-code text-2xl"></i>
                </div>
                <div class="absolute -bottom-8 -left-8 w-16 h-16 bg-[#ffcb05] rounded-full flex items-center justify-center text-[#950713] z-20 shadow-lg animate-float">
                    <i class="fas fa-rocket text-xl"></i>
                </div>
            </div>
            
            <!-- Right Column - Content -->
            <div>
                <div class="mb-8">
                    <span class="text-[#950713] text-sm font-bold uppercase tracking-wider">Registration Open</span>
                    <h2 class="text-4xl md:text-5xl font-bold mt-2 mb-6 font-montserrat">
                        <span class="text-[#950713]">Enroll</span> 
                        <span class="text-[#FF00FF]">Your Child</span> 
                        <span class="block text-[#ffcb05]">For Success!</span>
                    </h2>
                    <div class="w-20 h-1 bg-[#950713] mb-6"></div>
                    <p class="text-gray-700 text-lg mb-8">
                        Our programs are designed to inspire, educate, and empower young minds with technology skills that will serve them throughout their lives.
                    </p>
                </div>
                
                <!-- Program Rates Cards -->
                <div id="program-rates" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    @foreach($programRates as $program)
                    <div class="bg-white p-5 rounded-xl shadow-md border-l-4 {{ $program['color'] }} hover:shadow-lg transition-all">
                        <h3 class="text-xl font-bold mb-2 font-montserrat">{{ $program['name'] }}</h3>
                        <p class="text-gray-500 text-sm mb-2 font-montserrat">{{ $program['age'] }}</p>
                        <div class="flex justify-between items-center mt-4">
                            @if(is_numeric($program['price']))
                                <span class="text-2xl font-bold text-[#950713]">GH₵{{ $program['price'] }}</span>
                            @else
                                <span class="text-xl font-bold text-[#950713]">{{ $program['price'] }} Pricing</span>
                            @endif
                            <span class="text-sm text-gray-600 font-montserrat">{{ $program['period'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('student.registration.step1') }}" class="bg-[#950713] hover:bg-[#950713]/90 text-white py-3 px-8 rounded-full font-bold transition-all shadow-lg">
                        Enroll Now
                    </a>
                    <a href="{{ route('contact.index') }}" class="bg-[#ffcb05] hover:bg-[#ffcb05]/90 text-[#950713] py-3 px-8 rounded-full font-bold transition-all shadow-lg">
                        Request Information
                    </a>
                </div>
                
                <!-- Note -->
                <p class="mt-6 text-sm text-gray-600 font-montserrat">
                    * Scholarship options available for qualifying students. School partnership discounts available.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Enrollment Process Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-[#950713] font-montserrat">Enrollment Process</h2>
            <div class="w-24 h-1 bg-[#ffcb05] mx-auto my-6"></div>
            <p class="text-gray-700 max-w-3xl mx-auto body-font font-montserrat">
                Getting started with Young Experts Group is simple. Follow these steps to enroll your child in our programs.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="relative">
                <div class="absolute -top-3 -left-3 w-12 h-12 bg-[#950713] rounded-full flex items-center justify-center text-white font-bold text-xl">1</div>
                <div class="bg-gray-50 p-6 pt-10 rounded-lg shadow-md h-full">
                    <h3 class="text-2xl font-bold mb-3 font-montserrat">Choose a Program</h3>
                    <p class="text-gray-600 font-montserrat">
                        Select the program that best suits your child's age, interests, and skill level from our three age categories.
                    </p>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="relative">
                <div class="absolute -top-3 -left-3 w-12 h-12 bg-[#950713] rounded-full flex items-center justify-center text-white font-bold text-xl">2</div>
                <div class="bg-gray-50 p-6 pt-10 rounded-lg shadow-md h-full">
                    <h3 class="text-2xl font-bold mb-3 font-montserrat">Complete Registration</h3>
                    <p class="text-gray-600 font-montserrat">
                        Fill out our registration form with your child's information and select your preferred schedule.
                    </p>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="relative">
                <div class="absolute -top-3 -left-3 w-12 h-12 bg-[#950713] rounded-full flex items-center justify-center text-white font-bold text-xl">3</div>
                <div class="bg-gray-50 p-6 pt-10 rounded-lg shadow-md h-full">
                    <h3 class="text-2xl font-bold mb-3 font-montserrat">Payment</h3>
                    <p class="text-gray-600 font-montserrat">
                        Process the payment for the selected program. Multiple payment options are available for your convenience.
                    </p>
                </div>
            </div>
            
            <!-- Step 4 -->
            <div class="relative">
                <div class="absolute -top-3 -left-3 w-12 h-12 bg-[#950713] rounded-full flex items-center justify-center text-white font-bold text-xl">4</div>
                <div class="bg-gray-50 p-6 pt-10 rounded-lg shadow-md h-full">
                    <h3 class="text-2xl font-bold mb-3 font-montserrat">Begin Learning</h3>
                    <p class="text-gray-600 font-montserrat">
                        Receive confirmation and access details. Your child is now ready to begin their tech learning journey!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQs About Enrollment Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-[#950713] font-montserrat">Frequently Asked Questions</h2>
            <div class="w-24 h-1 bg-[#ffcb05] mx-auto my-6"></div>
        </div>
        
        <div class="max-w-3xl mx-auto">
            <!-- FAQ Items -->
            <div class="space-y-6">
                <!-- Question 1 -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                        <h4 class="text-lg text-gray-800 font-montserrat">How often do classes meet?</h4>
                        <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                        <p class="text-gray-600">
                            Our standard programs meet 2-3 times per week for 1-2 hours each session, depending on the age group and program type. We also offer intensive options and flexible scheduling for busy families.
                        </p>
                    </div>
                </div>
                
                <!-- Question 2 -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                        <h4 class="text-lg text-gray-800 font-montserrat">Are there any additional fees beyond the listed rates?</h4>
                        <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                        <p class="text-gray-600">
                            Our program fees include all standard instruction and digital materials. Some specialized courses might require specific equipment or software which would be communicated prior to enrollment. We strive to be transparent about any additional costs.
                        </p>
                    </div>
                </div>
                
                <!-- Question 3 -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                        <h4 class="text-lg text-gray-800 font-montserrat">How do I apply for scholarships?</h4>
                        <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                        <p class="text-gray-600">
                            Scholarship applications are available during the registration process. You'll need to complete a separate form and provide requested documentation. Our team reviews all applications thoroughly and decisions are typically made within 2-3 weeks.
                        </p>
                    </div>
                </div>
                
                <!-- Question 4 -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                        <h4 class="text-lg text-gray-800 font-montserrat">What is your refund policy?</h4>
                        <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                        <p class="text-gray-600">
                            We offer a 100% refund if canceled within 7 days before the program start date. After that, prorated refunds may be available based on attendance. Each case is reviewed individually to ensure fairness.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enroll CTA Banner -->
<div class="bg-[#950713] py-16 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 font-montserrat">Ready to get started?</h2>
        <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto">
            Enroll your child today and begin their journey to becoming a young tech expert!
        </p>
        <a href="{{ route('student.registration.step1') }}" class="inline-block bg-[#ffcb05] hover:bg-[#ffcb05]/90 text-[#950713] py-4 px-10 rounded-full font-bold text-lg transition-all shadow-lg">
            Enroll Now
        </a>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function toggleFaq(element) {
        // Find the answer panel sibling of the question
        const answerPanel = element.nextElementSibling;
        const iconElement = element.querySelector('.faq-icon i');
        
        // Toggle display
        if (answerPanel.style.display === 'none') {
            answerPanel.style.display = 'block';
            iconElement.classList.remove('fa-chevron-down');
            iconElement.classList.add('fa-chevron-up');
            element.parentElement.classList.add('border-[#950713]');
        } else {
            answerPanel.style.display = 'none';
            iconElement.classList.remove('fa-chevron-up');
            iconElement.classList.add('fa-chevron-down');
            element.parentElement.classList.remove('border-[#950713]');
        }
    }
</script>
@endsection
