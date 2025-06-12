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
     * @return array
     */
    public function generateHeroStyles(HeroSection $heroSection)
    {
        $styles = [
            'default' => [],
            'title' => [],
            'subtitle' => [],
            'brand_text' => []
        ];
        
        // Default text color
        if ($heroSection->text_color) {
            $styles['default'][] = "color: {$heroSection->text_color}";
        }
        
        // Title color
        if ($heroSection->title_color) {
            $styles['title'][] = "color: {$heroSection->title_color}";
        } else if ($heroSection->text_color) {
            $styles['title'][] = "color: {$heroSection->text_color}";
        }
        
        // Subtitle color
        if ($heroSection->subtitle_color) {
            $styles['subtitle'][] = "color: {$heroSection->subtitle_color}";
        } else if ($heroSection->text_color) {
            $styles['subtitle'][] = "color: {$heroSection->text_color}";
        }
        
        // Brand text color
        if ($heroSection->brand_text_color) {
            $styles['brand_text'][] = "color: {$heroSection->brand_text_color}";
        } else {
            // Default yellow for brand text if not specified
            $styles['brand_text'][] = "color: #ffcb05";
        }
        
        return [
            'default' => implode('; ', $styles['default']),
            'title' => implode('; ', $styles['title']),
            'subtitle' => implode('; ', $styles['subtitle']),
            'brand_text' => implode('; ', $styles['brand_text'])
        ];
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
