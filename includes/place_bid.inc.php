<?php
session_start();
require_once 'dbconn.inc.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to place a bid']);
    exit();
}

// Get raw POST data
$json = file_get_contents('php://input');

try {
    // Decode JSON data
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }
    
    // Validate required fields
    if (!isset($data['auctionId']) || !isset($data['bidAmount'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }
    
    $auctionId = $data['auctionId'];
    $bidAmount = $data['bidAmount'];
    $userId = $_SESSION['userID'];
    
    // Begin transaction
    $conn->beginTransaction();
    
    // Get current auction data
    $stmt = $conn->prepare("SELECT * FROM tblAuctions WHERE auctionID = :auctionId FOR UPDATE");
    $stmt->bindParam(':auctionId', $auctionId);
    $stmt->execute();
    
    if (!$auction = $stmt->fetch()) {
        throw new Exception('Auction not found');
    }
    
    // Check if auction has ended
    if (strtotime($auction['endTime']) < time()) {
        throw new Exception('This auction has ended');
    }
    
    // Check if bid is higher than current bid
    if ($bidAmount <= $auction['currentBid']) {
        throw new Exception('Your bid must be higher than the current bid');
    }
    
    // Update auction with new bid
    $stmt = $conn->prepare("UPDATE tblAuctions SET currentBid = :bidAmount, highestBidderID = :userId WHERE auctionID = :auctionId");
    $stmt->bindParam(':bidAmount', $bidAmount);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':auctionId', $auctionId);
    $stmt->execute();
    
    // Record bid in bid history table (if you have one)
    // $stmt = $conn->prepare("INSERT INTO tblBidHistory (auctionID, userID, bidAmount, bidTime) VALUES (:auctionId, :userId, :bidAmount, NOW())");
    // $stmt->bindParam(':auctionId', $auctionId);
    // $stmt->bindParam(':userId', $userId);
    // $stmt->bindParam(':bidAmount', $bidAmount);
    // $stmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    // Return success response
    echo json_encode([
        'success' => true, 
        'message' => 'Bid placed successfully',
        'newBid' => $bidAmount
    ]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Log the error (in a production environment)
    error_log('Bid error: ' . $e->getMessage());
    
    // Return error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>