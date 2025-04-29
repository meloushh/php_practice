<?php

class Model {
    static string $table = 'documents';
    public int $id = 0;
    protected array $columns = [];

    static function GetOne(int $id) {
        $db = App::$inst->db;
        $result = $db->Query('SELECT * FROM ' . static::$table . ' WHERE id = ' . $id);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            return new static($row);
        }

        throw new Exception("Failed to fetch ".static::class." with id {$id}");
    }

    static function GetAll() {
        $db = App::$inst->db;
        $resultSet = [];
        $result = $db->Query('SELECT * FROM ' . static::$table);
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
}

class Document extends Model {
    public static string $table = 'documents';
    protected array $columns = ['title', 'content'];
    public string $title;
    public string $content;

    function __construct(array $data) {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'];
        $this->content = $data['content'];
    }
}

?>