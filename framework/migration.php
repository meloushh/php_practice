<?php

namespace Framework;

use DateTime;

abstract class Migration {
    abstract function up();
    abstract function down();
}

class MigrationDBModel {
    function __construct(
        public string $class,
        public DateTime $executedAt
    ) {}
}

?>