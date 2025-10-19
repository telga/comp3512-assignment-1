<?php 
require_once 'includes/config.inc.php';

//Db connection.
$connection = getDbConnection();

//Fetchall users sort by lname.
$usersQuery = "SELECT id, firstname, lastname FROM users ORDER BY lastname, firstname";
$users = getQuery(pdo: $connection, query: $usersQuery);

//Check for id.
$selectedID = isset($_GET['id']) ? $_GET['id'] : null;
$portfolioData = null;
$portfolioDetails = [];
$selectedUser = null;

if ($selectedID) {
    //get user info from id.
    $userQuery = "SELECT firstname, lastname FROM users WHERE id = ?";
    $selectedUser = getQuerySingle(pdo: $connection, query: $userQuery, params: [$selectedID]);

    //get portfolio data.
    
    //Count of unique symbols for the user.
    $companiesQuery = "SELECT COUNT(DISTINCT symbol) as count FROM portfolio WHERE userId = ?";
    $companiesResult = getQuerySingle(pdo: $connection, query: $companiesQuery, params: [$selectedID]);
    $companiesCount = $companiesResult['count'];

    //Sum of all shares for the user.
    $sharesQuery = "SELECT SUM(amount) as total FROM portfolio WHERE userId = ?";
    $sharesResult = getQuerySingle(pdo: $connection, query: $sharesQuery, params: [$selectedID]);
    $totalShares = $sharesResult['total'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Project</title>
    <link rel="stylesheet" href="assets/styles/styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Portfolio Project</h1>
            <nav>
                <a href="index.php" class="nav-btn">Home</a>
                <a href="#" class="nav-btn">About</a>
                <a href="#" class="nav-btn">APIs</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <aside class="customers-left">
            <h2>Customers</h2>
            <ul class="customers-list">
                <?php foreach ($users as $user) : ?>
                    <li class="customer">
                        <span class="customer-name">
                            <?php echo htmlspecialchars($user['lastname'] . ', ' . $user['firstname']); ?>
                        </span>
                        <a href="index.php?id=<?php echo $user['id']; ?>" class="portfolio-btn">Portfolio</a>    
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
    </div>

    <main>

    </main>
    
</body>
</html>