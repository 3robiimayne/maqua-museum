<?php
require_once 'includes/dbconn.inc.php';
require_once 'includes/header.inc.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['userID']) || !isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header("Location: login.php");
    exit();
}

// Get stats
try {
    // Total users
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tblUsers");
    $stmt->execute();
    $totalUsers = $stmt->fetch()['total'];
    
    // Total purchases
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tblAankopen");
    $stmt->execute();
    $totalPurchases = $stmt->fetch()['total'];
    
    // Total revenue
    $stmt = $conn->prepare("SELECT SUM(totalPrice) as total FROM tblAankopen");
    $stmt->execute();
    $totalRevenue = $stmt->fetch()['total'];
    
    // Active auctions
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tblAuctions WHERE endTime > NOW()");
    $stmt->execute();
    $activeAuctions = $stmt->fetch()['total'];
} catch(PDOException $e) {
    // If tables don't exist yet, use sample data
    $totalUsers = 125;
    $totalPurchases = 450;
    $totalRevenue = 12750.50;
    $activeAuctions = 8;
}

// Get recent purchases
try {
    $stmt = $conn->prepare("SELECT a.*, u.email FROM tblAankopen a JOIN tblUsers u ON a.userID = u.userID ORDER BY a.purchaseDate DESC LIMIT 10");
    $stmt->execute();
    $recentPurchases = $stmt->fetchAll();
} catch(PDOException $e) {
    // If table doesn't exist yet, use sample data
    $recentPurchases = [
        ['aankoopID' => 1, 'userID' => 1, 'email' => 'user1@example.com', 'ticketType' => 'Adult', 'quantity' => 2, 'totalPrice' => 30.00, 'purchaseDate' => date('Y-m-d H:i:s', strtotime('-1 day')), 'visitDate' => date('Y-m-d', strtotime('+5 days'))],
        ['aankoopID' => 2, 'userID' => 2, 'email' => 'user2@example.com', 'ticketType' => 'Family Pack', 'quantity' => 1, 'totalPrice' => 45.00, 'purchaseDate' => date('Y-m-d H:i:s', strtotime('-2 days')), 'visitDate' => date('Y-m-d', strtotime('+7 days'))],
        ['aankoopID' => 3, 'userID' => 3, 'email' => 'user3@example.com', 'ticketType' => 'Child', 'quantity' => 3, 'totalPrice' => 24.00, 'purchaseDate' => date('Y-m-d H:i:s', strtotime('-3 days')), 'visitDate' => date('Y-m-d', strtotime('+2 days'))],
        ['aankoopID' => 4, 'userID' => 4, 'email' => 'user4@example.com', 'ticketType' => 'Senior', 'quantity' => 2, 'totalPrice' => 24.00, 'purchaseDate' => date('Y-m-d H:i:s', strtotime('-4 days')), 'visitDate' => date('Y-m-d', strtotime('+10 days'))],
        ['aankoopID' => 5, 'userID' => 5, 'email' => 'user5@example.com', 'ticketType' => 'Adult', 'quantity' => 1, 'totalPrice' => 15.00, 'purchaseDate' => date('Y-m-d H:i:s', strtotime('-5 days')), 'visitDate' => date('Y-m-d', strtotime('+3 days'))]
    ];
}

