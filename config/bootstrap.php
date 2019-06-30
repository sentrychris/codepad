<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();
error_reporting(E_ALL & ~E_NOTICE);

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();
