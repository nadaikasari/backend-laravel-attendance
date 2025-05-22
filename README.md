# Presence API with JWT Auth

API ini digunakan untuk autentikasi dan pencatatan kehadiran (presence) dengan fitur approval menggunakan Laravel + JWT.

---

## Instalation and Setup

1. **Clone repository**
```bash
git clone https://github.com/username/repo-name.git
cd repo-name
```
2. **Install dependency**
```bash
composer install
```
4. **Copy dan konfigurasi file environment**
```bash
cp .env.example .env
php artisan key:generate
```
6. **Atur koneksi database di file .env**
```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```
8. **Jalankan migrasi**
```bash
php artisan migrate
```
10. **Generate secret JWT**
```bash
php artisan jwt:secret
```
12. **Jalankan server**
```bash
php artisan serve
```

---

## API Endpoints
### Register

| Method | Endpoint        | Description        |
|--------|------------------|--------------------|
| POST   | `/api/register` | Create a new user   |

**Sample Payload**:
```json
{
  "name" : "Nada",
  "email": "nada@gmail.com",
  "password": "123456",
  "npp" : "123093"
}

```
---
### Login

| Method | Endpoint        | Description        |
|--------|------------------|--------------------|
| POST   | `/api/login` | Login to  get token   |

**Sample Payload**:
```json
{
  "email": "nada@gmail.com",
  "password": "123456"
}

```
---

### Presence

| Method | Endpoint        | Description              | Headers                          |
|--------|------------------|--------------------------|----------------------------------|
| POST   | `/api/presence`     | Generate presence | `Authorization: Bearer <token>`      |
| GET    | `/api/presence`  | Get all data presence    | `Authorization: Bearer <token>`  |
| PUT    | `/api/presence/{approvalId}/approval`  | Approve data presence    | `Authorization: Bearer <token>`  |


**Sample Payload Post Presence**:
```json
{
    "type" : "OUT",
    "date"  : "2025-05-22 18:00:00"
}

```
**Sample Payload Get Presence**:
```json
{
    "limit" : 10,
    "page" : 1
}
```
---
