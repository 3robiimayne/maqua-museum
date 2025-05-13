<?php
require_once 'includes/dbconn.inc.php';
require_once 'includes/header.inc.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['userID']);

// Fetch active auctions
try {
    $stmt = $conn->prepare("SELECT a.*, u.email as bidderEmail FROM tblAuctions a LEFT JOIN tblUsers u ON a.highestBidderID = u.userID WHERE a.endTime > NOW() ORDER BY a.endTime ASC");
    $stmt->execute();
    $activeAuctions = $stmt->fetchAll();
} catch(PDOException $e) {
    // If table doesn't exist yet, use sample data
    $activeAuctions = [
        ['auctionID' => 1, 'itemName' => 'Vintage Diving Helmet', 'description' => 'A beautifully preserved diving helmet from the 1940s.', 'startingBid' => 500.00, 'currentBid' => 750.00, 'highestBidderID' => 1, 'bidderEmail' => 'user@example.com', 'endTime' => date('Y-m-d H:i:s', strtotime('+2 days')), 'image' => 'https://source.unsplash.com/random/800x600/?diving-helmet'],
        ['auctionID' => 2, 'itemName' => 'Rare Marine Fossil Collection', 'description' => 'A collection of rare marine fossils dating back millions of years.', 'startingBid' => 300.00, 'currentBid' => 450.00, 'highestBidderID' => 2, 'bidderEmail' => 'bidder@example.com', 'endTime' => date('Y-m-d H:i:s', strtotime('+3 days')), 'image' => 'https://source.unsplash.com/random/800x600/?fossil'],
        ['auctionID' => 3, 'itemName' => 'Signed Marine Biology Book', 'description' => 'First edition marine biology book signed by Jacques Cousteau.', 'startingBid' => 200.00, 'currentBid' => 200.00, 'highestBidderID' => null, 'bidderEmail' => null, 'endTime' => date('Y-m-d H:i:s', strtotime('+5 days')), 'image' => 'https://source.unsplash.com/random/800x600/?book'],
        ['auctionID' => 4, 'itemName' => 'Antique Ship Model', 'description' => 'Detailed model of a 19th century whaling ship.', 'startingBid' => 400.00, 'currentBid' => 400.00, 'highestBidderID' => null, 'bidderEmail' => null, 'endTime' => date('Y-m-d H:i:s', strtotime('+4 days')), 'image' => 'https://source.unsplash.com/random/800x600/?ship-model']
    ];
}

// Fetch past auctions
try {
    $stmt = $conn->prepare("SELECT a.*, u.email as bidderEmail FROM tblAuctions a LEFT JOIN tblUsers u ON a.highestBidderID = u.userID WHERE a.endTime <= NOW() ORDER BY a.endTime DESC LIMIT 4");
    $stmt->execute();
    $pastAuctions = $stmt->fetchAll();
} catch(PDOException $e) {
    // If table doesn't exist yet, use sample data
    $pastAuctions = [
        ['auctionID' => 5, 'itemName' => 'Antique Nautical Map', 'description' => 'Rare nautical map from the 18th century.', 'startingBid' => 250.00, 'currentBid' => 375.00, 'highestBidderID' => 3, 'bidderEmail' => 'collector@example.com', 'endTime' => date('Y-m-d H:i:s', strtotime('-2 days')), 'image' => 'https://source.unsplash.com/random/800x600/?map'],
        ['auctionID' => 6, 'itemName' => 'Coral Specimen Collection', 'description' => 'A collection of preserved coral specimens.', 'startingBid' => 150.00, 'currentBid' => 225.00, 'highestBidderID' => 1, 'bidderEmail' => 'user@example.com', 'endTime' => date('Y-m-d H:i:s', strtotime('-5 days')), 'image' => 'https://source.unsplash.com/random/800x600/?coral'],
        ['auctionID' => 7, 'itemName' => 'Vintage Underwater Camera', 'description' => 'Functional underwater camera from the 1970s.', 'startingBid' => 350.00, 'currentBid' => 525.00, 'highestBidderID' => 2, 'bidderEmail' => 'bidder@example.com', 'endTime' => date('Y-m-d H:i:s', strtotime('-7 days')), 'image' => 'https://source.unsplash.com/random/800x600/?camera'],
        ['auctionID' => 8, 'itemName' => 'Marine Art Print', 'description' => 'Limited edition print of marine life by renowned artist.', 'startingBid' => 100.00, 'currentBid' => 175.00, 'highestBidderID' => 4, 'bidderEmail' => 'art@example.com', 'endTime' => date('Y-m-d H:i:s', strtotime('-10 days')), 'image' => 'https://source.unsplash.com/random/800x600/?art']
    ];
}
?>

