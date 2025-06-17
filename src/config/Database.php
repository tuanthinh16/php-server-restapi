<?php
require_once __DIR__ . '/../utils/LoggerTrait.php';
class Database
{
    use LoggerTrait;
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private static $instance = null;
    public $conn;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
        $this->port = $_ENV['DB_PORT'] ?? getenv('DB_PORT');
        $this->db_name = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
        $this->username = $_ENV['DB_USER'] ?? getenv('DB_USER');
        $this->password = $_ENV['DB_PASS'] ?? getenv('DB_PASS');

        // Debug
        // error_log("DB Config: {$this->host}, {$this->username}, {$this->password}");
    }

    public function connect()
    {
        $this->conn = null;

        try {
            $this->log('databse')->info('connect DB');
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}