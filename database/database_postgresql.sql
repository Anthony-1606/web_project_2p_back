-- ============================================
-- TASK MANAGER - DATABASE STRUCTURE (PostgreSQL)
-- Para uso en Render.com
-- ============================================

-- Conectar a la base de datos (si usas terminal)
-- \c task_manager;

-- Eliminar tabla si existe
DROP TABLE IF EXISTS tasks;

-- ============================================
-- Tabla: tasks
-- Descripción: Almacena las tareas del usuario
-- ============================================
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

-- ============================================
-- Índices para mejorar consultas
-- ============================================
CREATE INDEX idx_status ON tasks(status);
CREATE INDEX idx_priority ON tasks(priority);
CREATE INDEX idx_due_date ON tasks(due_date);

-- ============================================
-- Datos de prueba
-- ============================================
INSERT INTO tasks (title, description, status, priority, due_date) VALUES
('Completar proyecto de Desarrollo Web', 'Terminar CRUD con PHP y MySQL usando patrón MVC', 'en_proceso', 'alta', CURRENT_DATE),
('Estudiar para examen de Base de Datos', 'Repasar normalización, stored procedures y transacciones', 'pendiente', 'alta', CURRENT_DATE + INTERVAL '6 days'),
('Hacer ejercicios de JavaScript', 'Practicar validaciones y manipulación del DOM', 'pendiente', 'media', CURRENT_DATE + INTERVAL '2 days'),
('Revisar documentación de PHP', 'Leer sobre PDO y prepared statements para evitar SQL injection', 'completada', 'media', CURRENT_DATE - INTERVAL '2 days'),
('Configurar entorno de desarrollo', 'Instalar XAMPP y configurar virtual hosts', 'completada', 'baja', CURRENT_DATE - INTERVAL '3 days');

-- ============================================
-- Verificación de datos insertados
-- ============================================
SELECT * FROM tasks ORDER BY created_at DESC;

-- ============================================
-- Consultas útiles
-- ============================================

-- Ver todas las tareas pendientes
-- SELECT * FROM tasks WHERE status = 'pendiente' ORDER BY priority DESC, due_date ASC;

-- Ver tareas por prioridad
-- SELECT priority, COUNT(*) as total FROM tasks GROUP BY priority;

-- Ver tareas próximas a vencer (próximos 7 días)
-- SELECT * FROM tasks WHERE due_date BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL '7 days' ORDER BY due_date;

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
