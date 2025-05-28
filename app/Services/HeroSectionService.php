<?php

namespace App\Services;

use App\Models\HeroSection;

class HeroSectionService
{
    /**
     * Get active hero sections for the homepage
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveHeroSections()
    {
        return HeroSection::active()
            ->ordered()
            ->get();
    }

    /**
     * Generate CSS styles for a hero section
     * 
     * @param \App\Models\HeroSection $heroSection
     * @return string
     */
    public function generateHeroStyles(HeroSection $heroSection)
    {
        $styles = [];
        
        // Text color
        if ($heroSection->text_color) {
            $styles[] = "color: {$heroSection->text_color}";
        }
        
        return implode('; ', $styles);
    }

    /**
     * Generate CSS styles for the hero section overlay
     * 
     * @param \App\Models\HeroSection $heroSection
     * @return string
     */
    public function generateOverlayStyles(HeroSection $heroSection)
    {
        $styles = [];
        
        // Overlay color and opacity
        if ($heroSection->overlay_color) {
            // Convert hex color to rgba for opacity support
            $rgb = $this->hexToRgb($heroSection->overlay_color);
            if ($rgb) {
                $opacity = $heroSection->overlay_opacity ?? 0.5;
                $styles[] = "background-color: rgba({$rgb['r']}, {$rgb['g']}, {$rgb['b']}, {$opacity})";
            } else {
                $styles[] = "background-color: {$heroSection->overlay_color}";
            }
        }
        
        return implode('; ', $styles);
    }

    /**
     * Convert hex color to RGB components
     * 
     * @param string $hex
     * @return array|null
     */
    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        if (strlen($hex) !== 6) {
            return null;
        }
        
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }
}
