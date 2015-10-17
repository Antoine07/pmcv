<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();
/**
 * Library
 */
$database = require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/library/connect.php';
