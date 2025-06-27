<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\FaqCategory;

class PageController extends Controller
{
    /**
     * Show the about page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        // Get active FAQs with their categories
        $faqCategories = FaqCategory::with(['faqs' => function($query) {
            $query->where('is_active', true)->orderBy('display_order');
        }])->where('is_active', true)
          ->orderBy('display_order')
          ->get();

        // Meta information for SEO
        $metaTitle = 'About Us - Young Experts Group';
        $metaDescription = 'Learn about Young Experts Group, our mission, vision, and how we\'re transforming tech education for young minds in Africa.';
        $metaKeywords = 'about Young Experts, our story, tech education Africa, youth empowerment, digital skills training';

        return view('pages.about', compact('faqCategories', 'metaTitle', 'metaDescription', 'metaKeywords'));
    }
}
