<?php

abstract class Migration {
    abstract function up();
    abstract function down();
}

class MigrationModel {
    function __construct(
        public string $class,
        public DateTime $executedAt
    ) {}
}

?>