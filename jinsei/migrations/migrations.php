<?php

require_once BASEDIR.'/framework/Migration.php';

class MigrationOne extends Migration {
    function up() {
        $db = App::$inst->db;
        $db->Exec("CREATE TABLE documents (
            id INTEGER PRIMARY KEY,
            title TEXT,
            content TEXT
        )");
    }

    function down() {
        $db = App::$inst->db;
        $db->Exec("DROP TABLE documents");
    }
}

?>