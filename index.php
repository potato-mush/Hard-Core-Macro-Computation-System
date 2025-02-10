<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Website</title>
    <link rel="stylesheet" href="assets/styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <!-- Home Section -->
    <section id="home-section">
        <?php 
        include 'navbar.php';
        ?>

        <div class="social-icons-container">
            <div class="line"></div>
            <a href="https://facebook.com" target="_blank" class="social-icon facebook-icon">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="social-icon instagram-icon">
                <i class="fab fa-instagram"></i>
            </a>
            <div class="line"></div>
        </div>

        <div class="home-content">
            <h1>HARD CORE</h1>
            <h3>MACRO COMPUTATION SYSTEM</h3>
            <p>Unlock your fitness potential with our innovative Hard Macro Computation System! Designed for gym enthusiasts of all levels, our platform takes the guesswork out of nutrition, helping you accurately calculate and track your macros. With personalized guidance and easy-to-use tools, you’ll maximize your gains and achieve your goals more efficiently than ever. Transform your workout experience and fuel your success because every rep deserves the right nutrition!</p>
            <button class="cta-button">Calculate Now</button>
        </div>
    </section>

    <!-- Divider Section -->
    <div class="crossed-divider">
        <div class="tape">
            <span>Stay Strong > Fight On > Never Stop > Keep Moving Forward > Be Unstoppable</span>
        </div>
        <div class="tape">
            <span>Stay Strong > Fight On > Never Stop > Keep Moving Forward > Be Unstoppable</span>
        </div>
    </div>

    <!-- How It Works Section -->
    <section id="about-section" class="what-we-offer">
        <div class="content">
            <h2>What We Offer</h2>
            <p><strong>Personalized Macronutrient Calculation</strong><br>
                Our system uses a variety of factors – such as age, gender, weight, activity level, and goals – to calculate your optimal macronutrient breakdown. This ensures you're consuming the right amount of
            <ul>
                <li>Protein for muscle repair, growth, and recovery</li>
                <li>Carbohydrates for energy and performance during workouts</li>
                <li>Fats for overall health, hormone balance, and sustained energy</li>
            </ul>

            <p><strong>Comprehensive Goal Setting</strong><br>
                Whether you're trying to lose fat, gain muscle, or simply maintain a healthy weight, our system helps you define clear, actionable goals. Once your goals are set, we provide macronutrient recommendations based on the science of calorie intake, metabolism, and nutrient timing.</p>
        </div>
    </section>

    <!-- Divider Section -->
    <div class="crossed-divider">
        <div class="tape">
            <span>Stay Hard > Rise Up > Be Proud > Stay Humble > Never Give Up > Keep Pushing</span>
        </div>
        <div class="tape">
            <span>Stay Strong > Fight On > Never Stop > Keep Moving Forward > Be Unstoppable</span>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact-section" class="contact-section">
        <h2>Contact Us</h2>
        <div class="content">

            <!-- Left Side: Form -->
            <div id="contact-form">
                <form action="submit_contact.php" method="POST">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="12" required></textarea>
                    <button type="submit">Send</button>
                </form>
            </div>

            <!-- Right Side: Map and Info -->
            <div id="map-container">
                <div id="map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1919.4549443941341!2d120.4570855064731!3d15.808704773324987!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x339149225781f061%3A0x15d7558906d00996!2sHardcore%20Fitness%20Gym!5e0!3m2!1sen!2sph!4v1737004023451!5m2!1sen!2sph"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div id="address-hours">
                    <p>Quezon Blvd, Bayambang, 2423 Pangasinan</p>
                    <p>Open Mon-Sun, 7am-8pm</p>
                </div>
            </div>
        </div>
        <!-- Footer Section -->
        <footer>
            <p>&copy; 2024 Hard Core Gym Fittness Bayambang, Pangasinan.</p>
            <p>All rights reserved.</p>
            <p>For screen reader problems with this website, please call <a href="tel:+1234567890" style="color: white;">+63 9038503851</a> or<a href="mailto:info@gymwebsite.com" style="color: white;"> Email Us.</a></p>
        </footer>
    </div>


</body>

</html>