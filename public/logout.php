<?php
require __DIR__ . '/../src/Bootstrap.php';

session_destroy();

header('Location: login.php');
exit;

