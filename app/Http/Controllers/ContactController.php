<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;

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
    
    /**
     * Handle contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
        ]);
        
        try {
            // Send email
            Mail::to('imtghanabranch@gmail.com')
                ->send(new ContactFormMail($validated));
                
            // Log successful submission
            Log::info('Contact form submitted by ' . $validated['email']);
            
            // Flash success message
            return redirect()->route('contact.index')
                ->with('success', 'Thank you for your message! We will get back to you soon.');
                
        } catch (\Exception $e) {
            // Log error
            Log::error('Contact form error: ' . $e->getMessage());
            
            // Flash error message
            return redirect()->route('contact.index')
                ->withInput()
                ->with('error', 'Sorry, there was an issue sending your message. Please try again later.');
        }
    }
}