<!-- Auctions Hero Section -->
<section class="relative bg-dark text-white py-16">
    <div class="absolute inset-0 overflow-hidden">
        <img src="https://source.unsplash.com/random/1920x1080/?auction" alt="MAQUA Auctions" class="w-full h-full object-cover opacity-40">
    </div>
    <div class="relative container mx-auto px-4 text-center animate__animated animate__fadeIn">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">MAQUA Museum Auctions</h1>
        <p class="text-xl mb-0 max-w-3xl mx-auto">Bid on unique marine artifacts and support our conservation efforts</p>
    </div>
</section>

<!-- Active Auctions -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Active Auctions</h2>
        
        <?php if(!$isLoggedIn): ?>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 max-w-4xl mx-auto">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            You need to be logged in to place bids. <a href="login.php" class="font-medium underline text-yellow-700 hover:text-yellow-600">Login</a> or <a href="register.php" class="font-medium underline text-yellow-700 hover:text-yellow-600">Register</a> to participate in auctions.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($activeAuctions as $auction): ?>
                <div class="card bg-white shadow-lg rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="<?php echo $auction['auctionID'] * 100; ?>">
                    <figure>
                        <img src="<?php echo $auction['image']; ?>" alt="<?php echo $auction['itemName']; ?>" class="w-full h-64 object-cover">
                    </figure>
                    <div class="card-body p-6">
                        <h3 class="card-title text-xl font-bold mb-2"><?php echo $auction['itemName']; ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo $auction['description']; ?></p>
                        
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Current Bid:</span>
                                <span class="font-bold text-lg text-primary">$<?php echo number_format($auction['currentBid'], 2); ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Starting Bid:</span>
                                <span class="text-gray-700">$<?php echo number_format($auction['startingBid'], 2); ?></span>
                            </div>
                            
                            <?php if($auction['highestBidderID']): ?>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-500">Highest Bidder:</span>
                                    <span class="text-gray-700"><?php echo substr($auction['bidderEmail'], 0, 3) . '***' . strstr($auction['bidderEmail'], '@'); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Ends In:</span>
                                <span class="text-gray-700 countdown" data-end="<?php echo $auction['endTime']; ?>">
                                    <?php 
                                    $endTime = new DateTime($auction['endTime']);
                                    $now = new DateTime();
                                    $interval = $now->diff($endTime);
                                    echo $interval->format('%a days, %h hours');
                                    ?>
                                </span>
                            </div>
                        </div>
                        
                        <?php if($isLoggedIn): ?>
                            <div class="card-actions">
                                <div class="flex w-full space-x-2">
                                    <input type="number" class="bid-amount w-2/3 px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="Your bid" min="<?php echo $auction['currentBid'] + 1; ?>" step="1">
                                    <button class="btn-primary w-1/3 place-bid" data-auction-id="<?php echo $auction['auctionID']; ?>" data-current-bid="<?php echo $auction['currentBid']; ?>">Bid</button>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="card-actions">
                                <a href="login.php" class="btn-secondary w-full text-center">Login to Bid</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Past Auctions -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Past Auctions</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($pastAuctions as $auction): ?>
                <div class="card bg-white shadow-md rounded-lg overflow-hidden" data-aos="fade-up" data-aos-delay="<?php echo $auction['auctionID'] * 50; ?>">
                    <figure>
                        <img src="<?php echo $auction['image']; ?>" alt="<?php echo $auction['itemName']; ?>" class="w-full h-48 object-cover opacity-75">
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                            <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-medium">Auction Ended</span>
                        </div>
                    </figure>
                    <div class="card-body p-4">
                        <h3 class="card-title text-lg font-bold mb-1"><?php echo $auction['itemName']; ?></h3>
                        <p class="text-sm text-gray-500 mb-2">Ended: <?php echo date('M j, Y', strtotime($auction['endTime'])); ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Final Bid:</span>
                            <span class="font-bold text-primary">$<?php echo number_format($auction['currentBid'], 2); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Auction Information -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Auction Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg shadow-md" data-aos="fade-up">
                    <h3 class="text-xl font-bold mb-4">How to Bid</h3>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>Create an account or log in to your existing account.</li>
                        <li>Browse the active auctions and find items you're interested in.</li>
                        <li>Enter your bid amount (must be higher than the current bid).</li>
                        <li>Click the "Bid" button to place your bid.</li>
                        <li>You will be notified if you are outbid or if you win the auction.</li>
                    </ol>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xl font-bold mb-4">Auction Rules</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li>All bids are final and cannot be retracted.</li>
                        <li>The minimum bid increment is $1.00.</li>
                        <li>Auctions end at the specified time, regardless of recent bids.</li>
                        <li>Winners will be notified via email.</li>
                        <li>Payment must be completed within 7 days of auction end.</li>
                        <li>Items can be picked up at the museum or shipped for an additional fee.</li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-12 bg-primary text-white p-8 rounded-lg text-center" data-aos="fade-up">
                <h3 class="text-2xl font-bold mb-4">Support Our Conservation Efforts</h3>
                <p class="mb-6">All proceeds from our auctions go directly to supporting marine conservation projects and educational programs at MAQUA Museum.</p>
                <a href="#" class="inline-block bg-white text-primary font-bold px-6 py-2 rounded-md hover:bg-gray-100 transition-colors">Learn More About Our Projects</a>
            </div>
        </div>
    </div>
