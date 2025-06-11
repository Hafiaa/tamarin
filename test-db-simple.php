<?php

$dsn = 'mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname=tamarindeplu;charset=utf8mb4';
$username = 'root';
$password = '';

function testConnection($dsn, $username, $password) {
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ Connected to database successfully!\n";
        
        // List all tables
        $stmt = $pdo->query('SHOW TABLES');
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "📋 Tables in database:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
        
        return true;
    } catch (PDOException $e) {
        echo "❌ Connection failed: " . $e->getMessage() . "\n";
        return false;
    }
}

// Test the connection
echo "🔍 Testing database connection...\n";
$connected = testConnection($dsn, $username, $password);

if ($connected) {
    echo "\n✅ Database connection test completed successfully!\n";
} else {
    echo "\n❌ Failed to connect to the database. Please check your configuration.\n";
}
