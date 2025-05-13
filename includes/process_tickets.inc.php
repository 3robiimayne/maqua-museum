<?php
session_start();
require_once 'dbconn.inc.php';

// Get raw POST data
$json = file_get_contents('php://input');

// Fix for the JSON parsing error
if (!$json) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit();
}

try {
    // Decode JSON data
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }
    
    // Validate required fields
    if (!isset($data['visitDate']) || !isset($data['email']) || !isset($data['tickets']) || !isset($data['totalPrice'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }
    
    // Get user ID (if logged in) or find/create user by email
    $userID = null;
    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];
    } else {
        // Check if user exists with this email
        $stmt = $conn->prepare("SELECT userID FROM tblUsers WHERE email = :email");
        $stmt->bindParam(':email', $data['email']);
        $stmt->execute();
        
        if ($user = $stmt->fetch()) {
            $userID = $user['userID'];
        } else {
            // Create a temporary user (or you could require login)
            $tempPassword = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO tblUsers (email, password) VALUES (:email, :password)");
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $tempPassword);
            $stmt->execute();
            $userID = $conn->lastInsertId();
        }
    }
    
    // Begin transaction
    $conn->beginTransaction();
    
    // Generate order reference
    $orderReference = 'TKT-' . strtoupper(bin2hex(random_bytes(4)));
    
    // Process each ticket type
    foreach ($data['tickets'] as $ticket) {
        $stmt = $conn->prepare("INSERT INTO tblAankopen (userID, ticketType, quantity, totalPrice, purchaseDate, visitDate, orderReference) 
                               VALUES (:userID, :ticketType, :quantity, :totalPrice, NOW(), :visitDate, :orderReference)");
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':ticketType', $ticket['name']);
        $stmt->bindParam(':quantity', $ticket['quantity']);
        $subtotal = $ticket['price'] * $ticket['quantity'];
        $stmt->bindParam(':totalPrice', $subtotal);
        $stmt->bindParam(':visitDate', $data['visitDate']);
        $stmt->bindParam(':orderReference', $orderReference);
        $stmt->execute();
    }
    
    // Commit transaction
    $conn->commit();
    
    // Return success response
    echo json_encode([
        'success' => true, 
        'message' => 'Tickets purchased successfully', 
        'orderReference' => $orderReference
    ]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Log the error (in a production environment)
    error_log('Ticket purchase error: ' . $e->getMessage());
    
    // Return error response
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>