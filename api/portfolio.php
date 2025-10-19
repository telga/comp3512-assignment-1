<?php
require_once '../includes/config.inc.php';

header(header: 'Content-Type: application/json');

$connection = getDbConnection();

//ternary for if there is a id specified or not.
$userID = isset($_GET['ref']) ? $_GET['ref'] : null;

//portfolios will need a ref or else nothing. private info.
if (!$userID) {
    http_response_code(response_code:400);
    echo json_encode(value: ['error' => 'User ID (ref) is required']);
    exit;
}

try {
    //get portfol;io entries for ref user with company details.
    //join companies ti get sector info with name.
    $query = "
        SELECT
            p.id,
            p.userID,
            p.symbol,
            p.amount,
            c.name as company_name,
            c.sector
        FROM portfolio p
        INNER JOIN companies c ON p.symbol = c.symbol
        WHERE p.userID = ?
        ORDER BY c.name
    ";
    $result = getQuery(pdo: $connection, query: $query, params: ([$userID]));

    if ($result) {
        echo json_encode(value: $result);
    } else {
        echo json_encode(value: []); // eh just incase we use this later so we wont get nulls.
    }
} catch (Exception $e) {
    http_response_code(response_code: 500);
    echo json_encode(value: ['error' => $e->getMessage()]);
}