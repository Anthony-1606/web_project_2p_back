<?php
/**
 * Task.php
 * Modelo para gestionar operaciones CRUD de tareas
 * 
 * @author Anthony
 * @date 2026-02-04
 */

class Task {
    // Conexión a la BD y nombre de tabla
    private $conn;
    private $table_name = "tasks";
    
    // Propiedades del objeto Task
    public $id;
    public $title;
    public $description;
    public $status;
    public $priority;
    public $due_date;
    public $created_at;
    public $updated_at;
    
    /**
     * Constructor
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * CREATE - Crear nueva tarea
     * @return bool True si se creó correctamente, false en caso contrario
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET title = :title,
                      description = :description,
                      status = :status,
                      priority = :priority,
                      due_date = :due_date";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->priority = htmlspecialchars(strip_tags($this->priority));
        $this->due_date = htmlspecialchars(strip_tags($this->due_date));
        
        // Bind de parámetros
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":priority", $this->priority);
        $stmt->bindParam(":due_date", $this->due_date);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * READ - Obtener todas las tareas
     * @return PDOStatement Resultado de la consulta
     */
    public function readAll() {
        $query = "SELECT id, title, description, status, priority, due_date, 
                         created_at, updated_at
                  FROM " . $this->table_name . "
                  ORDER BY 
                      CASE status 
                          WHEN 'en_proceso' THEN 1
                          WHEN 'pendiente' THEN 2
                          WHEN 'completada' THEN 3
                      END,
                      CASE priority
                          WHEN 'alta' THEN 1
                          WHEN 'media' THEN 2
                          WHEN 'baja' THEN 3
                      END,
                      due_date ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * READ ONE - Obtener una tarea por ID
     * @return bool True si se encontró, false en caso contrario
     */
    public function readOne() {
        $query = "SELECT id, title, description, status, priority, due_date,
                         created_at, updated_at
                  FROM " . $this->table_name . "
                  WHERE id = :id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->status = $row['status'];
            $this->priority = $row['priority'];
            $this->due_date = $row['due_date'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        
        return false;
    }
    
    /**
     * UPDATE - Actualizar una tarea
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET title = :title,
                      description = :description,
                      status = :status,
                      priority = :priority,
                      due_date = :due_date
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->priority = htmlspecialchars(strip_tags($this->priority));
        $this->due_date = htmlspecialchars(strip_tags($this->due_date));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind de parámetros
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":priority", $this->priority);
        $stmt->bindParam(":due_date", $this->due_date);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * DELETE - Eliminar una tarea
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind de parámetros
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Obtener conteo de tareas por estado
     * @return array Array con contadores por estado
     */
    public function getStatusCount() {
        $query = "SELECT 
                      SUM(CASE WHEN status = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                      SUM(CASE WHEN status = 'en_proceso' THEN 1 ELSE 0 END) as en_proceso,
                      SUM(CASE WHEN status = 'completada' THEN 1 ELSE 0 END) as completadas
                  FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
