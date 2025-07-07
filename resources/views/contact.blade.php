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
                        <form id="whatsappForm">
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Your name" required>
                            </div>
                            
                            <div class="mb-6">
                                <label for="message" class="block text-gray-700 text-sm font-medium mb-2">Your Message</label>
                                <textarea id="message" name="message" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Tell us more about your inquiry..." required></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                                <i class="fab fa-whatsapp mr-2"></i> Send via WhatsApp
                            </button>
                            
                            <!-- Custom WhatsApp Modal -->                            
                            <div id="whatsapp-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                    <!-- Background overlay -->
                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                    </div>
                                    
                                    <!-- Modal panel -->
                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-green-50 px-4 py-3 sm:px-6 border-b border-green-100">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg leading-6 font-medium text-green-800">
                                                    <i class="fab fa-whatsapp mr-2 text-green-600"></i> WhatsApp Message
                                                </h3>
                                                <button id="close-modal" class="text-green-500 hover:text-green-800 focus:outline-none">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <i class="fas fa-comment text-green-600"></i>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                    <h4 class="text-lg leading-6 font-medium text-gray-900">Ready to connect?</h4>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">
                                                            You're about to send a WhatsApp message to the Institute of Modern Technologies Ghana. Your message will be pre-filled as shown below.
                                                        </p>
                                                        <div class="mt-3 p-3 bg-green-50 border border-green-100 rounded-lg">
                                                            <p class="text-sm font-bold text-gray-700" id="preview-name"></p>
                                                            <p class="text-sm text-gray-700 mt-2" id="preview-message"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button id="confirm-whatsapp" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                                                <i class="fab fa-whatsapp mr-2"></i> Open WhatsApp
                                            </button>
                                            <button id="cancel-whatsapp" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.getElementById('whatsappForm');
                                const nameInput = document.getElementById('name');
                                const messageInput = document.getElementById('message');
                                const modal = document.getElementById('whatsapp-modal');
                                const closeModal = document.getElementById('close-modal');
                                const confirmWhatsapp = document.getElementById('confirm-whatsapp');
                                const cancelWhatsapp = document.getElementById('cancel-whatsapp');
                                const previewName = document.getElementById('preview-name');
                                const previewMessage = document.getElementById('preview-message');
                                let whatsappUrl = '';
                                
                                // Show modal
                                function showModal() {
                                    modal.classList.remove('hidden');
                                    document.body.style.overflow = 'hidden';
                                    
                                    // Add fade-in animation
                                    modal.style.opacity = '0';
                                    setTimeout(function() {
                                        modal.style.transition = 'opacity 0.3s ease-out';
                                        modal.style.opacity = '1';
                                    }, 10);
                                }
                                
                                // Hide modal
                                function hideModal() {
                                    modal.style.opacity = '0';
                                    setTimeout(function() {
                                        modal.classList.add('hidden');
                                        document.body.style.overflow = '';
                                    }, 300);
                                }
                                
                                form.addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    
                                    if (!nameInput.value || !messageInput.value) {
                                        alert('Please fill out both your name and message');
                                        return;
                                    }
                                    
                                    // Format the message as requested
                                    const formattedMessage = `My name is ${nameInput.value}\n\n${messageInput.value}`;
                                    
                                    // Set the WhatsApp URL
                                    whatsappUrl = `https://wa.me/233547147313?text=${encodeURIComponent(formattedMessage)}`;
                                    
                                    // Update preview
                                    previewName.textContent = `My name is ${nameInput.value}`;
                                    previewMessage.textContent = messageInput.value;
                                    
                                    // Show modal
                                    showModal();
                                });
                                
                                // Close modal events
                                closeModal.addEventListener('click', hideModal);
                                cancelWhatsapp.addEventListener('click', hideModal);
                                
                                // Confirm and open WhatsApp
                                confirmWhatsapp.addEventListener('click', function() {
                                    window.open(whatsappUrl, '_blank');
                                    hideModal();
                                    
                                    // Reset form
                                    form.reset();
                                });
                                
                                // Close modal when clicking outside
                                window.addEventListener('click', function(e) {
                                    if (e.target === modal) {
                                        hideModal();
                                    }
                                });
                            });
                            </script>
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
