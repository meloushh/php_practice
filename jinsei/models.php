<?php

use Framework\Model;

class Document extends Model {
    static string $table = 'documents';
    static array $columns = ['title', 'content', 'user_id'];

    public string $title;
    public string $content;
    public int $user_id;

    function __construct(array $data) {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->user_id = $data['user_id'];
    }
}

class User extends Model {
    static string $table = 'users';
    static array $columns = ['email', 'password'];

    public string $email;
    public string $password;

    function __construct(array $data) {
        $this->id = $data['id'] ?? 0;
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
}

?>