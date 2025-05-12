<?php

namespace Framework;

use Framework\App;
use Migration;
use MigrationDBModel;
use DateTime;

class Migrator {
    /** @var string[] */
    private array $migrations_classes;

    /** 
     * @param string[] $migration_classes
    */
    function __construct($migration_classes) {
        $this->migrations_classes = $migration_classes;

        $db = App::$si->db;

        $result = $db->Query("SELECT name FROM sqlite_master WHERE type='table' AND name='migrations';");

        if ($result->fetchArray(SQLITE3_ASSOC)===false) {
            $db->Exec('CREATE TABLE migrations (
                class TEXT PRIMARY KEY,
                executedAt TEXT NOT NULL
            ) WITHOUT ROWID');
        }
    }

    function up() {
        $db = App::$si->db;

        /** @var MigrationDBModel[] */
        $ran_migrations  = [];

        $result = $db->Query("SELECT * FROM migrations");
        while ($row = $result->fetchArray(SQLITE3_NUM)) {
            $ran_migrations[] = new MigrationDBModel($row[0], new DateTime($row[1]));
        }

        foreach ($this->migrations_classes as $migration_class) {
            $migration_ran = false;

            foreach ($ran_migrations as $ran_migration) {
                if ($ran_migration->class===$migration_class) {
                    $migration_ran = true;
                    break;
                }
            }

            if ($migration_ran)
                continue;

            $db->Exec("BEGIN TRANSACTION");
            
            /** @var Migration */
            $inst = new $migration_class();
            $inst->up();
            echo 'Executed migration ' . $migration_class . "\n\r";
            $db->Exec("INSERT INTO migrations VALUES ('{$migration_class}', datetime())");
            $db->Exec("COMMIT");
        }
    }

    function down() {

    }
}

?>