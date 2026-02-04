<?php
/**
 * Database.php
 * Clase para gestionar la conexión a PostgreSQL en Render
 * 
 * @author Anthony
 * @date 2026-02-04
 */

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    
    public $conn;
    
    public function __construct() {
        // Render proporciona DATABASE_URL en producción
        if (getenv('DATABASE_URL')) {
            $db_url = parse_url(getenv('DATABASE_URL'));
            $this->host = $db_url['host'];
            $this->db_name = ltrim($db_url['path'], '/');
            $this->username = $db_url['user'];
            $this->password = $db_url['pass'];
            $this->port = $db_url['port'] ?? 5432;
        } else {
            // Valores por defecto para desarrollo local (si usas PostgreSQL local)
            $this->host = 'localhost';
            $this->db_name = 'task_manager';
            $this->username = 'postgres';
            $this->password = 'postgres';
            $this->port = '5432';
        }
    }
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "pgsql:host=" . $this->host . 
                   ";port=" . $this->port .
                   ";dbname=" . $this->db_name;
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            echo json_encode(array(
                'error' => 'Error de conexión',
                'message' => $exception->getMessage()
            ));
            return null;
        }
        
        return $this->conn;
    }
    
    public function closeConnection() {
        $this->conn = null;
    }
}
?>