// Get recent users
try {
    $stmt = $conn->prepare("SELECT * FROM tblUsers ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $recentUsers = $stmt->fetchAll();
} catch(PDOException $e) {
    // If table doesn't exist yet, use sample data
    $recentUsers = [
        ['userID' => 1, 'email' => 'user1@example.com', 'name' => 'John Doe', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))],
        ['userID' => 2, 'email' => 'user2@example.com', 'name' => 'Jane Smith', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))],
        ['userID' => 3, 'email' => 'user3@example.com', 'name' => 'Bob Johnson', 'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))],
        ['userID' => 4, 'email' => 'user4@example.com', 'name' => 'Alice Brown', 'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))],
        ['userID' => 5, 'email' => 'user5@example.com', 'name' => 'Charlie Wilson', 'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))]
    ];
}

// Get auction data
try {
    $stmt = $conn->prepare("SELECT a.*, u.email as bidderEmail FROM tblAuctions a LEFT JOIN tblUsers u ON a.highestBidderID = u.userID ORDER BY a.endTime DESC LIMIT 10");
    $stmt->execute();
    $auctions = $stmt->fetchAll();
} catch(PDOException $e) {
    // If table doesn't exist yet, use sample data
    $auctions = [
        ['auctionID' => 1, 'itemName' => 'Vintage Diving Helmet', 'startingBid' => 500.00, 'currentBid' => 750.00, 'highestBidderID' => 1, 'bidderEmail' => 'user1@example.com', 'endTime' => date('Y-m-d H:i:s', strtotime('+2 days'))],
        ['auctionID' => 2, 'itemName' => 'Rare Marine Fossil Collection', 'startingBid' => 300.00, 'currentBid' => 450.00, 'highestBidderID' => 2, 'bidderEmail' => 'user2@example.com', 'endTime' => date('Y-m-d H:i:s', strtotime('+3 days'))],
        ['auctionID' => 3, 'itemName' => 'Signed Marine Biology Book', 'startingBid' => 200.00, 'currentBid' => 200.00, 'highestBidderID' => null, 'bidderEmail' => null, 'endTime' => date('Y-m-d H:i:s', strtotime('+5 days'))],
        ['auctionID' => 4, 'itemName' => 'Antique Ship Model', 'startingBid' => 400.00, 'currentBid' => 400.00, 'highestBidderID' => null, 'bidderEmail' => null, 'endTime' => date('Y-m-d H:i:s', strtotime('+4 days'))],
        ['auctionID' => 5, 'itemName' => 'Antique Nautical Map', 'startingBid' => 250.00, 'currentBid' => 375.00, 'highestBidderID' => 3, 'bidderEmail' => 'user3@example.com', 'endTime' => date('Y-m-d H:i:s', strtotime('-2 days'))]
    ];
}
?>

<!-- Admin Dashboard -->
<div class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
            <p class="text-gray-600">Welcome back, <?php echo $_SESSION['name'] ?? 'Admin'; ?></p>
        </div>
    </header>
    
    <!-- Stats Cards -->
    <section class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-primary bg-opacity-10 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Total Users</h2>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $totalUsers; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-secondary bg-opacity-10 text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Total Purchases</h2>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $totalPurchases; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Total Revenue</h2>
                        <p class="text-2xl font-bold text-gray-800">$<?php echo number_format($totalRevenue, 2); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Active Auctions</h2>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $activeAuctions; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Main Content -->
    <section class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Purchases -->
            <div class="bg-white rounded-lg shadow-md" data-aos="fade-up">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-800">Recent Purchases</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($recentPurchases as $purchase): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $purchase['aankoopID']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $purchase['email']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $purchase['ticketType']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $purchase['quantity']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo number_format($purchase['totalPrice'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y', strtotime($purchase['visitDate'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 px-6 py-4">
                    <a href="#" class="text-primary hover:text-primary-dark font-medium">View All Purchases</a>
                </div>
            </div>
            
            <!-- Recent Users -->
            <div class="bg-white rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-800">Recent Users</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($recentUsers as $user): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $user['userID']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $user['name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $user['email']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 px-6 py-4">
                    <a href="#" class="text-primary hover:text-primary-dark font-medium">View All Users</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Auctions Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md" data-aos="fade-up">
            <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-800">Auctions</h2>
                <button class="btn-primary" id="add-auction-btn">Add New Auction</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Starting Bid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Bid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Highest Bidder</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach($auctions as $auction): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $auction['auctionID']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $auction['itemName']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo number_format($auction['startingBid'], 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo number_format($auction['currentBid'], 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $auction['bidderEmail'] ?? 'No bids yet'; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y H:i', strtotime($auction['endTime'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if(strtotime($auction['endTime']) > time()): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Ended</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button class="text-primary hover:text-primary-dark edit-auction" data-id="<?php echo $auction['auctionID']; ?>">Edit</button>
                                    <button class="ml-3 text-red-600 hover:text-red-900 delete-auction" data-id="<?php echo $auction['auctionID']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-200 px-6 py-4">
                <a href="#" class="text-primary hover:text-primary-dark font-medium">View All Auctions</a>
            </div>
        </div>
    </section>
</div>

<!-- Add/Edit Auction Modal -->
<div id="auction-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">Add New Auction</h3>
            <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="auction-form" class="space-y-4">
            <input type="hidden" id="auction-id" name="auction-id" value="">
            
            <div>
                <label for="item-name" class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                <input type="text" id="item-name" name="item-name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="starting-bid" class="block text-sm font-medium text-gray-700 mb-1">Starting Bid ($)</label>
                    <input type="number" id="starting-bid" name="starting-bid" min="1" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                </div>
                
                <div>
                    <label for="end-time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                    <input type="datetime-local" id="end-time" name="end-time" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                </div>
            </div>
            
            <div>
                <label for="image-url" class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                <input type="url" id="image-url" name="image-url" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
            </div>
            
            <div class="flex justify-end pt-4">
                <button type="button" id="cancel-btn" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary mr-3">
                    Cancel
                </button>
                <button type="submit" class="btn-primary py-2 px-4">
                    Save Auction
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Admin JavaScript -->
<script>
$(document).ready(function() {
    // Show modal
    $('#add-auction-btn').click(function() {
        $('#modal-title').text('Add New Auction');
        $('#auction-id').val('');
        $('#auction-form')[0].reset();
        
        // Set default end time to 7 days from now
        const now = new Date();
        now.setDate(now.getDate() + 7);
        $('#end-time').val(now.toISOString().slice(0, 16));
        
        $('#auction-modal').removeClass('hidden