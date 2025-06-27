@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gray-900 text-white">
        <div class="absolute inset-0">
            <img src="{{ asset('images/contact-hero.jpg') }}" alt="Contact Us" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-primary/80 mix-blend-multiply"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Get In Touch</h1>
                <p class="text-xl md:text-2xl opacity-90">We'd love to hear from you. Reach out to us with any questions or inquiries.</p>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Contact Form -->
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a message</h2>
                        <form>
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
                                <input type="text" id="name" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Your name">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                                <input type="email" id="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="your@email.com">
                            </div>
                            <div class="mb-4">
                                <label for="subject" class="block text-gray-700 text-sm font-medium mb-2">Subject</label>
                                <input type="text" id="subject" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="How can we help?">
                            </div>
                            <div class="mb-6">
                                <label for="message" class="block text-gray-700 text-sm font-medium mb-2">Your Message</label>
                                <textarea id="message" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Tell us more about your inquiry..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition duration-300">
                                Send Message
                            </button>
                        </form>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="bg-gray-50 p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h2>
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-primary/10 p-3 rounded-lg text-primary">
                                    <i class="fas fa-map-marker-alt text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Our Locations</h3>
                                    <p class="text-gray-600">No. 8 Borstal Street, Roman Ridge Accra</p>
                                    <p class="text-gray-600">P.O.Box 4754 Accra</p>
                                    <p class="text-gray-600 mt-2">Lapaz Nii Boi Accra</p>
                                    <p class="text-gray-600">P.O.Box 4754 Accra</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-primary/10 p-3 rounded-lg text-primary">
                                    <i class="fas fa-phone-alt text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Phone Number</h3>
                                    <p class="text-gray-600">+233 547147313</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-primary/10 p-3 rounded-lg text-primary">
                                    <i class="fas fa-envelope text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Email Address</h3>
                                    <p class="text-gray-600">yeg@imtghana.com</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-primary/10 p-3 rounded-lg text-primary">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Working Hours</h3>
                                    <p class="text-gray-600">Monday - Friday: 9:00 AM - 5:00 PM</p>
                                    <p class="text-gray-600">Saturday: 10:00 AM - 2:00 PM</p>
                                    <p class="text-gray-600">Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h3 class="font-semibold text-gray-900 mb-4">Follow Us</h3>
                            <div class="flex space-x-4">
                                <a href="mailto:yeg@imtghana.com" class="w-10 h-10 rounded-full bg-primary/10 hover:bg-primary/20 text-primary flex items-center justify-center transition duration-300">
                                    <i class="far fa-envelope"></i>
                                </a>
                                <a href="https://www.facebook.com/youngexpertsgroup" target="_blank" class="w-10 h-10 rounded-full bg-primary/10 hover:bg-primary/20 text-primary flex items-center justify-center transition duration-300">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://www.youtube.com/shorts/x_kUqKoTZR8" target="_blank" class="w-10 h-10 rounded-full bg-primary/10 hover:bg-primary/20 text-primary flex items-center justify-center transition duration-300">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="https://www.tiktok.com/@youngexpertsgroup" target="_blank" class="w-10 h-10 rounded-full bg-primary/10 hover:bg-primary/20 text-primary flex items-center justify-center transition duration-300">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                                <a href="https://www.instagram.com/youngexpertsgroup" target="_blank" class="w-10 h-10 rounded-full bg-primary/10 hover:bg-primary/20 text-primary flex items-center justify-center transition duration-300">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto overflow-hidden rounded-xl shadow-lg">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.162197716458!2d-0.1889189251320831!3d5.58545269442219!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf9a7c2d286d9f%3A0x8a1f4a9cf9f3d5b9!2s8%20Borstal%20Ave%2C%20Accra!5e0!3m2!1sen!2sgh!4v1620000000000!5m2!1sen!2sgh" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy"
                    class="w-full h-96">
                </iframe>
            </div>
        </div>
    </section>
@endsection
