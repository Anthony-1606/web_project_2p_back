    <?php
/**
 * tasks.php
 * API REST para gestión de tareas
 * Endpoints: GET, POST, PUT, DELETE
 * 
 * @author Anthony
 * @date 2026-02-04
 */

// Ocultar warnings en producción
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '0');

// Headers CORS - IMPORTANTE para permitir peticiones desde Netlify
$allowed_origin = getenv('FRONTEND_URL') ?: '*';
header("Access-Control-Allow-Origin: $allowed_origin");
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Manejar preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Incluir archivos necesarios
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Task.php';

// Crear conexión a BD
$database = new Database();
$db = $database->getConnection();

// Crear instancia de Task
$task = new Task($db);

// Obtener método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Obtener datos de entrada
$data = json_decode(file_get_contents("php://input"));

// Switch según el método HTTP
switch($method) {
    
    // ============================================
    // GET - Obtener tareas
    // ============================================
    case 'GET':
        if(isset($_GET['id'])) {
            // Obtener una tarea específica
            $task->id = $_GET['id'];
            
            if($task->readOne()) {
                $task_arr = array(
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'due_date' => $task->due_date,
                    'created_at' => $task->created_at,
                    'updated_at' => $task->updated_at
                );
                
                http_response_code(200);
                echo json_encode($task_arr);
            } else {
                http_response_code(404);
                echo json_encode(array('message' => 'Tarea no encontrada'));
            }
        } else {
            // Obtener todas las tareas
            $stmt = $task->readAll();
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Obtener estadísticas
            $stats = $task->getStatusCount();
            
            http_response_code(200);
            echo json_encode(array(
                'tasks' => $tasks,
                'stats' => $stats
            ));
        }
        break;
    
    // ============================================
    // POST - Crear nueva tarea
    // ============================================
    case 'POST':
    if(!empty($data->title)) {
        $task->title = $data->title;
        $task->description = isset($data->description) ? $data->description : '';
        $task->status = isset($data->status) ? $data->status : 'pendiente';
        $task->priority = isset($data->priority) ? $data->priority : 'media';
        
        // FIX IMPORTANTE: Manejar fecha vacía
        if (isset($data->due_date) && !empty($data->due_date)) {
            $task->due_date = $data->due_date;
        } else {
            $task->due_date = null;  // NULL en vez de string vacío
        }
        
        if($task->create()) {
            http_response_code(201);
            echo json_encode(array(
                'message' => 'Tarea creada exitosamente',
                'success' => true
            ));
        } else {
            http_response_code(500);
            echo json_encode(array(
                'message' => 'Error al crear la tarea',
                'success' => false
            ));
        }
    } else {
        http_response_code(400);
        echo json_encode(array(
            'message' => 'Datos incompletos. El título es obligatorio',
            'success' => false
        ));
    }
    break;
    
    // ============================================
    // PUT - Actualizar tarea
    // ============================================
    case 'PUT':
        if(
            !empty($data->id) &&
            !empty($data->title)
        ) {
            $task->id = $data->id;
            $task->title = $data->title;
            $task->description = $data->description ?? '';
            $task->status = $data->status ?? 'pendiente';
            $task->priority = $data->priority ?? 'media';
            $task->due_date = $data->due_date ?? null;
            
            if($task->update()) {
                http_response_code(200);
                echo json_encode(array(
                    'message' => 'Tarea actualizada exitosamente',
                    'success' => true
                ));
            } else {
                http_response_code(500);
                echo json_encode(array(
                    'message' => 'Error al actualizar la tarea',
                    'success' => false
                ));
            }
        } else {
            http_response_code(400);
            echo json_encode(array(
                'message' => 'Datos incompletos',
                'success' => false
            ));
        }
        break;
    
    // ============================================
    // DELETE - Eliminar tarea
    // ============================================
    case 'DELETE':
        if(!empty($_GET['id'])) {
            $task->id = $_GET['id'];
            
            if($task->delete()) {
                http_response_code(200);
                echo json_encode(array(
                    'message' => 'Tarea eliminada exitosamente',
                    'success' => true
                ));
            } else {
                http_response_code(500);
                echo json_encode(array(
                    'message' => 'Error al eliminar la tarea',
                    'success' => false
                ));
            }
        } else {
            http_response_code(400);
            echo json_encode(array(
                'message' => 'ID de tarea no proporcionado',
                'success' => false
            ));
        }
        break;
    
    // ============================================
    // Método no permitido
    // ============================================
    default:
        http_response_code(405);
        echo json_encode(array('message' => 'Método no permitido'));
        break;
}
?>
