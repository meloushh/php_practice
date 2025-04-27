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

    function Update() {
        $db = App::$inst->db;
        $query = 'UPDATE '.static::$table.' SET ';
        $i = 0;
        foreach ($this->columns as $column) {
            if ($i > 0)
                $query .= ', ';

            $query .= "{$column} = ";

            switch (gettype($this->$column)) {
                case 'string':
                    $query .= "'{$this->$column}'";
                    break;
                case 'integer':
                case 'double':
                    $query .= $this->$column;
                    break;
            }
            $i++;
        }
        $query .= ' WHERE id = '.$this->id;
        
        $db->Exec($query);
    }
}

class Document extends Model {
    public static string $table = 'documents';
    protected array $columns = ['title', 'content'];

    public string $title;
    public string $content;

    function __construct(array $data) {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->content = $data['content'];
    }
}

?>