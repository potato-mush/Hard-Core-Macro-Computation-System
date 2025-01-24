<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="how-it-works.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>How It Works!</title>
</head>

<body>

    <!-- Include the navbar at the top -->
    <?php include 'navbar.php' ?>

    <!-- Container wrapping the carousel -->
    <div class="container">

        <!-- Heading above the carousel -->
        <p class="carousel-heading" id="tutorial-description">Macro Calculator: Input your personal details (age, gender, activity level, etc.) to calculate your ideal daily macronutrient breakdown (proteins, fats, and carbs) tailored to your fitness goals—whether it's weight loss, muscle gain, or maintenance.</p>

        <!-- Left and Right Arrows -->
        <div class="arrow arrow-left" id="prevBtn">&#10094;</div>
        <div class="arrow arrow-right" id="nextBtn">&#10095;</div>

        <!-- Carousel Container -->
        <div class="carousel-container">
            <div class="carousel-wrapper" id="carouselWrapper">
                <div class="carousel-item"><img src="images/screenshots/image1.jpg" alt="Image 1"></div>
                <div class="carousel-item"><img src="images/screenshots/image2.jpg" alt="Image 2"></div>
                <div class="carousel-item"><img src="images/screenshots/image3.jpg" alt="Image 3"></div>
                <div class="carousel-item"><img src="images/screenshots/image4.jpg" alt="Image 4"></div>
            </div>
        </div>

        <!-- Pagination Dots -->
        <div class="pagination" id="pagination"></div>

    </div>

    <script>
        const carouselWrapper = document.getElementById('carouselWrapper');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const pagination = document.getElementById('pagination');
        const description = document.getElementById('tutorial-description');
        let currentIndex = 0;

        // Define tutorials
        const tutorials = [{
                description: "Macro Calculator: Input your personal details (age, gender, activity level, etc.) to calculate your ideal daily macronutrient breakdown (proteins, fats, and carbs) tailored to your fitness goals—whether it's weight loss, muscle gain, or maintenance.",
                images: ["images/screenshots/image1.jpg", "images/screenshots/image2.jpg", "images/screenshots/image3.jpg", "images/screenshots/image4.jpg"]
            },
            {
                description: "Meal Plan Generator: Based on your calculated macros, the system provides a customized meal plan with suggested foods and portion sizes. You can adjust your meal plan as needed to fit dietary preferences, allergies, or specific nutritional needs.",
                images: ["images/screenshots/image5.jpg", "images/screenshots/image6.jpg", "images/screenshots/image7.jpg", "images/screenshots/image8.jpg"]
            }
        ];

        let currentTutorialIndex = 0;
        let totalItems = tutorials[currentTutorialIndex].images.length;

        function updatePagination() {
            pagination.innerHTML = '';
            const totalPages = Math.ceil(totalItems / 2);
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('span');
                dot.addEventListener('click', () => goToSlide(i));
                if (i === currentIndex / 2) dot.classList.add('active');
                pagination.appendChild(dot);
            }
        }

        function goToSlide(index) {
            currentIndex = index * 2;
            carouselWrapper.style.transform = `translateX(-${currentIndex * 50}%)`;
            updatePagination();
        }

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex -= 2;
                carouselWrapper.style.transform = `translateX(-${currentIndex * 50}%)`;
                updatePagination();
            }
        });

        nextBtn.addEventListener('click', goToNextTutorial);

        function goToNextTutorial() {
            // Disable the transition temporarily when changing the tutorial
            carouselWrapper.style.transition = 'none';

            // Change tutorial index
            currentTutorialIndex = (currentTutorialIndex + 1) % tutorials.length;
            currentIndex = 0; // Reset the slide index to 0 for the first slide of the new tutorial
            updateCarousel(); // Update the carousel content

            // Re-enable the transition after updating the carousel
            setTimeout(() => {
                carouselWrapper.style.transition = 'transform 0.5s ease';
                carouselWrapper.style.transform = `translateX(-${currentIndex * 50}%)`; // Ensure carousel starts at the first slide
            }, 50); // Small delay to ensure the transition is re-applied
        }

        function updateCarousel() {
            const tutorial = tutorials[currentTutorialIndex];
            description.textContent = tutorial.description;

            // Reset the images and pagination
            const imagesHTML = tutorial.images.map(image => `<div class="carousel-item"><img src="${image}" alt="Image"></div>`).join('');
            carouselWrapper.innerHTML = imagesHTML;

            // Update total items and reset pagination
            totalItems = tutorial.images.length;
            updatePagination();
        }

        // Auto slide every 3 seconds
        setInterval(() => {
            if (currentIndex < totalItems - 2) {
                currentIndex += 2;
            } else {
                currentIndex = 0;
            }
            carouselWrapper.style.transform = `translateX(-${currentIndex * 50}%)`;
            updatePagination();
        }, 3000);

        // Initialize
        updateCarousel();
    </script>

</body>

</html>