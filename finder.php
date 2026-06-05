<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fragrance Finder - ScentAura</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="components/css/index.css">
    <link rel="stylesheet" href="components/css/finder.css">
</head>
<body>

    <nav>
        <div class="logo">
            <a href="index.php"><img src="components/logo/logo.png" alt="ScentAura"></a>
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <div class="finder-container">
        <div class="quiz-header">
            <h1>Discover Your Signature Scent</h1>
            <p>Answer 3 quick questions and let our concierge find your perfect match.</p>
            <div class="progress-bar">
                <div class="progress" id="progress"></div>
            </div>
        </div>

        <form id="quizForm" action="products.php" method="GET">
            <!-- Question 1 -->
            <div class="question-step active" id="step1">
                <h2>1. Which season feels most like "you"?</h2>
                <div class="options-grid">
                    <label class="quiz-option">
                        <input type="radio" name="scent" value="fresh">
                        <i class="fas fa-leaf"></i>
                        <span>Spring (Fresh & Light)</span>
                    </label>
                    <label class="quiz-option">
                        <input type="radio" name="scent" value="citrus">
                        <i class="fas fa-sun"></i>
                        <span>Summer (Citrus & Bright)</span>
                    </label>
                    <label class="quiz-option">
                        <input type="radio" name="scent" value="woody">
                        <i class="fas fa-tree"></i>
                        <span>Autumn (Woody & Earthy)</span>
                    </label>
                    <label class="quiz-option">
                        <input type="radio" name="scent" value="oriental">
                        <i class="fas fa-snowflake"></i>
                        <span>Winter (Oriental & Warm)</span>
                    </label>
                </div>
                <button type="button" class="next-btn" onclick="nextStep(2)">Next Question <i class="fas fa-arrow-right"></i></button>
            </div>

            <!-- Question 2 -->
            <div class="question-step" id="step2">
                <h2>2. What's the primary occasion?</h2>
                <div class="options-grid">
                    <label class="quiz-option">
                        <input type="radio" name="occasion" value="daily">
                        <i class="fas fa-briefcase"></i>
                        <span>Daily Office Wear</span>
                    </label>
                    <label class="quiz-option">
                        <input type="radio" name="occasion" value="special">
                        <i class="fas fa-glass-cheers"></i>
                        <span>Special Event</span>
                    </label>
                    <label class="quiz-option">
                        <input type="radio" name="occasion" value="casual">
                        <i class="fas fa-coffee"></i>
                        <span>Casual Weekend</span>
                    </label>
                </div>
                <div class="btn-group">
                    <button type="button" class="back-btn" onclick="prevStep(1)"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="next-btn" onclick="nextStep(3)">Next Question <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="question-step" id="step3">
                <h2>3. Who are we shopping for?</h2>
                <div class="options-grid">
                    <label class="quiz-option">
                        <input type="radio" name="gender" value="female">
                        <i class="fas fa-female"></i>
                        <span>Women's</span>
                    </label>
                    <label class="quiz-option">
                        <input type="radio" name="gender" value="male">
                        <i class="fas fa-male"></i>
                        <span>Men's</span>
                    </label>
                    <label class="quiz-option">
                        <input type="radio" name="gender" value="unisex">
                        <i class="fas fa-venus-mars"></i>
                        <span>Unisex</span>
                    </label>
                </div>
                <div class="btn-group">
                    <button type="button" class="back-btn" onclick="prevStep(2)"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="submit" class="submit-btn">Reveal My Scent <i class="fas fa-magic"></i></button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateProgress(step) {
            const progress = document.getElementById('progress');
            progress.style.width = ((step - 1) / 2 * 100) + '%';
        }

        function nextStep(step) {
            document.querySelectorAll('.question-step').forEach(el => el.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');
            updateProgress(step);
        }

        function prevStep(step) {
            document.querySelectorAll('.question-step').forEach(el => el.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');
            updateProgress(step);
        }
    </script>
</body>
</html>
