<?php
require_once __DIR__ . '/vendor/autoload.php';

use MongoDB\Client;

$connectionString = "mongodb+srv://alenroy:alenroy@cluster0.f6moj.mongodb.net/?retryWrites=true&w=majority&authSource=admin&appName=Cluster0";

$options = [
    'tls' => true,
    'tlsAllowInvalidCertificates' => true, // For testing only, remove in production
    // 'tlsCAFile' => '/path/to/ca-certificate.crt', // Uncomment and set if needed
];

try {
    $client = new Client($connectionString, $options);
    $db = $client->selectDatabase('leave_management_system');
    $collections = $db->listCollections();
    echo "Connection successful. Collections:" . PHP_EOL;
    foreach ($collections as $collection) {
        echo "- " . $collection->getName() . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
}
?>
