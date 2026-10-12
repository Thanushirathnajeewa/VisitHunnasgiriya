<?php
// Logs out both admins and visitors.
require __DIR__ . '/includes/functions.php';
$_SESSION = [];
session_destroy();
header('Location: index.php'); exit;
