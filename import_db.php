<?php
/**
 * import_db.php
 * Script TEMPORAL para importar la estructura y datos de la base de datos
 * 
 * ⚠️ IMPORTANTE: BORRAR ESTE ARCHIVO DESPUÉS DE USARLO
 * 
 * Uso:
 * 1. Subir este archivo al repositorio backend
 * 2. Hacer deploy en Render
 * 3. Visitar: https://tu-backend.onrender.com/import_db.php
 * 4. ¡BORRAR INMEDIATAMENTE este archivo del repositorio!
 * 
 * @author Anthony
 * @date 2026-02-04
 */

// Configuración de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🗄️ Importación de Base de Datos</h1>";
echo "<hr>";

try {
    // Incluir clase de conexión
    require_once __DIR__ . '/api/config/Database.php';
    
    echo "<p>✅ Clase Database cargada correctamente</p>";
    
    // Crear conexión
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("No se pudo conectar a la base de datos");
    }
    
    echo "<p>✅ Conexión a PostgreSQL exitosa</p>";
    
    // SQL completo para crear la estructura y datos
    $sql = "
-- Eliminar tabla si existe
DROP TABLE IF EXISTS tasks;

-- Crear tabla tasks
CREATE TABLE tasks (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'pendiente' CHECK (status IN ('pendiente', 'en_proceso', 'completada')),
    priority VARCHAR(20) DEFAULT 'media' CHECK (priority IN ('baja', 'media', 'alta')),
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear índices
CREATE INDEX idx_status ON tasks(status);
CREATE INDEX idx_priority ON tasks(priority);
CREATE INDEX idx_due_date ON tasks(due_date);

-- Insertar datos de prueba
INSERT INTO tasks (title, description, status, priority, due_date) VALUES
('Completar proyecto de Desarrollo Web', 'Terminar CRUD con PHP y MySQL usando patrón MVC', 'en_proceso', 'alta', CURRENT_DATE),
('Estudiar para examen de Base de Datos', 'Repasar normalización, stored procedures y transacciones', 'pendiente', 'alta', CURRENT_DATE + INTERVAL '6 days'),
('Hacer ejercicios de JavaScript', 'Practicar validaciones y manipulación del DOM', 'pendiente', 'media', CURRENT_DATE + INTERVAL '2 days'),
('Revisar documentación de PHP', 'Leer sobre PDO y prepared statements para evitar SQL injection', 'completada', 'media', CURRENT_DATE - INTERVAL '2 days'),
('Configurar entorno de desarrollo', 'Instalar XAMPP y configurar virtual hosts', 'completada', 'baja', CURRENT_DATE - INTERVAL '3 days');
";
    
    echo "<p>📝 Ejecutando script SQL...</p>";
    
    // Ejecutar el SQL
    $db->exec($sql);
    
    echo "<p>✅ Tabla 'tasks' creada correctamente</p>";
    echo "<p>✅ Índices creados correctamente</p>";
    echo "<p>✅ 5 tareas de ejemplo insertadas</p>";
    
    // Verificar que se insertaron los datos
    $stmt = $db->query("SELECT COUNT(*) as total FROM tasks");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<p>✅ Total de tareas en la base de datos: <strong>" . $result['total'] . "</strong></p>";
    
    echo "<hr>";
    echo "<h2>🎉 ¡IMPORTACIÓN COMPLETADA EXITOSAMENTE!</h2>";
    echo "<hr>";
    echo "<h3 style='color: red;'>⚠️ ACCIÓN REQUERIDA:</h3>";
    echo "<ol>";
    echo "<li>Ir a tu repositorio de GitHub</li>";
    echo "<li>BORRAR este archivo (import_db.php)</li>";
    echo "<li>Hacer commit y push</li>";
    echo "<li>Render actualizará automáticamente</li>";
    echo "</ol>";
    echo "<p style='color: red; font-weight: bold;'>¡NO DEJES ESTE ARCHIVO EN PRODUCCIÓN!</p>";
    
    // Mostrar las tareas insertadas
    echo "<hr>";
    echo "<h3>📋 Tareas insertadas:</h3>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'>
            <th>ID</th>
            <th>Título</th>
            <th>Estado</th>
            <th>Prioridad</th>
            <th>Fecha</th>
          </tr>";
    
    $stmt = $db->query("SELECT id, title, status, priority, due_date FROM tasks ORDER BY id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td>" . $row['priority'] . "</td>";
        echo "<td>" . $row['due_date'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ ERROR:</h2>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<p>Detalles técnicos:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><small>Script ejecutado: " . date('Y-m-d H:i:s') . "</small></p>";
?>
