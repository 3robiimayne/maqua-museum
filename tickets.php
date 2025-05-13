<?php
require_once 'includes/dbconn.inc.php';
require_once 'includes/header.inc.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['userID']);

// Fetch ticket types
try {
    $stmt = $conn->prepare("SELECT * FROM tblTicketTypes");
    $stmt->execute();
    $ticketTypes = $stmt->fetchAll();
} catch(PDOException $e) {
    // If table doesn't exist yet, use sample data
    $ticketTypes = [
        ['typeID' => 1, 'name' => 'Adult', 'price' => 15.00, 'description' => 'Ages 18-64'],
        ['typeID' => 2, 'name' => 'Child', 'price' => 8.00, 'description' => 'Ages 3-17'],
        ['typeID' => 3, 'name' => 'Senior', 'price' => 12.00, 'description' => 'Ages 65+'],
        ['typeID' => 4, 'name' => 'Family Pack', 'price' => 45.00, 'description' => '2 Adults + 3 Children']
    ];
}
?>

<!-- Tickets Hero Section -->
<section class="relative bg-dark text-white py-16">
    <div class="absolute inset-0 overflow-hidden">
        <img src="https://source.unsplash.com/random/1920x1080/?aquarium-tickets" alt="Buy Tickets" class="w-full h-full object-cover opacity-40">
    </div>
    <div class="relative container mx-auto px-4 text-center animate__animated animate__fadeIn">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Purchase Tickets</h1>
        <p class="text-xl mb-0 max-w-3xl mx-auto">Plan your visit to MAQUA Museum</p>
    </div>
</section>

<!-- Ticket Information -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-gray-50 p-6 rounded-lg shadow-md mb-8" data-aos="fade-up">
                <h2 class="text-2xl font-bold mb-4">Opening Hours</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="font-semibold">Monday - Friday:</p>
                        <p class="mb-2">9:00 AM - 6:00 PM</p>
                        
                        <p class="font-semibold">Saturday:</p>
                        <p class="mb-2">10:00 AM - 8:00 PM</p>
                        
                        <p class="font-semibold">Sunday:</p>
                        <p>10:00 AM - 5:00 PM</p>
                    </div>
                    <div>
                        <p class="font-semibold">Last Entry:</p>
                        <p class="mb-2">1 hour before closing</p>
                        
                        <p class="font-semibold">Special Holiday Hours:</p>
                        <p>Please check our social media for holiday hours</p>
                    </div>
                </div>
            </div>
            
            <div class="mb-12" data-aos="fade-up">
                <h2 class="text-2xl font-bold mb-6">Ticket Options</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach($ticketTypes as $ticket): ?>
                        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:border-primary transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-bold"><?php echo $ticket['name']; ?></h3>
                                <span class="text-xl font-bold text-primary">$<?php echo number_format($ticket['price'], 2); ?></span>
                            </div>
                            <p class="text-gray-600 mb-4"><?php echo $ticket['description']; ?></p>
                            <button class="btn-primary w-full select-ticket" data-ticket-id="<?php echo $ticket['typeID']; ?>" data-ticket-name="<?php echo $ticket['name']; ?>" data-ticket-price="<?php echo $ticket['price']; ?>">
                                Select
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Ticket Purchase Form -->
            <div class="bg-gray-50 p-8 rounded-lg shadow-lg animate__animated animate__fadeIn" id="ticket-form" data-aos="fade-up">
                <h2 class="text-2xl font-bold mb-6">Purchase Your Tickets</h2>
                
                <?php if(!$isLoggedIn): ?>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    You are not logged in. <a href="login.php" class="font-medium underline text-yellow-700 hover:text-yellow-600">Login</a> or <a href="register.php" class="font-medium underline text-yellow-700 hover:text-yellow-600">Register</a> to save your purchase history.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form id="purchase-form" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="visit-date" class="block text-sm font-medium text-gray-700 mb-1">Visit Date</label>
                            <input type="date" id="visit-date" name="visit-date" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required <?php echo $isLoggedIn ? 'value="'.$_SESSION['email'].'"' : ''; ?>>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium mb-3">Selected Tickets</h3>
                        <div id="selected-tickets" class="space-y-4 mb-4">
                            <p class="text-gray-500 italic">No tickets selected. Please select tickets above.</p>
                        </div>
                        
                        <div class="flex justify-between items-center font-bold text-lg border-t border-gray-200 pt-4">
                            <span>Total:</span>
                            <span id="total-price">$0.00</span>
                        </div>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full btn-primary py-3 text-lg">Complete Purchase</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Additional Information -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold mb-8 text-center" data-aos="fade-up">Visitor Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up">
                    <h3 class="text-xl font-bold mb-4">Getting Here</h3>
                    <p class="mb-4">MAQUA Museum is located at 123 Ocean Avenue, Coastal City, CC 12345.</p>
                    <p class="mb-4"><strong>By Public Transport:</strong> Take bus lines 10, 15, or 22 to Ocean Avenue stop.</p>
                    <p><strong>Parking:</strong> Paid parking is available in our underground garage.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xl font-bold mb-4">Accessibility</h3>
                    <p class="mb-4">MAQUA Museum is fully accessible for visitors with disabilities.</p>
                    <p class="mb-4">Wheelchair rentals are available at the information desk.</p>
                    <p>Service animals are welcome throughout the museum.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xl font-bold mb-4">Group Visits</h3>
                    <p class="mb-4">Groups of 15 or more visitors can benefit from discounted rates.</p>
                    <p>Please contact our group bookings department at groups@maquamuseum.com to arrange your visit.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-xl font-bold mb-4">Museum Rules</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li>No food or drinks in the exhibit areas</li>
                        <li>No flash photography</li>
                        <li>Do not touch the exhibits unless indicated</li>
                        <li>Children under 12 must be accompanied by an adult</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom JavaScript for Ticket Selection -->
