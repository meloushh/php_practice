<?php

namespace Framework;

abstract class Migration {
    abstract function up();
    abstract function down();
}

?>