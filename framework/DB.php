<?php

class DB {
    public SQLite3 $sqlite;

    function __construct(string $path) {
        $this->sqlite = new SQLite3($path);
    }

    function Exec(string $sql) {
        // Dump($sql);
        $success = $this->sqlite->exec($sql);
        if ($success === false) {
            throw new Exception($this->sqlite->lastErrorMsg());
        }
    }

    function Query(string $sql) {
        // Dump($sql);
        $result = $this->sqlite->query($sql);
        if ($result === false) {
            throw new Exception($this->sqlite->lastErrorMsg());
        }
        return $result;
    }

    function GetArgType($arg) {
        switch (gettype($arg)) {
            case 'double': return SQLITE3_FLOAT;
            case 'integer': return SQLITE3_INTEGER;
            case 'boolean': return SQLITE3_INTEGER;
            case 'NULL': return SQLITE3_NULL;
            case 'string': return SQLITE3_TEXT;
            default:
                throw new InvalidArgumentException('Argument is of invalid type '.gettype($arg));
        }
    }

    function Prepared(string $sql, array $params) {
        $statement = $this->sqlite->prepare($sql);
        $i = 1;
        foreach ($params as $key => $value) {
            $statement->bindValue($i, $value, $this->GetArgType($value));
            $i++;
        }
        $result = $statement->execute();
        if ($result === false) {
            throw new Exception($this->sqlite->lastErrorMsg());
        }
        return $result;
    }
}

?>