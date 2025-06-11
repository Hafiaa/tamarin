<?php

$dsn = 'mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname=tamarindeplu;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Checking reservations table structure...\n";
    
    // Get columns information
    $stmt = $pdo->query("DESCRIBE reservations");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Reservations table columns:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} | Type: {$column['Type']} | Null: {$column['Null']} | Key: {$column['Key']} | Default: {$column['Default']} | Extra: {$column['Extra']}\n";
    }
    
    // Check some sample data
    echo "\n🔍 Sample reservation records (first 5):\n";
    $stmt = $pdo->query("SELECT id, user_id, event_type_id, status, total_price, event_date, event_time, end_time FROM reservations ORDER BY id DESC LIMIT 5");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($reservations)) {
        echo "No reservation records found.\n";
    } else {
        foreach ($reservations as $reservation) {
            echo "- ID: {$reservation['id']}, User ID: {$reservation['user_id']}, Status: {$reservation['status']}, Total: {$reservation['total_price']}, Date: {$reservation['event_date']} at {$reservation['event_time']}";
            if (!empty($reservation['end_time'])) {
                echo " to {$reservation['end_time']}";
            }
            echo "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
