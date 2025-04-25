<?php

class Model {
    public static string $table = 'documents';
    public static $primary = 'id';

    public static function GetOne(int $id) {
        $db = App::$inst->db;
        $result = $db->Query('SELECT * FROM '.self::$table.' WHERE '.self::$primary.' = '.$id);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            return new self($row);
        }
    }
}

class Document extends Model {
    public static string $table = 'documents';
    public static $primary = 'id';

    public int $id;
    public string $title;
    public string $content;

    function __construct(array $data) {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->content = $data['content'];
    }
}

?>