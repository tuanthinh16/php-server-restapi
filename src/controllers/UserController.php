<?php
// echo '__DIR__: ' . __DIR__ . "\n";
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../config/RedisHelper.php';
require_once __DIR__ . '/../utils/LoggerTrait.php';


class UserController
{
    use LoggerTrait;
    private $db;
    private $user;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();

        $this->user = new User($this->db);
    }

    public function getAll()
    {
        $this->log('user')->info('get all');
        $stmt = $this->user->read();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Response::json($users);
    }

    public function create($data)
    {
        $this->user->username = $data['username'];
        $this->user->password = $data['password'];

        if ($this->user->create()) {
            Response::json(['message' => 'User created'], 201);
        } else {
            Response::json(['message' => 'User creation failed'], 400);
        }
    }
    public function getUserById($id)
    {
        $cacheKey = "user:$id";

        try {
            // Kiểm tra cache
            $user = RedisHelper::get($cacheKey);
            if ($user) {
                return Response::json(['source' => 'cache', 'data' => $user]);
            }
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Lưu cache nếu có kết quả
                RedisHelper::set($cacheKey, $user, 600);
                return Response::json(['source' => 'db', 'data' => $user]);
            } else {
                return Response::json(['error' => 'User not found'], 404);
            }

        } catch (Exception $e) {
            $this->log('user')->info("Server Error when get user {$e->getMessage()}");
            return Response::json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

}