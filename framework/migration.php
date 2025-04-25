<?php

abstract class Migration {
    function up() {
    }

    function down() {
    }
}

class MigrationModel {
    public function __construct(
        public string $class,
        public DateTime $executedAt
    ) {}
}

?>