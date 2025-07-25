// Function to open receipt modal
function openReceiptModal(paymentId) {
    const receiptModal = document.getElementById('receiptModal');
    const receiptContent = document.getElementById('receipt-content');
    let studentPhone = '';
    let studentEmail = '';
    
    // Show modal
    receiptModal.classList.remove('hidden');
    
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
    
    // WhatsApp button handler
    newWhatsappBtn.addEventListener('click', function() {
        // If we have a phone number, use it; otherwise ask for phone number
        if (phone) {
            sendWhatsAppReceipt(paymentId, phone);
        } else {
            Swal.fire({
                title: 'Enter Phone Number',
                input: 'tel',
                inputLabel: 'Phone number with country code (e.g. +233...)',
                inputPlaceholder: '+233',
                showCancelButton: true,
                confirmButtonText: 'Send',
                confirmButtonColor: '#25D366', // WhatsApp green
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Please enter a phone number';
                    }
                }
            }).then(result => {
                if (result.isConfirmed) {
                    sendWhatsAppReceipt(paymentId, result.value);
                }
            });
        }
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
    // Clean phone number (remove spaces, ensure starts with +)
    let formattedPhone = phone.trim();
    if (!formattedPhone.startsWith('+')) {
        formattedPhone = '+' + formattedPhone;
    }
    
    // Create WhatsApp message
    const message = `Your payment receipt from IMT is ready. View it here: ${window.location.origin}/admin/payments/receipt/${paymentId}`;
    const whatsappURL = `https://wa.me/${formattedPhone.replace('+', '')}?text=${encodeURIComponent(message)}`;
    
    // Open WhatsApp in new window
    window.open(whatsappURL, '_blank');
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

// Function to close receipt modal
function closeReceiptModal() {
    const receiptModal = document.getElementById('receiptModal');
    if (receiptModal) {
        receiptModal.classList.add('hidden');
        // Optional: Reload page to reflect any changes
        window.location.reload();
    }
}
