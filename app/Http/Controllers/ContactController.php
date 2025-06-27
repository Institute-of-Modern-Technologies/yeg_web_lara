<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $metaTitle = 'Contact Us - Young Experts Group';
        $metaDescription = 'Get in touch with Young Experts Group. We\'d love to hear from you regarding our programs, partnerships, or any questions you might have.';
        $metaKeywords = 'contact Young Experts, get in touch, partnership, inquiry, support';

        return view('contact', compact('metaTitle', 'metaDescription', 'metaKeywords'));
    }
}
