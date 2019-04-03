<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

$db = new PDO(
    'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_NAME') . ';charset=utf8',
    env('DB_USER'),
    env('DB_PASS')
);
