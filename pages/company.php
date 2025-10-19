<?php

require_once '../includes/config.inc.php';

//Db connection.
$connection = getDbConnection();

$symbol = isset($_GET['symbol']) ? strtoupper(string: $_GET['symbol']) : null;

if (!$symbol) {
    header(header: 'Location: ../index.php');
    exit;
}

$companyQuery = "SELECT * FROM companies WHERE symbol = ?";
$company = getQuerySingle(pdo: $connection, query: $companyQuery, params: [$symbol]);

if (!$company) {
    die("Company not found");
}

$historyQuery = "
    SELECT *
    FROM history
    WHERE symbol = ?
    ORDER BY date DESC
";

$history = getquery(pdo: $connection, query: $historyQuery, params: [$symbol]);

//calculation for high, low, total volume, and average volume.
$historyHigh = 0;
$historyLow = 9999999999999999; //use high val as it will be replaced later with real low.
$totVol = 0; 
$historyCount = count(value: $history); //used for avg.

foreach ($history as $entry) {
    $high = floatval(value: $entry['high']);
    $low = floatval(value: $entry['low']);
    $volume = intval(value: $entry['volume']);

    if ($high > $historyHigh) $historyHigh = $high; //itll replace this val with highest always.
    if ($low < $historyLow) $historyLow = $low; //same here ut for low.
    $totVol += $volume; //keeps adding volume of shares for this symbol.
}

$avgVol = $historyCount > 0 ? $totVol / $historyCount : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(string: $company['name']); ?> - Portfolio Project</title>
    <link rel="stylesheet" href="../assets/styles/styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Portfolio Project</h1>
            <nav>
                <a href="../index.php" class="nav-btn">Home</a>
                <a href="about.php" class="nav-btn">About</a>
                <a href="api-test.php" class="nav-btn">APIs</a>
            </nav>
        </div>
    </header>

    <div class="company-container">
        <main class="company-main">

            <!-- info section of company -->
            <section class="company-info">
                <h2>Company Information</h2>

                <div class="company-header">
                    <div class="company-title">
                        <h3><?php echo htmlspecialchars(string: $company['name']); ?></h3>
                        <span class="company-symbol"><?php echo htmlspecialchars(string: $company['symbol']); ?></span>
                    </div>

                    <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Sector:</span>
                        <span class="info-val"><?php echo htmlspecialchars(string: $company['sector']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Sub-Industry:</span>
                        <span class="info-val"><?php echo htmlspecialchars(string: $company['subindustry']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Address:</span>
                        <span class="info-val"><?php echo htmlspecialchars(string: $company['address']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Exchange:</span>
                        <span class="info-val"><?php echo htmlspecialchars(string: $company['exchange']); ?></span>
                    </div>
                    <?php if (!empty($company['website'])): ?>
                    <div class="info-item">
                        <span class="info-label">Website:</span>
                        <span class="info-val">
                            <a href="<?php echo htmlspecialchars(string: $company['website']); ?>" target="_blank" rel="noopener noreferrer">
                                <?php echo htmlspecialchars(string: $company['website']); ?>
                            </a>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($company['description'])): ?>
                <div class="company-description">
                    <h4>About</h4>
                    <p><?php echo htmlspecialchars(string: $company['description']); ?></p>
                </div>
                <?php endif; ?>
            </section>
            
            <!-- 3 month history -->
            <section class="company-history">
                <h2>History (3M)</h2>
                <div class="history-t-wrapper">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Volume</th>
                                <th>Open</th>
                                <th>Close</th>
                                <th>High</th>
                                <th>Low</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(string: $record['date']); ?></td>
                                <td><?php echo number_format(num: $record['volume']); ?></td>
                                <td><?php echo '$' . number_format(num: $record['open'], decimals: 2); ?></td>
                                <td><?php echo '$' . number_format(num: $record['close'], decimals: 2); ?></td>
                                <td><?php echo '$' . number_format(num: $record['high'], decimals: 2); ?></td>
                                <td><?php echo '$' . number_format(num: $record['low'], decimals: 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <aside class="company-side">
            <div class="metric-card">
                <div class="metric-label">History High</div>
                <div class="metric-value">$<?php echo number_format(num: $historyHigh, decimals: 2); ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">History Low</div>
                <div class="metric-value">$<?php echo number_format(num: $historyLow, decimals: 2); ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Total Volume</div>
                <div class="metric-value"><?php echo number_format(num: $totVol); ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Average Volume</div>
                <div class="metric-value"><?php echo number_format(num: $avgVol, decimals: 0); ?></div>
            </div>
        </aside>
    </div>
</body>
</html>
