// Function to open receipt modal
function openReceiptModal(paymentId) {
    const receiptModal = document.getElementById('receiptModal');
    const receiptContent = document.getElementById('receipt-content');
    let studentPhone = '';
    let studentEmail = '';
    
    // Show modal
    receiptModal.classList.remove('hidden');
    
    // Store the payment ID on the modal for reference
    receiptModal.setAttribute('data-payment-id', paymentId);
    
    // Load receipt content via AJAX
    fetch(`/admin/payments/receipt/${paymentId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            receiptContent.innerHTML = html;
            
            // Extract student contact info if available
            const phoneElement = receiptContent.querySelector('[data-student-phone]');
            const emailElement = receiptContent.querySelector('[data-student-email]');
            
            if (phoneElement) studentPhone = phoneElement.getAttribute('data-student-phone');
            if (emailElement) studentEmail = emailElement.getAttribute('data-student-email');
            
            // Configure buttons with student contact info
            configureShareButtons(paymentId, studentPhone, studentEmail);
        })
        .catch(error => {
            console.error('Error loading receipt:', error);
            receiptContent.innerHTML = '<div class="p-4 text-center text-red-600">Error loading receipt. Please try again.</div>';
        });
}

// Configure share buttons
function configureShareButtons(paymentId, phone, email) {
    const whatsappBtn = document.getElementById('whatsapp-receipt');
    const emailBtn = document.getElementById('email-receipt');
    const printBtn = document.getElementById('print-receipt');
    
    if (!whatsappBtn || !emailBtn || !printBtn) {
        console.error('Receipt action buttons not found');
        return;
    }
    
    // Remove any existing event listeners
    whatsappBtn.replaceWith(whatsappBtn.cloneNode(true));
    emailBtn.replaceWith(emailBtn.cloneNode(true));
    printBtn.replaceWith(printBtn.cloneNode(true));
    
    // Get fresh references
    const newWhatsappBtn = document.getElementById('whatsapp-receipt');
    const newEmailBtn = document.getElementById('email-receipt');
    const newPrintBtn = document.getElementById('print-receipt');
    
    // Print button handler
    newPrintBtn.addEventListener('click', function() {
        const printContents = document.getElementById('receipt-content').innerHTML;
        const originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <div class="print-container">
                <style>
                    @media print {
                        body { margin: 0; padding: 20px; }
                        .print-container { max-width: 800px; margin: 0 auto; }
                    }
                </style>
                ${printContents}
            </div>`;
        
        window.print();
        
        // Restore original content and reopen modal
        document.body.innerHTML = originalContents;
        
        // Reattach event listeners after printing
        setTimeout(() => {
            openReceiptModal(paymentId);
        }, 500);
    });
    
    // WhatsApp button handler - DISABLED to prevent conflicts
    // The custom WhatsApp functionality is now handled in the student show page
    newWhatsappBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Default WhatsApp handler disabled - using custom implementation');
        return false;
    });
    
    // Email button handler
    newEmailBtn.addEventListener('click', function() {
        // If we have an email, use it; otherwise ask for email
        if (email) {
            sendEmailReceipt(paymentId, email);
        } else {
            Swal.fire({
                title: 'Enter Email Address',
                input: 'email',
                inputLabel: 'Email address',
                inputPlaceholder: 'example@example.com',
                showCancelButton: true,
                confirmButtonText: 'Send',
                confirmButtonColor: '#950713', // Primary color
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Please enter an email address';
                    }
                }
            }).then(result => {
                if (result.isConfirmed) {
                    sendEmailReceipt(paymentId, result.value);
                }
            });
        }
    });
}

