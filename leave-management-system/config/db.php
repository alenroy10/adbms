<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

$connectionString = "mongodb+srv://alenroy:alenroy@cluster0.f6moj.mongodb.net/?retryWrites=true&w=majority&authSource=admin&appName=Cluster0";
$client = new Client($connectionString);
$db = $client->selectDatabase('leave_management_system');

// Collections will be created automatically on first insert, so no need to list or create collections explicitly.
?>
