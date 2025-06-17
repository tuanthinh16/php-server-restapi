<?php

class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $password;
    public $role;
    public $creator;
    public $create_time;
    public $modifier;
    public $last_login;
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function read()
    {
        $query = "SELECT id, username, role, last_login FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                 SET username = :username, 
                     password = :password,
                     role = :role,
                     creator = :creator,
                     create_time = :create_time";

        $stmt = $this->conn->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->create_time = time();

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':creator', $this->creator);
        $stmt->bindParam(':create_time', $this->create_time);

        return $stmt->execute();
    }

    public function findByUsername()
    {
        $query = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->username]);
        return $stmt;
    }
}