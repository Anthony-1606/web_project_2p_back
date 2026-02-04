<?php
/**
 * import_db.php
 * Script TEMPORAL para importar la estructura y datos de la base de datos
 * 
 * ⚠️ UBICACIÓN: Este archivo va en la carpeta api/
 * ⚠️ IMPORTANTE: BORRAR ESTE ARCHIVO DESPUÉS DE USARLO
 * 
 * Uso:
 * 1. Colocar en: task-manager-backend/api/import_db.php
 * 2. Subir al repositorio y hacer deploy
 * 3. Visitar: https://tu-backend.onrender.com/import_db.php
 * 4. ¡BORRAR INMEDIATAMENTE este archivo!
 * 
 * @author Anthony
 * @date 2026-02-04
 */

// Configuración de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Importación de Base de Datos</title>";
echo "<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 900px;
        margin: 50px auto;
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #333;
    }
    .container {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    h1 { color: #4F46E5; }
    h2 { color: #10B981; }
    h3 { color: #EF4444; }
    .success { 
        background: #D1FAE5; 
        border: 2px solid #10B981; 
        padding: 15px; 
        border-radius: 5px; 
        margin: 20px 0;
    }
    .error { 
        background: #FEE2E2; 
        border: 2px solid #EF4444; 
        padding: 15px; 
        border-radius: 5px; 
        margin: 20px 0;
    }
    .warning { 
        background: #FEF3C7; 
        border: 2px solid #F59E0B; 
        padding: 15px; 
        border-radius: 5px; 
        margin: 20px 0;
    }
    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin: 20px 0;
    }
    th, td { 
        padding: 12px; 
        text-align: left; 
        border: 1px solid #ddd; 
    }
    th { 
        background: #4F46E5; 
        color: white; 
    }
    tr:nth-child(even) { background: #f9f9f9; }
    ol { line-height: 2; }
    code {
        background: #f4f4f4;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
    }
</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";

echo "<h1>🗄️ Importación de Base de Datos</h1>";
echo "<hr>";

try {
    // Incluir clase de conexión (ruta relativa desde api/)
    require_once __DIR__ . '/config/Database.php';
    
    echo "<div class='success'>";
    echo "<p>✅ Clase Database cargada correctamente</p>";
    echo "</div>";
    
    // Crear conexión
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("No se pudo conectar a la base de datos");
    }
    
    echo "<div class='success'>";
    echo "<p>✅ Conexión a PostgreSQL exitosa</p>";
    echo "</div>";
    
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
    
    echo "<div class='success'>";
    echo "<p>✅ Tabla 'tasks' creada correctamente</p>";
    echo "<p>✅ Índices creados correctamente</p>";
    echo "<p>✅ 5 tareas de ejemplo insertadas</p>";
    echo "</div>";
    
    // Verificar que se insertaron los datos
    $stmt = $db->query("SELECT COUNT(*) as total FROM tasks");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<div class='success'>";
    echo "<p>✅ Total de tareas en la base de datos: <strong>" . $result['total'] . "</strong></p>";
    echo "</div>";
    
    echo "<hr>";
    echo "<h2>🎉 ¡IMPORTACIÓN COMPLETADA EXITOSAMENTE!</h2>";
    echo "<hr>";
    
    echo "<div class='warning'>";
    echo "<h3>⚠️ ACCIÓN REQUERIDA INMEDIATAMENTE:</h3>";
    echo "<ol>";
    echo "<li>Ir a tu repositorio de GitHub</li>";
    echo "<li><strong>BORRAR</strong> el archivo <code>api/import_db.php</code></li>";
    echo "<li>Ejecutar: <code>git rm api/import_db.php</code></li>";
    echo "<li>Ejecutar: <code>git commit -m 'Eliminar script de importación'</code></li>";
    echo "<li>Ejecutar: <code>git push origin main</code></li>";
    echo "<li>Render actualizará automáticamente y el archivo desaparecerá</li>";
    echo "</ol>";
    echo "<p style='color: red; font-weight: bold; font-size: 18px;'>⚠️ ¡NO DEJES ESTE ARCHIVO EN PRODUCCIÓN! ⚠️</p>";
    echo "<p>Este archivo permite que CUALQUIERA borre todos tus datos.</p>";
    echo "</div>";
    
    // Mostrar las tareas insertadas
    echo "<hr>";
    echo "<h3>📋 Tareas insertadas en la base de datos:</h3>";
    echo "<table>";
    echo "<tr>
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
    
    echo "<hr>";
    echo "<h3>🔍 Verificar que funciona:</h3>";
    echo "<p>Abre esta URL en otra pestaña para ver tus tareas en formato JSON:</p>";
    
    // Detectar URL base
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $api_url = $protocol . "://" . $host . "/tasks.php";
    
    echo "<p style='background: #f4f4f4; padding: 10px; border-radius: 5px;'>";
    echo "<a href='" . $api_url . "' target='_blank' style='color: #4F46E5; font-weight: bold;'>" . $api_url . "</a>";
    echo "</p>";
    echo "<p>Deberías ver un JSON con 5 tareas y estadísticas.</p>";
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h2>❌ ERROR AL IMPORTAR:</h2>";
    echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
    echo "</div>";
    
    echo "<h3>Posibles causas:</h3>";
    echo "<ul>";
    echo "<li>La variable <code>DATABASE_URL</code> no está configurada en Render</li>";
    echo "<li>Las credenciales de PostgreSQL son incorrectas</li>";
    echo "<li>La base de datos no está disponible</li>";
    echo "</ul>";
    
    echo "<h3>Solución:</h3>";
    echo "<ol>";
    echo "<li>Ir a Render Dashboard → Tu Web Service</li>";
    echo "<li>Environment → Variables</li>";
    echo "<li>Verificar que existe <code>DATABASE_URL</code></li>";
    echo "<li>El valor debe ser la 'Internal Database URL' de tu PostgreSQL</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p style='text-align: center; color: #666;'>";
echo "<small>Script ejecutado: " . date('Y-m-d H:i:s') . " UTC</small>";
echo "</p>";

echo "</div>"; // container
echo "</body>";
echo "</html>";
?>
