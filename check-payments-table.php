<?php

$dsn = 'mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname=tamarindeplu;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Checking payments table structure...\n\n";
    
    // Get table structure
    $stmt = $pdo->query('DESCRIBE payments');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Payments Table Structure:\n";
    echo str_repeat("-", 100) . "\n";
    printf("%-20s | %-15s | %-10s | %-10s | %-20s\n", 
           'Field', 'Type', 'Null', 'Key', 'Extra');
    echo str_repeat("-", 100) . "\n";
    
    foreach ($columns as $column) {
        printf("%-20s | %-15s | %-10s | %-10s | %-20s\n", 
               $column['Field'], 
               $column['Type'], 
               $column['Null'], 
               $column['Key'], 
               $column['Extra']);
    }
    
    echo "\n✅ Payments table check completed!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
