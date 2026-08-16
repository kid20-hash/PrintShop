<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['fullname'] ?? 'Guest';

$log = sprintf(
    "[%s] %-6s %-30s User: %s | IP: %s\n",
    date('Y-m-d H:i:s'),
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
    $user,
    $_SERVER['REMOTE_ADDR']
);

file_put_contents(
    "C:/AppServ/Apache24/logs/user_access.log",
    $log,
    FILE_APPEND
);