<script>
$(document).ready(function() {
    let selectedTickets = {};
    let totalPrice = 0;
    
    // Handle ticket selection
    $('.select-ticket').click(function() {
        const ticketId = $(this).data('ticket-id');
        const ticketName = $(this).data('ticket-name');
        const ticketPrice = parseFloat($(this).data('ticket-price'));
        
        // Check if ticket is already selected
        if (selectedTickets[ticketId]) {
            selectedTickets[ticketId].quantity++;
        } else {
            selectedTickets[ticketId] = {
                id: ticketId,
                name: ticketName,
                price: ticketPrice,
                quantity: 1
            };
        }
        
        updateTicketDisplay();
        
        // Scroll to form
        $('html, body').animate({
            scrollTop: $('#ticket-form').offset().top - 100
        }, 500);
    });
    
    // Update the display of selected tickets
    function updateTicketDisplay() {
        const $selectedTickets = $('#selected-tickets');
        $selectedTickets.empty();
        
        totalPrice = 0;
        
        if (Object.keys(selectedTickets).length === 0) {
            $selectedTickets.append('<p class="text-gray-500 italic">No tickets selected. Please select tickets above.</p>');
        } else {
            for (const id in selectedTickets) {
                const ticket = selectedTickets[id];
                const subtotal = ticket.price * ticket.quantity;
                totalPrice += subtotal;
                
                const $ticketItem = $(`
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-md">
                        <div>
                            <h4 class="font-medium">${ticket.name}</h4>
                            <p class="text-sm text-gray-500">$${ticket.price.toFixed(2)} each</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button type="button" class="decrease-quantity px-3 py-1 bg-gray-200 rounded-md" data-ticket-id="${ticket.id}">-</button>
                            <span class="ticket-quantity">${ticket.quantity}</span>
                            <button type="button" class="increase-quantity px-3 py-1 bg-gray-200 rounded-md" data-ticket-id="${ticket.id}">+</button>
                            <span class="ml-4 font-medium">$${subtotal.toFixed(2)}</span>
                        </div>
                    </div>
                `);
                
                $selectedTickets.append($ticketItem);
            }
        }
        
        $('#total-price').text(`$${totalPrice.toFixed(2)}`);
        
        // Set up event handlers for quantity buttons
        $('.decrease-quantity').click(function() {
            const ticketId = $(this).data('ticket-id');
            if (selectedTickets[ticketId].quantity > 1) {
                selectedTickets[ticketId].quantity--;
            } else {
                delete selectedTickets[ticketId];
            }
            updateTicketDisplay();
        });
        
        $('.increase-quantity').click(function() {
            const ticketId = $(this).data('ticket-id');
            selectedTickets[ticketId].quantity++;
            updateTicketDisplay();
        });
    }
    
    // Handle form submission
    $('#purchase-form').submit(function(e) {
        e.preventDefault();
        
        if (Object.keys(selectedTickets).length === 0) {
            alert('Please select at least one ticket.');
            return;
        }
        
        const visitDate = $('#visit-date').val();
        const email = $('#email').val();
        
        // Prepare data for submission
        const purchaseData = {
            visitDate: visitDate,
            email: email,
            tickets: Object.values(selectedTickets),
            totalPrice: totalPrice
        };
        
        // Send data to server using AJAX
        $.ajax({
            url: 'includes/process_tickets.inc.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(purchaseData),
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        // Show success message
                        $('#purchase-form').html(`
                            <div class="text-center py-8 animate__animated animate__bounceIn">
                                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <h3 class="mt-4 text-xl font-bold text-gray-900">Purchase Successful!</h3>
                                <p class="mt-2 text-gray-600">Your tickets have been purchased successfully. A confirmation email has been sent to ${email}.</p>
                                <p class="mt-4 text-gray-600">Order Reference: <span class="font-medium">${result.orderReference}</span></p>
                                <div class="mt-6">
                                    <a href="index.php" class="btn-secondary">Return to Homepage</a>
                                </div>
                            </div>
                        `);
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (e) {
                    // Fix for the JSON parsing error
                    console.error('JSON parsing error:', e);
                    alert('There was an error processing your request. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                alert('There was an error processing your request. Please try again.');
            }
        });
    });
});
</script>

<?php require_once 'includes/footer.inc.php'; ?>