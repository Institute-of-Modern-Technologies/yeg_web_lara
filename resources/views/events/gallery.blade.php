@extends('layouts.app')

@section('title', 'Event Gallery | Young Experts Group')

@section('meta_description', 'Explore our collection of events and workshops designed to build practical tech skills for students of all ages.')

@section('content')
<div class="event-gallery-container">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <!-- Online Background Image -->
            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Tech Events" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black opacity-60"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
        </div>
        <div class="container mx-auto px-6 py-20 relative z-10 flex flex-col items-center justify-center min-h-[40vh]">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 text-center">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-800 to-red-700" style="color: #950713;">
                    Event Gallery
                </span>
            </h1>
            <p class="text-lg text-gray-200 max-w-2xl text-center mb-8">
                Discover our full range of workshops, courses, and tech events designed to inspire the next generation of innovators.
            </p>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-gray-100 py-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <!-- Search -->
                <div class="relative w-full md:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input id="searchInput" type="text" class="bg-white w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500" placeholder="Search events...">
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <button class="filter-btn active bg-white border border-gray-200 hover:border-red-800 px-4 py-2 rounded-md transition-all duration-200" data-filter="all">All Levels</button>
                    <button class="filter-btn bg-white border border-gray-200 hover:border-red-800 px-4 py-2 rounded-md transition-all duration-200" data-filter="beginner">Beginner</button>
                    <button class="filter-btn bg-white border border-gray-200 hover:border-red-800 px-4 py-2 rounded-md transition-all duration-200" data-filter="intermediate">Intermediate</button>
                    <button class="filter-btn bg-white border border-gray-200 hover:border-red-800 px-4 py-2 rounded-md transition-all duration-200" data-filter="advanced">Advanced</button>
                </div>
            </div>
            
            <!-- Results Counter -->
            <div class="mt-4 text-right text-gray-600 font-medium">
                <span id="resultCount">{{ count($events) }} Events</span>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="bg-white py-16">
        <div class="container mx-auto px-6">
            <div id="searchStatus" class="text-center py-10 hidden">
                <p class="text-gray-500 text-lg">No events found matching your search criteria.</p>
                <button id="clearFilters" class="mt-4 px-6 py-2 text-white rounded-md transition-colors duration-300" style="background-color: #950713; hover:background-color: #7d0410;">Clear Filters</button>
            </div>
            
            <div id="eventGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($events as $event)
                    <div class="event-card hologram-card hover-lift rounded-lg overflow-hidden shadow-lg border border-gray-100 animate-fade-in" 
                         data-level="{{ strtolower($event->level) }}"
                         data-active="{{ $event->is_active ? '1' : '0' }}">
                        
                        <!-- Card Header with Image or Video -->
                        <div class="relative aspect-[16/9] bg-gray-100 overflow-hidden">
                            @if($event->isVideo())
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fas fa-play-circle text-white text-5xl opacity-80 hover:opacity-100 transition-opacity"></i>
                                </div>
                                <x-image class="gallery-item w-full h-full object-cover rounded-md cursor-pointer hover:scale-[1.02] transition-all duration-300 ease-out shadow-sm hover:shadow-md" 
                                 src="storage/{{ $event->media_path }}" 
                                 alt="{{ $event->title }} - Photo {{ $loop->index + 1 }}" 
                                 :attributes="['data-src' => app(\App\Services\ImagePathService::class)->resolveImagePath('storage/' . $event->media_path)]" />
                            @else
                                <x-image class="gallery-item w-full h-full object-cover rounded-md cursor-pointer hover:scale-[1.02] transition-all duration-300 ease-out shadow-sm hover:shadow-md" 
                                 src="storage/{{ $event->media_path }}" 
                                 alt="{{ $event->title }} - Photo {{ $loop->index + 1 }}" 
                                 :attributes="['data-src' => app(\App\Services\ImagePathService::class)->resolveImagePath('storage/' . $event->media_path)]" />
                            @endif
                            
                            <!-- Level Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="hologram-data-pill inline-block py-1 px-3 rounded-full text-xs font-medium uppercase tracking-wider" 
                                      style="background-color: {{ $event->level_color ?? '#950713' }}; color: white;">
                                    {{ $event->level }}
                                </span>
                            </div>
                            
                            @if(!$event->is_active)
                                <div class="absolute top-4 left-4">
                                    <span class="inline-block py-1 px-3 bg-gray-800 text-white rounded-full text-xs font-medium">Inactive</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-6">
                            <h3 class="hologram-title text-xl font-bold mb-2 text-gray-900 hover:text-pink-500 transition-colors">{{ $event->title }}</h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $event->getShortDescription(150) }}</p>
                            
                            <!-- Event Details -->
                            <div class="flex items-center text-gray-500 mb-4 text-sm">
                                @if($event->duration)
                                <div class="flex items-center mr-4">
                                    <i class="far fa-clock mr-2"></i>
                                    <span>{{ $event->duration }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Action Button -->
                            <a href="{{ route('events.public.show', $event->id) }}" class="block w-full text-center py-2 px-4 text-white rounded-md transition-all duration-300 mt-4" style="background-color: #950713; hover:background-color: #7d0410;">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20">
                        <p class="text-2xl font-light text-gray-500">No events found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Animation */
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Hover Animation */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Filter Button Styles */
    .filter-btn.active {
        background-color: #950713;
        color: white;
        border-color: #950713;
    }
    
    /* Line Clamp */
    .line-clamp-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const eventCards = document.querySelectorAll('.event-card');
    const searchStatus = document.getElementById('searchStatus');
    const resultCount = document.getElementById('resultCount');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    // Add animation delay to cards for staggered appearance
    eventCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 50}ms`;
    });
    
    // Filter functionality
    function filterEvents() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter').toLowerCase();
        let visibleCount = 0;
        let totalCount = eventCards.length;
        let animationDelay = 0;

        eventCards.forEach((card, index) => {
            const eventTitle = card.querySelector('.hologram-title').textContent.toLowerCase();
            const eventLevel = card.querySelector('.hologram-data-pill').textContent.toLowerCase();
            
            const matchesSearch = eventTitle.includes(searchTerm);
            const matchesFilter = activeFilter === 'all' || eventLevel.includes(activeFilter);

            if (matchesSearch && matchesFilter) {
                card.classList.remove('hidden');
                visibleCount++;
                
                // Reset and add animation with staggered delay
                card.style.animation = 'none';
                card.offsetHeight; // Trigger reflow
                card.style.animation = `fadeIn 0.6s ease-out forwards ${animationDelay}ms`;
                animationDelay += 50;
            } else {
                card.classList.add('hidden');
            }
        });

        resultCount.textContent = `${visibleCount} Events`;
        
        if (visibleCount === 0) {
            searchStatus.classList.remove('hidden');
        } else {
            searchStatus.classList.add('hidden');
        }
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterEvents);
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterEvents();
        });
    });
    
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        filterBtns.forEach(b => b.classList.remove('active'));
        document.querySelector('[data-filter="all"]').classList.add('active');
        filterEvents();
    });
});
</script>
@endsection
