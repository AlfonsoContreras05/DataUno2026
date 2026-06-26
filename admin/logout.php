<?php
require_once __DIR__ . '/../includes/auth.php';
datauno_logout();
header('Location: login.php');
exit;
