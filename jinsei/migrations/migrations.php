<?php

use Framework\App;
use Framework\Migration;

$migrations = [
    CreateUsers::class,
    CreateDocuments::class
];

class CreateUsers extends Migration {
    function up() {
        $db = App::$si->db;

        $db->Exec('CREATE TABLE users (
                id INTEGER PRIMARY KEY,
                email TEXT NOT NULL,
                password TEXT NOT NULL
            );
        ');
    }

    function down() {
        $db = App::$si->db;
        $db->Exec('DROP TABLE users;');
    }
}

class CreateDocuments extends Migration {
    function up() {
        $db = App::$si->db;

        $db->Exec('CREATE TABLE documents (
                id INTEGER PRIMARY KEY,
                title TEXT NOT NULL,
                content TEXT,
                user_id INTEGER NOT NULL,
            
                CONSTRAINT fk_users FOREIGN KEY (user_id) REFERENCES users (id)
            );
        ');
    }

    function down() {
        $db = App::$si->db;
        $db->Exec('DROP TABLE documents;');
    }
}

?>