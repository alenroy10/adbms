<?php
require_once __DIR__ . '/config/db.php';

session_start();
session_destroy();
header("Location: index.php");
exit;
?>