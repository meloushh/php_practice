<?php

class Model {
    static string $table = 'documents';
    public int $id = 0;
    protected array $columns = [];

    static function GetOne(string $suffix = '', array $suffix_data = []) {
        $db = App::$inst->db;

        $query = 'SELECT * FROM '.static::$table;

        $suffix = trim($suffix);
        if (strlen($suffix) > 0) {
            $query.= " {$suffix}";
        }

        $result = $db->Prepared($query, $suffix_data);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            return new static($row);
        }

        throw new Exception('Failed to fetch '.static::class.', query: "'.$query.'"');
    }

    static function GetAll() {
        $db = App::$inst->db;
        $resultSet = [];
        $result = $db->Query('SELECT * FROM '.static::$table);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $resultSet[$row['id']] = new static($row);
        }
        return $resultSet;
    }

    function Create() {
        $db = App::$inst->db;

        $query = 'INSERT INTO '.static::$table.' VALUES (NULL';
        
        for ($i = 0; $i < count($this->columns); $i++) {
            $query .= ', ?';
        }
        $query .= ')';

        $data = $this->ColumnsToAssocArr();

        $db->Prepared($query, $data);
        return $db->sqlite->lastInsertRowID();
    }

    function Update() {
        $db = App::$inst->db;
        
        $query = 'UPDATE '.static::$table.' SET ';
        
        $i = 0;
        foreach ($this->columns as $column) {
            if ($i > 0)
                $query .= ', ';

            $query .= "{$column} = ?";
            $i++;
        }
        $query .= ' WHERE id = '.$this->id;

        $data = $this->ColumnsToAssocArr();

        $db->Prepared($query, $data);
    }

    function Delete() {
        $db = App::$inst->db;
        $query = 'DELETE FROM '.static::$table.' WHERE id = '.$this->id;
        $db->Exec($query);
    }

    function ColumnsToAssocArr() : array {
        $data = [];
        foreach ($this->columns as $column) {
            $data[$column] = $this->$column;
        }
        return $data;
    }

    static function QueryVal($raw_val) {
        switch (gettype($$raw_val)) {
            case 'double': return $raw_val;
            case 'integer': return $raw_val;
            case 'boolean': return (int)$raw_val;
            case 'NULL': return "'NULL'";
            case 'string': return "'{$raw_val}'";
            default:
                throw new InvalidArgumentException('Argument is of invalid type '.gettype($raw_val));
        }
    }
}

?>