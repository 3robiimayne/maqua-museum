<?php
require_once 'includes/dbconn.inc.php';
require_once 'includes/header.inc.php';

// Fetch featured exhibits
try {
    $stmt = $conn->prepare("SELECT * FROM tblExhibits WHERE featured = 1 LIMIT 6");
    $stmt->execute();
    $featuredExhibits = $stmt->fetchAll();
} catch(PDOException $e) {
    // If table doesn't exist yet, use sample data
    $featuredExhibits = [
        ['exhibitID' => 1, 'title' => 'Ocean Wonders', 'description' => 'Explore the mysteries of the deep ocean and discover amazing sea creatures.', 'image' => 'https://source.unsplash.com/random/800x600/?ocean'],
        ['exhibitID' => 2, 'title' => 'Coral Reefs', 'description' => 'Learn about the diverse ecosystems of coral reefs and their importance to marine life.', 'image' => 'https://source.unsplash.com/random/800x600/?coral'],
        ['exhibitID' => 3, 'title' => 'Marine Mammals', 'description' => 'Get up close with dolphins, whales, and other fascinating marine mammals.', 'image' => 'https://source.unsplash.com/random/800x600/?dolphin'],
        ['exhibitID' => 4, 'title' => 'Aquatic Conservation', 'description' => 'Discover how we can protect our oceans and the life within them for future generations.', 'image' => 'https://source.unsplash.com/random/800x600/?conservation'],
        ['exhibitID' => 5, 'title' => 'Underwater Photography', 'description' => 'View stunning photographs capturing the beauty of underwater landscapes and creatures.', 'image' => 'https://source.unsplash.com/random/800x600/?underwater'],
        ['exhibitID' => 6, 'title' => 'Deep Sea Exploration', 'description' => 'Journey to the deepest parts of the ocean and learn about recent discoveries.', 'image' => 'https://source.unsplash.com/random/800x600/?deep-sea']
    ];
}

// Fetch upcoming events
try {
    $stmt = $conn->prepare("SELECT * FROM tblEvents WHERE eventDate >= CURDATE() ORDER BY eventDate ASC LIMIT 3");
    $stmt->execute();
    $upcomingEvents = $stmt->fetchAll();
} catch(PDOException $e) {
    // If table doesn't exist yet, use sample data
    $upcomingEvents = [
        ['eventID' => 1, 'title' => 'Marine Biology Workshop', 'description' => 'Hands-on workshop for all ages to learn about marine biology.', 'eventDate' => '2023-06-15', 'image' => 'https://source.unsplash.com/random/800x600/?workshop'],
        ['eventID' => 2, 'title' => 'Ocean Documentary Screening', 'description' => 'Special screening of award-winning ocean documentaries.', 'eventDate' => '2023-06-22', 'image' => 'https://source.unsplash.com/random/800x600/?documentary'],
        ['eventID' => 3, 'title' => 'World Oceans Day Celebration', 'description' => 'Join us for a day of activities celebrating our oceans.', 'eventDate' => '2023-06-08', 'image' => 'https://source.unsplash.com/random/800x600/?celebration']
    ];
}
?>

<!-- Hero Section -->
<section class="relative bg-dark text-white py-20">
    <div class="absolute inset-0 overflow-hidden">
        <img src="https://source.unsplash.com/random/1920x1080/?aquarium" alt="MAQUA Museum" class="w-full h-full object-cover opacity-40">
    </div>
    <div class="relative container mx-auto px-4 text-center animate__animated animate__fadeIn">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Welcome to MAQUA Museum</h1>
        <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">Discover the wonders of aquatic life and marine conservation</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="tickets.php" class="btn-primary text-lg px-6 py-3">Buy Tickets</a>
            <a href="#exhibits" class="btn-secondary text-lg px-6 py-3">Explore Exhibits</a>
        </div>
    </div>
</section>

<!-- Featured Exhibits -->
<section id="exhibits" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Featured Exhibits</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($featuredExhibits as $exhibit): ?>
                <div class="card bg-white shadow-lg rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="<?php echo $exhibit['exhibitID'] * 100; ?>">
                    <figure>
                        <img src="<?php echo $exhibit['image']; ?>" alt="<?php echo $exhibit['title']; ?>" class="w-full h-64 object-cover">
                    </figure>
                    <div class="card-body p-6">
                        <h3 class="card-title text-xl font-bold mb-2"><?php echo $exhibit['title']; ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo $exhibit['description']; ?></p>
                        <div class="card-actions justify-end">
                            <a href="#" class="btn-primary">Learn More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-12">
            <div class="md:w-1/2" data-aos="fade-right">
                <img src="https://source.unsplash.com/random/800x600/?museum" alt="About MAQUA Museum" class="rounded-lg shadow-lg w-full">
            </div>
            <div class="md:w-1/2" data-aos="fade-left">
                <h2 class="text-3xl font-bold mb-6">About MAQUA Museum</h2>
                <p class="text-gray-600 mb-4">MAQUA Museum is dedicated to educating visitors about the importance of aquatic ecosystems and marine conservation. Our state-of-the-art facility houses numerous exhibits featuring marine life from around the world.</p>
                <p class="text-gray-600 mb-6">Founded in 2005, we have welcomed over 2 million visitors and continue to expand our educational programs and conservation efforts.</p>
                <a href="#" class="btn-primary">Our Mission</a>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Upcoming Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach($upcomingEvents as $event): ?>
                <div class="card bg-white shadow-lg rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="<?php echo $event['eventID'] * 100; ?>">
                    <figure>
                        <img src="<?php echo $event['image']; ?>" alt="<?php echo $event['title']; ?>" class="w-full h-48 object-cover">
                    </figure>
                    <div class="card-body p-6">
                        <div class="badge bg-primary text-white mb-2">
                            <?php echo date('F j, Y', strtotime($event['eventDate'])); ?>
                        </div>
                        <h3 class="card-title text-xl font-bold mb-2"><?php echo $event['title']; ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo $event['description']; ?></p>
                        <div class="card-actions justify-end">
                            <a href="#" class="btn-secondary">Register</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-primary text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6" data-aos="fade-up">Ready to Visit MAQUA Museum?</h2>
        <p class="text-xl mb-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">Purchase your tickets today and embark on an unforgettable journey through the wonders of aquatic life.</p>
        <a href="tickets.php" class="inline-block bg-white text-primary font-bold px-8 py-3 rounded-md hover:bg-gray-100 transition-colors" data-aos="fade-up" data-aos-delay="200">Get Tickets Now</a>
    </div>
</section>

<?php require_once 'includes/footer.inc.php'; ?>