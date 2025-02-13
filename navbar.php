<!-- Navbar -->
<div class="navbar" id="navbar">
    <?php
    include('functions/login.php');
    if (isLoggedIn()) {
        echo '<div id="profile" class="nav-item primary_button"><a href="profile.php">Profile</a></div>';
    } else {
        echo '<div id="join-us" class="nav-item primary_button"><a href="login.php">Join Us!</a></div>';
    }
    ?>
    <div id="home" class="nav-item"><a href="index.php#home-section">Home</a></div>
    <div id="about" class="nav-item"><a href="index.php#about-section">About</a></div>
    <div id="how-it-works" class="nav-item"><a href="how-it-works.php">How It Works</a></div>
    <?php
    if (isLoggedIn()) {
        echo '<script>console.log("User is logged in");</script>';
        echo '<div id="calculate" class="nav-item"><a href="calculate.php">Calculate</a></div>';
    } else {
        echo '<script>console.log("User is not logged in");</script>';
        echo '<div id="calculate" class="nav-item"><a href="login.php">Calculate</a></div>';
    }
    ?>
    
    <div id="contact" class="nav-item"><a href="index.php#contact-section">Contact</a></div>
    <div class="logo">
        <img src="assets/images/logo.png" alt="Logo" />
    </div>
</div>