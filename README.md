# 🔴 Task Manager - Backend API

Backend API REST en PHP para el sistema de gestión de tareas.

## 📋 Tecnologías

- PHP 8.1
- PostgreSQL
- PDO (PHP Data Objects)
- REST API

## 📁 Estructura

```
task-manager-backend/
├── api/
│   ├── tasks.php           # Endpoints REST
│   ├── config/
│   │   └── Database.php    # Conexión PostgreSQL
│   └── models/
│       └── Task.php        # Modelo de datos
├── database/
│   └── database_postgresql.sql
└── Dockerfile
```

## 🚀 Endpoints API

### GET /api/tasks.php
Obtener todas las tareas con estadísticas
```json
{
  "tasks": [...],
  "stats": {
    "pendientes": 5,
    "en_proceso": 2,
    "completadas": 10
  }
}
```

### GET /api/tasks.php?id=1
Obtener una tarea específica
```json
{
  "id": 1,
  "title": "Tarea de ejemplo",
  "description": "...",
  "status": "pendiente",
  "priority": "alta",
  "due_date": "2026-02-10"
}
```

### POST /api/tasks.php
Crear nueva tarea
```json
{
  "title": "Nueva tarea",
  "description": "Descripción",
  "status": "pendiente",
  "priority": "media",
  "due_date": "2026-02-15"
}
```

### PUT /api/tasks.php
Actualizar tarea
```json
{
  "id": 1,
  "title": "Tarea actualizada",
  "description": "...",
  "status": "en_proceso",
  "priority": "alta",
  "due_date": "2026-02-15"
}
```

### DELETE /api/tasks.php?id=1
Eliminar tarea

## 🔧 Configuración Local

1. Instalar PostgreSQL
2. Crear base de datos:
   ```bash
   psql -U postgres -f database/database_postgresql.sql
   ```
3. Iniciar servidor:
   ```bash
   php -S localhost:8080 -t api/
   ```
4. Acceder a: http://localhost:8080/tasks.php

## 🌐 Deploy en Render

1. Crear PostgreSQL database
2. Importar SQL
3. Crear Web Service desde este repositorio
4. Configurar variable de entorno:
   - `DATABASE_URL`: Connection string de PostgreSQL

## 📝 Autor

Anthony - Ingeniería en Software

## 📄 Licencia

Uso académico
