<?php

$dsn = 'mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname=tamarindeplu;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Checking media table structure...\n";
    
    // Get columns information
    $stmt = $pdo->query("DESCRIBE media");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Media table columns:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} | Type: {$column['Type']} | Null: {$column['Null']} | Key: {$column['Key']} | Default: {$column['Default']} | Extra: {$column['Extra']}\n";
    }
    
    // Check some sample data
    echo "\n🔍 Sample media records (first 5):\n";
    $stmt = $pdo->query("SELECT * FROM media ORDER BY id DESC LIMIT 5");
    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($media)) {
        echo "No media records found.\n";
    } else {
        foreach ($media as $item) {
            echo "- ID: {$item['id']}, Model Type: {$item['model_type']}, Model ID: {$item['model_id']}, Collection: {$item['collection_name']}, File: {$item['file_name']}, Size: {$item['size']} bytes\n";
            echo "  Path: {$item['disk']}/{$item['directory']}/{$item['file_name']}\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
