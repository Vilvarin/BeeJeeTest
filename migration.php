<?php

$db = mysqli_connect('localhost', 'bjtest', 'bjtest', 'bjtest');

if ($db->connect_error) {
    die($db->connect_error);
}

$result = $db->query("
    CREATE OR REPLACE TABLE todo_items (
        id BIGINT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        status BOOL NOT NULL DEFAULT false,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    );
");

if ($result) {
    echo "Query complete\n";
} else {
    echo "Query was not execute\n";
}
