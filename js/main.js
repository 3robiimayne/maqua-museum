/**
 * MAQUA Museum - Main JavaScript
 * 
 * This file contains the main JavaScript functionality for the MAQUA Museum website.
 */

$(document).ready(function() {
    // Initialize AOS (Animate on Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });
    
    // Mobile menu toggle
    $('#mobile-menu-button').click(function() {
        $('#mobile-menu').toggleClass('hidden');
    });
    
    // Theme toggle
    $('#theme-toggle').click(function() {
        const currentTheme = $('html').attr('data-theme');
        const newTheme = currentTheme === 'light' ? 'orange' : 'light';
        $('html').attr('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });
    
    // Check for saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        $('html').attr('data-theme', savedTheme);
    }
    
    // Form validation
    $('.validate-form').submit(function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('border-red-500');
                
                // Add error message if it doesn't exist
                if ($(this).next('.error-message').length === 0) {
                    $(this).after('<p class="error-message text-red-500 text-sm mt-1">This field is required</p>');
                }
            } else {
                $(this).removeClass('border-red-500');
                $(this).next('.error-message').remove();
            }
        });
        
        // Check email format
        $(this).find('input[type="email"]').each(function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if ($(this).val() !== '' && !emailRegex.test($(this).val())) {
                isValid = false;
                $(this).addClass('border-red-500');
                
                // Add error message if it doesn't exist
                if ($(this).next('.error-message').length === 0) {
                    $(this).after('<p class="error-message text-red-500 text-sm mt-1">Please enter a valid email address</p>');
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Clear validation errors on input
    $('input, textarea, select').on('input', function() {
        $(this).removeClass('border-red-500');
        $(this).next('.error-message').remove();
    });
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        const target = $(this.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
    
    // Countdown timer for auctions
    function updateCountdowns() {
        $('.countdown').each(function() {
            const endTime = new Date($(this).data('end'));
            const now = new Date();
            const diff = endTime - now;
            
            if (diff <= 0) {
                $(this).text('Auction ended');
                $(this).closest('.card').find('.place-bid').prop('disabled', true).text('Ended');
            } else {
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                
                if (days > 0) {
                    $(this).text(`${days} days, ${hours} hours`);
                } else if (hours > 0) {
                    $(this).text(`${hours} hours, ${minutes} minutes`);
                } else {
                    $(this).text(`${minutes} minutes`);
                }
                
                // Highlight ending soon
                if (diff < 3600000) { // Less than 1 hour
                    $(this).addClass('text-red-600 font-bold');
                }
            }
        });
    }
    
    // Initialize countdowns if they exist on the page
    if ($('.countdown').length > 0) {
        updateCountdowns();
        setInterval(updateCountdowns, 60000); // Update every minute
    }
});