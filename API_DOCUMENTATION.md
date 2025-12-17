# API Documentation - LMS Sekolah

Base URL: `http://localhost:8000/api/v1`

## Authentication

Semua endpoint (kecuali login) memerlukan token Bearer authentication.

```
Authorization: Bearer {token}
```

---

## Auth Endpoints

### Login
```
POST /auth/login
```
**Body:**
```json
{
  "email": "admin@smksyasmu.sch.id",
  "password": "password"
}
```
**Response:**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": { ... },
    "token": "1|abc123..."
  }
}
```

### Logout
```
POST /auth/logout
```

### Get Current User
```
GET /auth/user
```

---

## Dashboard

### Get Dashboard Stats
```
GET /dashboard
```

### Get Quick Stats
```
GET /dashboard/quick-stats
```

---

## Students

### List Students
```
GET /students
GET /students?search=nama&jurusan=TPM&kelas=10
```

### Get Student
```
GET /students/{id}
```

### Create Student
```
POST /students
```
**Body:**
```json
{
  "nama": "Nama Siswa",
  "nis": "12345",
  "kelas": "10 TPM 1",
  "email": "siswa@email.com"
}
```

### Update Student
```
PUT /students/{id}
```

### Delete Student
```
DELETE /students/{id}
```

### Import Students (Excel)
```
POST /students/import
Content-Type: multipart/form-data
```

### Get Jurusan List
```
GET /students/jurusan
```

---

## Teachers

### List Teachers
```
GET /teachers
GET /teachers?search=nama
```

### Get Teacher Stats
```
GET /teachers/stats
```

### Get Teacher
```
GET /teachers/{id}
```

### Create Teacher
```
POST /teachers
```

### Update Teacher
```
PUT /teachers/{id}
```

### Delete Teacher
```
DELETE /teachers/{id}
```

---

## Courses

### List Courses
```
GET /courses
GET /courses?search=nama
```

### Get Course Stats
```
GET /courses/stats
```

### Get Course
```
GET /courses/{id}
```

### Create Course
```
POST /courses
```

### Update Course
```
PUT /courses/{id}
```

### Delete Course
```
DELETE /courses/{id}
```

---

## Assignments

### List Assignments
```
GET /assignments
GET /assignments?search=title&status=active
```

### Get Assignment Stats
```
GET /assignments/stats
```

### Get Assignment
```
GET /assignments/{id}
```

### Create Assignment
```
POST /assignments
```
**Body:**
```json
{
  "title": "Judul Tugas",
  "description": "Deskripsi tugas",
  "course_id": 1,
  "due_date": "2025-12-20",
  "kelas": ["10 TPM 1", "10 TPM 2"],
  "status": "active"
}
```

### Update Assignment
```
PUT /assignments/{id}
```

### Delete Assignment
```
DELETE /assignments/{id}
```

---

## Quizzes

### List Quizzes
```
GET /quizzes
GET /quizzes?search=title&status=published
```

### Get Quiz Stats
```
GET /quizzes/stats
```

### Get Quiz
```
GET /quizzes/{id}
```

### Create Quiz
```
POST /quizzes
```
**Body:**
```json
{
  "title": "Judul Kuis",
  "description": "Deskripsi kuis",
  "course_id": 1,
  "duration": 60,
  "max_attempts": 3,
  "passing_score": 70,
  "available_from": "2025-12-10 08:00:00",
  "available_until": "2025-12-15 17:00:00",
  "randomize_questions": true,
  "show_correct_answers": false,
  "kelas": ["10 TPM 1"],
  "status": "published",
  "questions": [
    {
      "question": "Apa ibukota Indonesia?",
      "type": "multiple_choice",
      "options": ["Jakarta", "Surabaya", "Bandung", "Medan"],
      "correct_answer": "Jakarta",
      "points": 10
    },
    {
      "question": "Indonesia adalah negara kepulauan",
      "type": "true_false",
      "options": ["Benar", "Salah"],
      "correct_answer": "Benar",
      "points": 5
    }
  ]
}
```

### Update Quiz
```
PUT /quizzes/{id}
```

### Delete Quiz
```
DELETE /quizzes/{id}
```

### Start Quiz (Take)
```
POST /quizzes/{id}/take
```
**Response:**
```json
{
  "success": true,
  "data": {
    "attempt_id": 1,
    "quiz": { ... },
    "questions": [ ... ],
    "started_at": "2025-12-10T08:00:00"
  }
}
```

### Submit Quiz
```
POST /quizzes/{id}/submit
```
**Body:**
```json
{
  "attempt_id": 1,
  "answers": {
    "1": "Jakarta",
    "2": "Benar"
  }
}
```
**Response:**
```json
{
  "success": true,
  "data": {
    "score": 85,
    "passed": true,
    "passing_score": 70,
    "total_questions": 10
  }
}
```

---

## Learning Materials

### List Materials
```
GET /materials
GET /materials?search=title&type=document&course_id=1
```

### Get Material Stats
```
GET /materials/stats
```

### Get Material
```
GET /materials/{id}
```

### Create Material
```
POST /materials
Content-Type: multipart/form-data
```
**Fields:**
- `title` (required): Judul materi
- `description`: Deskripsi
- `course_id` (required): ID mata pelajaran
- `type` (required): document, video, link
- `kelas`: Array kelas target
- `file`: File untuk type document
- `url`: URL untuk type video/link

### Update Material
```
PUT /materials/{id}
```

### Delete Material
```
DELETE /materials/{id}
```

### Download Material
```
GET /materials/{id}/download
```

---

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

### Paginated Response
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [ ... ],
    "per_page": 15,
    "total": 100,
    "last_page": 7
  }
}
```

---

## HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created |
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Access denied |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation error |
| 500 | Internal Server Error |

---

## Notes

- Semua tanggal menggunakan format ISO 8601 (`YYYY-MM-DD HH:mm:ss`)
- Timezone: `Asia/Jakarta` (WIB)
- Upload file max: 20MB untuk materi, 10MB untuk tugas
- Token Sanctum expires setelah logout
