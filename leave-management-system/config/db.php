<?php
require_once __DIR__ . '/../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb+srv://alenroy1001:alenroy1001@cluster0.f6moj.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
$db = $mongoClient->leave_management_system;

session_start();
?>