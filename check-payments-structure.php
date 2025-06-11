<?php

$dsn = 'mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname=tamarindeplu;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Checking payments table structure...\n";
    
    // Get columns information
    $stmt = $pdo->query("DESCRIBE payments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Payments table columns:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} | Type: {$column['Type']} | Null: {$column['Null']} | Key: {$column['Key']} | Default: {$column['Default']} | Extra: {$column['Extra']}\n";
    }
    
    // Check some sample data
    echo "\n🔍 Sample payment records (first 5):\n";
    $stmt = $pdo->query("SELECT * FROM payments ORDER BY id DESC LIMIT 5");
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($payments)) {
        echo "No payment records found.\n";
    } else {
        foreach ($payments as $payment) {
            echo "- ID: {$payment['id']}, Reservation ID: {$payment['reservation_id']}, Amount: {$payment['amount']}, Status: {$payment['status']}\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