</section>

<!-- Bidding JavaScript -->
<script>
$(document).ready(function() {
    // Place bid
    $('.place-bid').click(function() {
        const auctionId = $(this).data('auction-id');
        const currentBid = parseFloat($(this).data('current-bid'));
        const bidAmount = parseFloat($(this).siblings('.bid-amount').val());
        
        // Validate bid
        if (!bidAmount || isNaN(bidAmount)) {
            alert('Please enter a valid bid amount.');
            return;
        }
        
        if (bidAmount <= currentBid) {
            alert('Your bid must be higher than the current bid.');
            return;
        }
        
        // Send bid to server
        $.ajax({
            url: 'includes/place_bid.inc.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                auctionId: auctionId,
                bidAmount: bidAmount
            }),
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        alert('Your bid has been placed successfully!');
                        // Update the UI
                        const $card = $(`.place-bid[data-auction-id="${auctionId}"]`).closest('.card');
                        $card.find('.bid-amount').attr('min', bidAmount + 1);
                        $card.find('.place-bid').data('current-bid', bidAmount);
                        $card.find('.text-primary').text('$' + bidAmount.toFixed(2));
                        
                        // Add animation
                        $card.addClass('animate__animated animate__pulse');
                        setTimeout(function() {
                            $card.removeClass('animate__animated animate__pulse');
                        }, 1000);
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (e) {
                    console.error('JSON parsing error:', e);
                    alert('There was an error processing your bid. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                alert('There was an error processing your bid. Please try again.');
            }
        });
    });
    
    // Update countdowns
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
    
    // Initial update
    updateCountdowns();
    
    // Update every minute
    setInterval(updateCountdowns, 60000);
});
</script>

<?php require_once 'includes/footer.inc.php'; ?>