<?php

namespace Framework;

use DateTime;

class MigrationDBModel {
    function __construct(
        public string $class,
        public DateTime $executedAt
    ) {}
}