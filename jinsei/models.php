<?php

require_once BASEDIR.'/framework/Model.php';

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

class User extends Model {
    public static string $table = 'users';
    protected array $columns = ['email', 'password'];

    public string $email;
    public string $password;

    function __construct(array $data) {
        $this->id = $data['id'] ?? 0;
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
}

?>