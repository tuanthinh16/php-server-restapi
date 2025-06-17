<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../config/RedisHelper.php';
require_once __DIR__ . '/../utils/LoggerTrait.php';

class AuthController
{
    private $db;
    private $user;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->user = new User($this->db);
    }

    public function login($data)
    {
        $this->user->username = $data['username'];
        $stmt = $this->user->findByUsername();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($data['password'], $user['password'])) {
                $token = Auth::generateToken($user['id']);
                Response::json(['token' => $token]);
            }
        }

        Response::json(['message' => 'Invalid credentials'], 401);
    }
}