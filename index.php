<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System | Café Crepe</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="front.css">

    <!-- Boxicons & FontAwesome -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" crossorigin="anonymous"></script>

</head>
<body>

    <!-- Header / Navbar -->
    <header>
        <div class="company-logo">Café Crepe</div>

        <nav class="navbar">
            <ul class="nav-items">
                <li class="nav-item"><a href="index.php" class="nav-link">HOME</a></li>
                <li class="nav-item"><a href="book.html" class="nav-link">Book Now</a></li>
                <li class="nav-item"><a href="order.php" class="nav-link">Order Food</a></li>
                <li class="nav-item">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" class="nav-link">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="nav-link">Sign In</a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>

        <div class="menu-toggle">
            <i class='bx bx-menu'></i>
            <i class='bx bx-x'></i>
        </div>
    </header>

    <!-- Slideshow -->
    <div class="slideshow-container">
        <div class="slide fade">
            <img src="images/slide1.jpg" class="slide-img">
            <div class="slide-text">Experience luxury like never before</div>
        </div>
        <div class="slide fade">
            <img src="images/slide8.jpg" class="slide-img">
            <div class="slide-text">Relax and unwind in our elegant rooms</div>
        </div>
        <div class="slide fade">
            <img src="images/slide3.jpg" class="slide-img">
            <div class="slide-text">World-class dining at your service</div>
        </div>
    </div>

    <!-- Description Section -->
    <div class="container">
        <article class="description">
            <section>
             <h2>
                <p>Welcome to <strong>Café Crepe</strong>, where flavors come alive, and every dish tells a story. More than just a place to eat, we are a space where tradition meets innovation, and every visit feels like a celebration. Whether you’re stopping by for a quick bite or looking for a comforting meal, our café invites you into a world of rich aromas, bold flavors, and unforgettable dining experiences.</p>
                </h2>
            </section>
<br>
<br>
<br>

            <section>
                <h3>A Journey Through Taste and Tradition:</h3> <br>
                <p><strong>Café Crepe</strong> was born from a simple idea—good food brings people together. Inspired by the bustling streets, vibrant markets, and the deep culinary heritage of our land, we have curated a menu that reflects both nostalgia and creativity. Every dish we serve carries a legacy of flavors, passed down through generations and reimagined with a modern twist.We believe that food is more than just sustenance; it is a story waiting to be told. From the sizzling spices that awaken your senses to the delicate sweetness that lingers on your palate, every bite at <strong>Café Crepe</strong> is an experience in itself.</p>
            </section>
<hr>
<br>
<br>
            <section>
                <h3>A Warm Welcome, Every Time:</h3> <br>
                Step inside <strong>Café Crepe</strong>, and you’ll feel an instant sense of belonging. The inviting aroma of freshly prepared meals, the soft hum of laughter and conversation, and the warm, earthy interiors all create an atmosphere that feels like home. Whether you’re catching up with an old friend over a steaming cup of coffee or enjoying a quiet meal by yourself, our café is designed for comfort and connection. We take pride in the little details—handpicked ingredients, carefully curated flavors, and the love that goes into every plate we serve. The joy of a perfect meal isn’t just about taste; it’s about the experience, the company, and the memories you create around the table.</p>
            </section>
<hr>
<br>
<br>
            <section>
                <h3>Where Every Bite Tells a Story:</h3> <br>
                <p>At <strong>Café Crepe</strong>, we don’t just serve food—we craft moments. Imagine the warmth of a dish that reminds you of childhood, the thrill of discovering a new favorite flavor, or the simple happiness of sharing a plate with someone you love. Our chefs pour their passion into every dish, blending traditional recipes with fresh ideas to create something truly special. Some flavors bring comfort, some bring excitement, but all of them bring people together. Whether it’s the perfect balance of spices, the comforting aroma of slow-cooked delicacies, or the indulgent sweetness of a well-made dessert, we believe that food has the power to evoke emotions and create lasting memories.</p>
            </section>
<hr>
<br>
<br>
            <section>
                <h3>More Than a Café, a Community:</h3> <br>
                <p><strong>Café Crepe</strong> isn’t just about great food; it’s about bringing people together. It’s about the joy of sharing, the pleasure of discovery, and the love that goes into every meal. We are more than just a café—we are a community where stories unfold over plates of delicious food, where strangers become friends, and where every meal feels like a celebration. So, whether you’re here for a hearty meal, a comforting sip of something warm, or a sweet treat to brighten your day, we welcome you with open arms. Because at <strong>Café Crepe</strong>, every visit is a special occasion.</p>
            </section>
        </article>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; Santrapti Nayak.</p>
        <div class="social-icons">
            <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="script.js"></script>
    <script src="front.js"></script>

</body>
</html>
