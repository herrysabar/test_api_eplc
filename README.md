# Simple Task Management API

REST API untuk manajemen task sederhana dengan autentikasi berbasis token.

## Tech Stack

- **Framework**: Laravel 12
- **Database**: MySQL
- **Authentication**: Token-based (Bearer Token)

## Cara Install

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repository-url>
cd api_eplc

# 2. Install dependencies
composer install

# 3. Copy file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di file .env
# Ubah sesuai konfigurasi MySQL Anda:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_eplc
DB_USERNAME=root
DB_PASSWORD=

# 6. Buat database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS api_eplc"

# 7. Jalankan migration
php artisan migrate

# 8. (Opsional) Jalankan seeder untuk data sample
php artisan db:seed
```

## Cara Run

```bash
# Development server
php artisan serve

# Server akan berjalan di http://localhost:8000
```

## Contoh Request

### 1. Login (POST /api/login)

**Request:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@test.com", "password": "password"}'
```

**Response Success (200):**
```json
{
  "token": "secret-token-123"
}
```

**Response Gagal (401):**
```json
{
  "message": "Invalid credentials"
}
```

---

### 2. Get List Tasks (GET /api/tasks)

**Request:**
```bash
curl -X GET http://localhost:8000/api/tasks \
  -H "Authorization: Bearer secret-token-123"
```

**Request dengan Pagination:**
```bash
curl -X GET "http://localhost:8000/api/tasks?page=1&per_page=5" \
  -H "Authorization: Bearer secret-token-123"
```

**Response (200):**
```json
{
  "message": "Tasks retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Task 1",
      "description": "Description 1",
      "status": "pending",
      "user_id": null,
      "created_at": "2026-01-27T07:00:00.000000Z",
      "updated_at": "2026-01-27T07:00:00.000000Z",
      "deleted_at": null
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

---

### 3. Get Detail Task (GET /api/tasks/{id})

**Request:**
```bash
curl -X GET http://localhost:8000/api/tasks/1 \
  -H "Authorization: Bearer secret-token-123"
```

**Response (200):**
```json
{
  "message": "Task retrieved successfully",
  "data": {
    "id": 1,
    "title": "Task 1",
    "description": "Description 1",
    "status": "pending",
    "user_id": null,
    "created_at": "2026-01-27T07:00:00.000000Z",
    "updated_at": "2026-01-27T07:00:00.000000Z",
    "deleted_at": null
  }
}
```

**Response Not Found (404):**
```json
{
  "message": "Task not found"
}
```

---

### 4. Create Task (POST /api/tasks)

**Request:**
```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Authorization: Bearer secret-token-123" \
  -H "Content-Type: application/json" \
  -d '{"title": "New Task", "description": "Task description", "status": "pending"}'
```

**Response (201):**
```json
{
  "message": "Task created successfully",
  "data": {
    "id": 1,
    "title": "New Task",
    "description": "Task description",
    "status": "pending",
    "user_id": null,
    "created_at": "2026-01-27T07:00:00.000000Z",
    "updated_at": "2026-01-27T07:00:00.000000Z"
  }
}
```

**Response Validation Error (400):**
```json
{
  "message": "Validation failed",
  "errors": {
    "title": ["Title is required"]
  }
}
```

---

### 5. Update Task (PUT /api/tasks/{id})

**Request (Partial Update):**
```bash
curl -X PUT http://localhost:8000/api/tasks/1 \
  -H "Authorization: Bearer secret-token-123" \
  -H "Content-Type: application/json" \
  -d '{"status": "done"}'
```

**Response (200):**
```json
{
  "message": "Task updated successfully",
  "data": {
    "id": 1,
    "title": "New Task",
    "description": "Task description",
    "status": "done",
    "user_id": null,
    "created_at": "2026-01-27T07:00:00.000000Z",
    "updated_at": "2026-01-27T07:30:00.000000Z",
    "deleted_at": null
  }
}
```

---

### 6. Delete Task (DELETE /api/tasks/{id})

**Request:**
```bash
curl -X DELETE http://localhost:8000/api/tasks/1 \
  -H "Authorization: Bearer secret-token-123"
```

**Response (200):**
```json
{
  "message": "Task deleted successfully"
}
```

---

## Error Handling

| Kondisi | Status Code |
|---------|-------------|
| Token tidak ada/salah | 401 |
| Data tidak ditemukan | 404 |
| Validasi gagal | 400 |
| Success GET | 200 |
| Success POST | 201 |

---

## Postman Collection

Import collection ini ke Postman:

```json
{
  "info": {
    "name": "Task Management API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Login",
      "request": {
        "method": "POST",
        "url": "http://localhost:8000/api/login",
        "header": [{"key": "Content-Type", "value": "application/json"}],
        "body": {"mode": "raw", "raw": "{\"email\": \"admin@test.com\", \"password\": \"password\"}"}
      }
    },
    {
      "name": "Get Tasks",
      "request": {
        "method": "GET",
        "url": "http://localhost:8000/api/tasks",
        "header": [{"key": "Authorization", "value": "Bearer secret-token-123"}]
      }
    },
    {
      "name": "Get Task Detail",
      "request": {
        "method": "GET",
        "url": "http://localhost:8000/api/tasks/1",
        "header": [{"key": "Authorization", "value": "Bearer secret-token-123"}]
      }
    },
    {
      "name": "Create Task",
      "request": {
        "method": "POST",
        "url": "http://localhost:8000/api/tasks",
        "header": [
          {"key": "Authorization", "value": "Bearer secret-token-123"},
          {"key": "Content-Type", "value": "application/json"}
        ],
        "body": {"mode": "raw", "raw": "{\"title\": \"New Task\", \"description\": \"Description\", \"status\": \"pending\"}"}
      }
    },
    {
      "name": "Update Task",
      "request": {
        "method": "PUT",
        "url": "http://localhost:8000/api/tasks/1",
        "header": [
          {"key": "Authorization", "value": "Bearer secret-token-123"},
          {"key": "Content-Type", "value": "application/json"}
        ],
        "body": {"mode": "raw", "raw": "{\"status\": \"done\"}"}
      }
    },
    {
      "name": "Delete Task",
      "request": {
        "method": "DELETE",
        "url": "http://localhost:8000/api/tasks/1",
        "header": [{"key": "Authorization", "value": "Bearer secret-token-123"}]
      }
    }
  ]
}
```

---

## Fitur Bonus

- ✅ **Pagination**: GET /api/tasks mendukung parameter `page` dan `per_page`
- ✅ **Soft Delete**: Task yang dihapus tidak benar-benar dihapus dari database
- ✅ **Request Logging**: Semua request API dicatat ke log file (`storage/logs/laravel.log`)
- ✅ **Unit Tests**: Tersedia test untuk semua endpoint

### Menjalankan Unit Tests

```bash
php artisan test
```

---

## Docker (Opsional)

### Build dan Run dengan Docker

```bash
# Build image
docker-compose up -d --build

# Jalankan migration di container
docker-compose exec app php artisan migrate

# Aplikasi akan berjalan di http://localhost:8000
```
