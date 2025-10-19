<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Portfolio Project</title>
    <link rel="stylesheet" href="../assets/styles/styles.css">
</head>
<body>
    <?php 
        $activePage = 'about';
        $basePath = '../';
        include '../views/nav.php'; 
    ?>

    <div class="api-container">
        <main class="api-content">
            <h2>About This Project</h2>
            
            <div class="about-section">
                <h3>Project Description</h3>
                <p>
                    This is Assignment 1 from COMP 3512. The project is a  PHP based web app that displays stock information for user portfolios.
                </p>

                <h3>Technologies Used</h3>
                <ul>
                    <li><strong>PHP 8.4</strong></li>
                    <li><strong>SQLite</strong></li>
                    <li><strong>PDO</strong></li>
                    <li><strong>HTML5</strong></li>
                    <li><strong>CSS3</strong></li>
                    <li><strong>JSON</strong></li>
                </ul>

                <p class="note">⚠️ I was going to use tailwind as I got used to it for work but I guess we should be demonstrating base css knowledge anyways.</p>

                <h3>Features</h3>
                <ul>
                    <li>View all customer portfolios</li>
                    <li>Display portfolio summary</li>
                    <li>Show stock prices</li>
                    <li>Show company information</li>
                </ul>

                <h3>Dev Information</h3>
                <p><strong>Name:</strong> Brian Nguyen</p>
                <p><strong>Course:</strong> COMP 3512</p>
                <p><strong>University:</strong> Mount Royal University</p>
                <p><strong>Assignment:</strong> Assignment 1</p>
                <p><strong>Date:</strong> October 19 2025</p>

                <h3>GitHub Repo</h3>
                <p>
                    <strong>Repository URL:</strong> 
                    <a href="https://github.com/telga/comp3512-assignment-1" target="_blank" class="api-link">
                        https://github.com/telga/comp3512-assignment-1
                    </a>
                </p>
            </div>
        </main>
    </div>
</body>
</html>