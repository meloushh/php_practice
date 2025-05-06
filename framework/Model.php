<?php

class Model {
    static string $table = 'documents';
    static array $columns = [];
    public int $id = 0;

    static function GetOne(string $suffix = '', array $suffix_data = []) {
        $db = App::$si->db;

        $query = 'SELECT * FROM '.static::$table;
        $suffix = trim($suffix);
        if (strlen($suffix) > 0) {
            $query.= " {$suffix}";
        }
        $result = $db->Prepared($query, $suffix_data);

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            return new static($row);
        }

        return null;
    }

    static function GetAll(string $suffix = '', array $suffix_data = []) {
        $db = App::$si->db;

        $query = 'SELECT * FROM '.static::$table;
        $suffix = trim($suffix);
        if (strlen($suffix) > 0) {
            $query.= " {$suffix}";
        }
        $result = $db->Prepared($query, $suffix_data);

        $resultSet = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $resultSet[$row['id']] = new static($row);
        }
        return $resultSet;
    }

    static function Create(array $data) {
        $db = App::$si->db;

        $query = 'INSERT INTO '.static::$table.' VALUES (NULL';
        
        for ($i = 0; $i < count(static::$columns); $i++) {
            $query .= ', ?';
        }
        $query .= ')';

        $db->Prepared($query, $data);
        $data['id'] = $db->sqlite->lastInsertRowID();
        
        return new static($data);
    }

    function Update() {
        $db = App::$si->db;
        
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
        $db = App::$si->db;
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