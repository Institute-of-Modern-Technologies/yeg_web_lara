@props(['heroSection'])

<div class="max-w-2xl text-center py-6 px-8">
    
    @if($heroSection->title)
    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-3 leading-tight text-shadow-lg" 
        style="{{ app(\App\Services\HeroSectionService::class)->generateHeroStyles($heroSection)['title'] }}">
        {{ $heroSection->title }}
    </h1>
    @endif
    
    @if($heroSection->subtitle)
    <h2 class="text-2xl md:text-3xl mb-4 font-light text-shadow-md" 
        style="{{ app(\App\Services\HeroSectionService::class)->generateHeroStyles($heroSection)['subtitle'] }}">
        {{ $heroSection->subtitle }}
    </h2>
    @endif
    
    @if($heroSection->button_text)
    <div class="relative inline-block">
        @if($heroSection->button_link)
        {{-- If button_link is provided, make it a regular link button --}}
        <a href="{{ $heroSection->button_link }}" class="bg-gradient-to-r from-[#950713] to-[#bd0a1a] hover:from-[#7c0510] hover:to-[#950713] inline-block text-lg px-6 py-3 rounded-md shadow-lg text-white font-medium flex items-center space-x-2">
            <i class="fas fa-arrow-right"></i>
            <span>{{ $heroSection->button_text }}</span>
        </a>
        @else
        {{-- If no button_link, show the dropdown register button --}}
        <button class="heroRegisterBtn bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 inline-block text-lg px-6 py-3 rounded-md shadow-lg text-white font-medium flex items-center space-x-2">
            <i class="fas fa-user-plus"></i>
            <span>{{ $heroSection->button_text }}</span>
            <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
        </button>
        <div class="heroDropdownMenu absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl overflow-hidden z-50 hidden transform transition-all duration-300 ease-in-out border border-gray-100">
            <div class="py-2">
                <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-700">Register as:</p>
                </div>
                <a href="/students/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                    <i class="fas fa-user-graduate mr-3 text-blue-500"></i>
                    <div>
                        <p class="font-medium">Student</p>
                        <p class="text-xs text-gray-500">Join our learning programs</p>
                    </div>
                </a>
                <a href="/teachers/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                    <i class="fas fa-chalkboard-teacher mr-3 text-green-500"></i>
                    <div>
                        <p class="font-medium">Trainer</p>
                        <p class="text-xs text-gray-500">Become a YEG instructor</p>
                    </div>
                </a>
                <a href="/schools/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                    <i class="fas fa-school mr-3 text-purple-500"></i>
                    <div>
                        <p class="font-medium">School</p>
                        <p class="text-xs text-gray-500">Partner with Young Experts</p>
                    </div>
                </a>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
