<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

$connectionString = "mongodb+srv://alenroy:alenroy@cluster0.f6moj.mongodb.net/?retryWrites=true&w=majority&authSource=admin&appName=Cluster0";

// Add options array to explicitly enable TLS and allow invalid certificates for testing
$options = [
    'tls' => true,
    'tlsAllowInvalidCertificates' => true, // Remove this in production
    // 'tlsCAFile' => '/path/to/ca-certificate.crt', // Uncomment and set path if needed
];

$client = new Client($connectionString, $options);
$db = $client->selectDatabase('leave_management_system');

// Collections will be created automatically on first insert, so no need to list or create collections explicitly.
?>
