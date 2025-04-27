<?php

require_once 'App.php';
require_once 'migration.php';


class Migrator {
    /** @var string[] */
    private array $migrationClasses;

    /** 
     * @param string[] $migrationClasses
    */
    function __construct($migrationClasses) {
        $this->migrationClasses = $migrationClasses;

        $db = App::$inst->db;

        $result = $db->Query("SELECT name FROM sqlite_master WHERE type='table' AND name='migrations';");

        if ($result->fetchArray(SQLITE3_ASSOC) == false) {
            $db->Exec('CREATE TABLE migrations (
                class TEXT PRIMARY KEY,
                executedAt TEXT NOT NULL
            ) WITHOUT ROWID');
        }
    }

    function up() {
        $db = App::$inst->db;

        /** @var MigrationModel[] */
        $ranMigrations  = [];

        $result = $db->Query("SELECT * FROM migrations");
        while ($row = $result->fetchArray(SQLITE3_NUM)) {
            $ranMigrations[] = new MigrationModel($row[0], new DateTime($row[1]));
        }

        foreach ($this->migrationClasses as $migrationClass) {
            foreach ($ranMigrations as $ranMigration) {
                if ($ranMigration->class == $migrationClass) {
                    continue;
                }
            }

            $db->Exec("BEGIN TRANSACTION");
            /** @var Migration */
            $inst = new $migrationClass();
            $inst->up();
            echo 'Executed migration ' . $migrationClass;
            $db->Exec("INSERT INTO migrations VALUES ('{$migrationClass}', datetime())");
            $db->Exec("COMMIT");
        }
    }

    function down() {

    }
}

?>