<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAQUA Museum</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.6.0/dist/full.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="index.php" class="text-2xl font-bold text-primary">MAQUA Museum</a>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="index.php" class="px-3 py-2 rounded-md hover:bg-gray-100">Home</a>
                    <a href="tickets.php" class="px-3 py-2 rounded-md hover:bg-gray-100">Tickets</a>
                    <a href="auctions.php" class="px-3 py-2 rounded-md hover:bg-gray-100">Auctions</a>
                    <?php if(isset($_SESSION['userID'])): ?>
                        <?php if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
                            <a href="admin.php" class="px-3 py-2 rounded-md hover:bg-gray-100">Admin</a>
                        <?php endif; ?>
                        <a href="includes/logout.inc.php" class="btn-primary">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-secondary">Login</a>
                    <?php endif; ?>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <a href="index.php" class="block px-3 py-2 rounded-md hover:bg-gray-100">Home</a>
                <a href="tickets.php" class="block px-3 py-2 rounded-md hover:bg-gray-100">Tickets</a>
                <a href="auctions.php" class="block px-3 py-2 rounded-md hover:bg-gray-100">Auctions</a>
                <?php if(isset($_SESSION['userID'])): ?>
                    <?php if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
                        <a href="admin.php" class="block px-3 py-2 rounded-md hover:bg-gray-100">Admin</a>
                    <?php endif; ?>
                    <a href="includes/logout.inc.php" class="block px-3 py-2 mt-2 btn-primary">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="block px-3 py-2 mt-2 btn-secondary">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">