<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Tester - Portfolio Project</title>
    <link rel="stylesheet" href="../assets/styles/styles.css">
</head>
<body>
    <?php 
        $activePage = 'apis';
        $basePath = '../';
        include '../views/nav.php'; 
    ?>
    
    <div class="api-container">
        <main class="api-content">
            <h2>API List</h2>
            <p class="api-description">Click on a link below to test the API endpoints. The response will be in JSON format.</p>
            
            <table class="api-table">
                <thead>
                    <tr>
                        <th>URL</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="../api/companies.php" target="_blank" class="api-link">/api/companies.php</a></td>
                        <td>Returns all the companies/stocks</td>
                    </tr>
                    <tr>
                        <td><a href="../api/companies.php?ref=ads" target="_blank" class="api-link">/api/companies.php?ref=ads</a></td>
                        <td>Return just a specific company/stock (ADS)</td>
                    </tr>
                    <tr>
                        <td><a href="../api/portfolio.php?ref=8" target="_blank" class="api-link">/api/portfolio.php?ref=8</a></td>
                        <td>Returns all the portfolios for a specific sample customer (User ID 8)</td>
                    </tr>
                    <tr>
                        <td><a href="../api/history.php?ref=ads" target="_blank" class="api-link">/api/history.php?ref=ads</a></td>
                        <td>Returns the history information for a specific sample company (ADS)</td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>