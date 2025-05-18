<?php

$dbDir = __DIR__ . '/db';
$dbFile = $dbDir . '/database.sqlite';

//checking directory..
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0777, true);
}

$db = new SQLite3($dbFile);

$db->exec("
    CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        price INTEGER NOT NULL,
        date TEXT NOT NULL
    );
");

echo "The order table has been added successfully!";