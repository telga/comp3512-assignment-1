<?php
require_once '../includes/config.inc.php';

header(header: 'Content-Type: application/json');

$connection = getDbConnection();

//ternary for if there is a symbol specified or not.
$symbol = isset($_GET['ref']) ? $_GET['ref'] : null;

try {
    if ($symbol) {
        //specific company per symbol if provided
        $query = "
            SELECT *
            FROM history
            WHERE symbol = ?
            ORDER BY date ASC
        ";

        $result = getQuery(pdo: $connection, query: $query, params: [strtoupper(string: $symbol)]);
        
        if ($result) {
            echo json_encode(value: $result);
        } else {
            http_response_code(response_code: 404);
            echo json_encode(value: ['error' => 'Company not found']);
        }
    } else {
        // no ref shows all
        $query = "
            SELECT *
            FROM history
            ORDER BY date ASC
        ";
        $result = getQuery(pdo: $connection, query: $query);

        echo json_encode(value: $result);
    }
} catch (Exception $e) {
    http_response_code(response_code: 500);
    echo json_encode(value: ['error' => $e->getMessage()]);
}