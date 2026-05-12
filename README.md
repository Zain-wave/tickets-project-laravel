# Tickets Project API

API REST para administración de tickets, asignación de dispositivos y reporte de incidentes técnicos.

## Stack

- **PHP 8.3** + **Laravel 13**
- **SQL Server 2022**
- **Docker**
- **JWT Auth** (tymon/jwt-auth)
- **Sentry** (monitoreo de errores)
- **Discord Webhooks** (notificaciones)

## Requisitos

- Docker & Docker Compose

## Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/Zain-wave/tickets-project-laravel.git
cd tickets-project-laravel

# 2. Copiar variables de entorno
cp .env.example .env

# 3. Construir y levantar contenedores
docker compose up -d --build

# 4. Ejecutar migraciones y seeders
docker compose exec app php artisan migrate --seed
```

La API estará disponible en `http://localhost:8080`.

## Variables de Entorno

| Variable | Descripción | Default |
|---|---|---|
| `APP_ENV` | Entorno | `local` |
| `APP_KEY` | Key de Laravel | (generar con `php artisan key:generate`) |
| `DB_CONNECTION` | Driver de BD | `sqlsrv` |
| `DB_HOST` | Host de BD | `sqlserver` |
| `DB_PORT` | Puerto | `1433` |
| `DB_DATABASE` | Nombre BD | `tickets` |
| `DB_USERNAME` | Usuario BD | `sa` |
| `DB_PASSWORD` | Contraseña BD | (definir) |
| `JWT_SECRET` | Secreto JWT | (generar con `php artisan jwt:secret`) |
| `SENTRY_LARAVEL_DSN` | DSN de Sentry | — |
| `DISCORD_WEBHOOK` | URL de webhook Discord | — |

## Endpoints

### Autenticación

| Método | Endpoint | Descripción | Auth |
|---|---|---|---|
| POST | `/api/register` | Registrar usuario | No |
| POST | `/api/login` | Iniciar sesión | No |
| GET | `/api/me` | Obtener perfil | Sí |
| POST | `/api/logout` | Cerrar sesión | Sí |

### Tickets

| Método | Endpoint | Descripción | Auth |
|---|---|---|---|
| GET | `/api/tickets` | Listar tickets | Sí |
| POST | `/api/tickets` | Crear ticket | Sí |
| GET | `/api/tickets/{id}` | Ver ticket | Sí |
| PUT | `/api/tickets/{id}` | Actualizar ticket | Sí |
| DELETE | `/api/tickets/{id}` | Eliminar ticket | Sí |

### Dispositivos

| Método | Endpoint | Descripción | Auth |
|---|---|---|---|
| GET | `/api/devices` | Listar dispositivos | Sí |
| POST | `/api/devices` | Crear dispositivo | Sí |
| GET | `/api/devices/{id}` | Ver dispositivo | Sí |
| POST | `/api/devices/assign` | Asignar dispositivo | Sí |
| POST | `/api/devices/return/{assignmentId}` | Devolver dispositivo | Sí |
| GET | `/api/devices/{id}/history` | Historial de asignaciones | Sí |

### Rate Limiting

- Endpoints de auth: **5 requests/minuto** por IP
- Endpoints protegidos: **30 requests/minuto** por usuario/IP

## Respuestas JSON

### Éxito

```json
{
  "data": { ... },
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

### Error

```json
{
  "message": "Descripción del error"
}
```

## Notificaciones Discord

- **Errores 500+**: se envía alerta con endpoint, método, mensaje de error, IP y fecha
- **Rate Limit excedido**: se envía alerta con endpoint, IP, timestamp e intentos

## Sentry

Todas las excepciones son capturadas y enviadas a Sentry automáticamente.

## Seeders

```bash
docker compose exec app php artisan db:seed
```

Usuarios de prueba:

| Email | Password | Rol |
|---|---|---|
| admin@example.com | password | Admin |
| tech@example.com | password | Tech Support |
| user@example.com | password | Regular User |

## Postman Collection

Importar en Postman:

```json
{
  "info": {
    "name": "Tickets API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8080/api"
    },
    {
      "key": "token",
      "value": ""
    }
  ],
  "item": [
    {
      "name": "Register",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/register",
        "header": [{"key": "Content-Type", "value": "application/json"}],
        "body": {
          "mode": "raw",
          "raw": "{\"name\": \"User\", \"email\": \"user@example.com\", \"password\": \"password\", \"password_confirmation\": \"password\"}"
        }
      }
    },
    {
      "name": "Login",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/login",
        "header": [{"key": "Content-Type", "value": "application/json"}],
        "body": {
          "mode": "raw",
          "raw": "{\"email\": \"user@example.com\", \"password\": \"password\"}"
        }
      }
    },
    {
      "name": "Get Tickets",
      "request": {
        "method": "GET",
        "url": "{{base_url}}/tickets",
        "header": [{"key": "Authorization", "value": "Bearer {{token}}"}]
      }
    },
    {
      "name": "Create Ticket",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/tickets",
        "header": [
          {"key": "Content-Type", "value": "application/json"},
          {"key": "Authorization", "value": "Bearer {{token}}"}
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"title\": \"Issue title\", \"description\": \"Description\", \"priority\": \"medium\"}"
        }
      }
    },
    {
      "name": "Get Devices",
      "request": {
        "method": "GET",
        "url": "{{base_url}}/devices",
        "header": [{"key": "Authorization", "value": "Bearer {{token}}"}]
      }
    },
    {
      "name": "Assign Device",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/devices/assign",
        "header": [
          {"key": "Content-Type", "value": "application/json"},
          {"key": "Authorization", "value": "Bearer {{token}}"}
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"user_id\": 1, \"device_id\": 1, \"notes\": \"Assignment note\"}"
        }
      }
    }
  ]
}
```

## Arquitectura

```
app/
├── Http/
│   ├── Controllers/Api/    # Controladores
│   └── Requests/           # Validación de requests
├── Models/                 # Modelos Eloquent
├── Providers/
└── Services/               # Lógica de negocio

database/
└── migrations/             # Migraciones SQL
└── seeders/                # Datos de prueba
```