// Function to send receipt via WhatsApp
function sendWhatsAppReceipt(paymentId, phone) {
    // Get student ID from the current page URL or data attribute
    const studentId = getStudentIdFromPage();
    
    if (!studentId) {
        console.error('Student ID not found');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Unable to determine student information. Please try again.'
        });
        return;
    }
    
    // Get bill information for this student
    fetch(`/admin/billing/get-bill-info/${studentId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Use parent contact if available, otherwise use provided phone
            const phoneToUse = data.parent_contact || phone || data.phone || '';
            
            // Show WhatsApp modal with pre-filled information
            Swal.fire({
                title: 'Send Receipt via WhatsApp',
                html: `
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-700 mb-2">${data.has_parent_contact ? 'Parent Contact Number:' : 'Phone Number:'}</label>
                        <input type="text" id="whatsapp-phone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md mb-4" 
                               placeholder="Enter phone number">
                        <p class="mb-4 text-sm text-gray-700">✅ Payment receipt will be sent as PDF</p>
                        <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Message:</label>
                        <textarea id="whatsapp-message" rows="8" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                  placeholder="Enter WhatsApp message">${data.whatsapp_message}</textarea>
                    </div>
                `,
                width: 600,
                showCancelButton: true,
                confirmButtonText: '<i class="fab fa-whatsapp mr-2"></i> Send via WhatsApp',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#25D366',
                didOpen: () => {
                    // Set the phone value after modal opens
                    setTimeout(() => {
                        const phoneInput = document.getElementById('whatsapp-phone');
                        if (phoneInput) {
                            phoneInput.value = phoneToUse;
                        }
                    }, 100);
                },
                preConfirm: () => {
                    const phone = document.getElementById('whatsapp-phone').value.trim();
                    const message = document.getElementById('whatsapp-message').value.trim();
                    
                    if (!phone) {
                        Swal.showValidationMessage('Please enter a phone number');
                        return false;
                    }
                    
                    if (!message) {
                        Swal.showValidationMessage('Please enter a message');
                        return false;
                    }
                    
                    return { phone, message };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { phone, message } = result.value;
                    
                    // Format phone number
                    let formattedPhone = phone.replace(/[^0-9]/g, '');
                    if (!formattedPhone.startsWith('233') && formattedPhone.length === 10) {
                        formattedPhone = '233' + formattedPhone.substring(1);
                    }
                    
                    // Open PDF receipt and WhatsApp
                    if (data.has_pdf_receipt && data.receipt_url) {
                        // Show loading
                        Swal.fire({
                            title: 'Preparing PDF Receipt',
                            text: 'Please wait while we prepare your PDF receipt...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        
                        // Open PDF receipt in new tab
                        window.open(data.receipt_url, '_blank');
                        
                        // Then open WhatsApp
                        setTimeout(() => {
                            const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(message)}`;
                            window.open(whatsappUrl, '_blank');
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Receipt PDF Generated!',
                                html: `<div class="text-left">
                                    <p>1. The PDF receipt has been opened in a new tab</p>
                                    <p>2. WhatsApp has been opened with your message</p>
                                    <p>3. <strong>To send the PDF:</strong> Download it from the PDF tab, then attach it in WhatsApp</p>
                                </div>`,
                                confirmButtonText: 'OK'
                            });
                        }, 1500);
                    } else {
                        // No PDF available, just open WhatsApp
                        const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(message)}`;
                        window.open(whatsappUrl, '_blank');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'WhatsApp Opened!',
                            text: 'WhatsApp has been opened with your message.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to load student information.'
            });
        }
    })
    .catch(error => {
        console.error('Error loading student info:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load student information. Please try again.'
        });
    });
}

// Function to send receipt via Email
function sendEmailReceipt(paymentId, email) {
    // Create Gmail compose URL
    const subject = 'Your Payment Receipt from IMT';
    const body = `Your payment receipt from the Institute of Modern Technologies is ready.\n\nView your receipt here: ${window.location.origin}/admin/payments/receipt/${paymentId}\n\nThank you for your payment.\n\nRegards,\nInstitute of Modern Technologies\nGhana Branch`;
    const gmailURL = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(email)}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    
    // Open Gmail in new window
    window.open(gmailURL, '_blank');
}

// Helper function to get student ID from the current page
function getStudentIdFromPage() {
    // Try to get from URL path (e.g., /admin/students/123)
    const pathParts = window.location.pathname.split('/');
    const studentIndex = pathParts.indexOf('students');
    if (studentIndex !== -1 && pathParts[studentIndex + 1]) {
        return pathParts[studentIndex + 1];
    }
    
    // Try to get from data attribute or other sources
    const studentElement = document.querySelector('[data-student-id]');
    if (studentElement) {
        return studentElement.getAttribute('data-student-id');
    }
    
    return null;
}

// Function to close receipt modal
function closeReceiptModal() {
    const receiptModal = document.getElementById('receiptModal');
    if (receiptModal) {
        receiptModal.classList.add('hidden');
        // No page reload to avoid interrupting WhatsApp functionality
    }
}
