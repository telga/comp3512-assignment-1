<?php 
require_once 'includes/config.inc.php';

//Db connection.
$connection = getDbConnection();

//Fetchall users sort by lname.
$usersQuery = "SELECT id, firstname, lastname FROM users ORDER BY lastname, firstname";
$users = getQuery(pdo: $connection, query: $usersQuery);

//Check for id.
$selectedID = isset($_GET['id']) ? $_GET['id'] : null;
$portData = null;
$portDetails = [];
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

    //portfolio detakls using closing price latest from history table.
    $detailsQuery = "
    SELECT 
            p.symbol,
            c.name,
            c.sector,
            p.amount,
            (SELECT close FROM history WHERE symbol = p.symbol ORDER BY date DESC LIMIT 1) as latest_close
        FROM portfolio p
        INNER JOIN companies c ON p.symbol = c.symbol
        WHERE p.userId = ?
        ORDER BY c.name
    ";
    $portDetails = getQuery(pdo: $connection, query: $detailsQuery, params: [$selectedID]);

    //calculate total value.
    $totalValue = 0;

    //not using key and using as detail will create a double on the last entry of the data set.
    foreach ($portDetails as $key => $detail) {
        $portDetails[$key]['value'] = $detail['amount'] * $detail['latest_close'];
        $totalValue += $portDetails[$key]['value'];
    }

    $portData = [
        'companies' => $companiesCount,
        'shares' => $totalShares,
        'totalValue' => $totalValue
    ];
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
                            <?php echo htmlspecialchars(string: $user['lastname'] . ', ' . $user['firstname']); ?>   <!-- xss prevention in name echo -->
                        </span>
                        <a href="index.php?id=<?php echo $user['id']; ?>" class="port-btn">Portfolio</a>    
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
        
        <main class="port-main">
            <?php if (!$selectedID): ?>
                <div class="empty-state">
                    <p>Select a customer's portfolio from the list on the left.</p>
                </div>
            <?php else: ?>
                <?php if ($selectedUser): ?>
                    <h2 class="port-title">
                        <?php echo htmlspecialchars(string: $selectedUser['firstname'] . ' ' . $selectedUser['lastname']) . '\'s Portfolio Summary'; ?>
                    </h2>
                <?php endif; ?>

                <!-- portfolio summary -->
                <section class="port-summary">
                    <h3>Portfolio Summary</h3>
                    <div class="summary-cards">
                        <div class="summary-card">
                            <h4>Companies</h4>
                            <div class="card-value"><?php echo $portData['companies']; ?></div>
                        </div>
                        <div class="summary-card">
                            <h4># Shares</h4>
                            <div class="card-value"><?php echo number_format(num: $portData['shares']); ?></div>
                        </div>
                        <div class="summary-card">
                            <h4>Total Value</h4>
                            <div class="card-value">$<?php echo number_format(num: $portData['totalValue'], decimals: 2); ?></div>
                        </div>
                    </div>
                </section>

                <!-- portfolio details - dad helped me work out tables-->
                <section class="port-details">
                    <h3>Portfolio Details</h3>
                    <table class="port-table">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Name</th>
                                <th>Sector</th>
                                <th>Amount</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($portDetails as $detail): ?>
                                <tr>
                                    <td>
                                        <a href="pages/company.php?symbol=<?php echo htmlspecialchars(string: $detail['symbol']); ?>" class="symbol-link">
                                            <?php echo htmlspecialchars(string: strtoupper(string: $detail['symbol'])); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars(string: $detail['name']); ?></td>
                                    <td><?php echo htmlspecialchars(string: $detail['sector']); ?></td>
                                    <td><?php echo number_format(num: $detail['amount']); ?></td>
                                    <td><?php echo '$' .number_format(num: $detail['value'], decimals: 2); ?></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </section>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>