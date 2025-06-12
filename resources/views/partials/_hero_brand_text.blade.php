@if(!empty($heroSection->brand_text))
    <span class="inline-block font-semibold text-base px-4 py-1 rounded-full bg-white/10 mb-3 shadow-sm" 
          style="{{ app(\App\Services\HeroSectionService::class)->generateHeroStyles($heroSection)['brand_text'] }}">
        {{ $heroSection->brand_text }}
    </span>
@endif
