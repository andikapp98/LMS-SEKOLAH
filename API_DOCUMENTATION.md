# API Documentation

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
API menggunakan Laravel Sanctum untuk authentication. Setelah login, gunakan token yang diterima pada header `Authorization: Bearer {token}` untuk endpoint yang memerlukan authentication.

---

## Endpoints

### 1. Login
**POST** `/api/v1/auth/login`

Login dan mendapatkan token authentication.

**Request Body:**
```json
{
  "email": "admin@test.com",
  "password": "password"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "admin@test.com"
    },
    "token": "1|WfmMo5SuEhSdhx3dYToWnRfQaWkhgqKCxSIv2EZ625057ac0"
  }
}
```

**Response Error (401):**
```json
{
  "success": false,
  "message": "Email atau password salah"
}
```

---

### 2. Get User Info
**GET** `/api/v1/auth/user`

Mendapatkan informasi user yang sedang login.

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Admin",
    "email": "admin@test.com",
    "role": null,
    "created_at": "2025-11-28T02:07:18.000000Z",
    "updated_at": "2025-11-28T02:07:18.000000Z"
  }
}
```

**Response Error (401):**
```json
{
  "message": "Unauthenticated."
}
```

---

### 3. Logout
**POST** `/api/v1/auth/logout`

Logout dan menghapus token authentication saat ini.

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

---

## Testing dengan cURL

### Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@test.com","password":"password"}'
```

### Get User (dengan token)
```bash
curl -X GET http://localhost:8000/api/v1/auth/user \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Logout
```bash
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Testing dengan PowerShell

### Login
```powershell
$body = @{
    email = "admin@test.com"
    password = "password"
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/login" `
    -Method Post `
    -Body $body `
    -ContentType "application/json"

$token = $response.data.token
```

### Get User
```powershell
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/user" `
    -Method Get `
    -Headers $headers
```

### Logout
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/logout" `
    -Method Post `
    -Headers $headers
```

---

## Data Siswa (Students)

### 4. Get All Students
**GET** `/api/v1/students`

Mendapatkan daftar semua siswa (dengan pagination).

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "nis": "12345",
      "nisn": "0012345678",
      "nama": "John Doe",
      "jenis_kelamin": "L",
      "tempat_lahir": "Jakarta",
      "tanggal_lahir": "2005-01-15",
      "alamat": "Jl. Contoh No. 123",
      "kelas": "XII IPA 1",
      "no_hp": "081234567890",
      "email": "john@example.com",
      "nama_wali": "Jane Doe",
      "no_hp_wali": "081234567891",
      "created_at": "2025-11-28T05:30:00.000000Z",
      "updated_at": "2025-11-28T05:30:00.000000Z"
    }
  ],
  "per_page": 20,
  "total": 1
}
```

---

### 5. Create Student
**POST** `/api/v1/students`

Menambah data siswa baru.

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**Request Body:**
```json
{
  "nis": "12345",
  "nisn": "0012345678",
  "nama": "John Doe",
  "jenis_kelamin": "L",
  "tempat_lahir": "Jakarta",
  "tanggal_lahir": "2005-01-15",
  "alamat": "Jl. Contoh No. 123",
  "kelas": "XII IPA 1",
  "no_hp": "081234567890",
  "email": "john@example.com",
  "nama_wali": "Jane Doe",
  "no_hp_wali": "081234567891"
}
```

**Field Requirements:**
- `nis` (required, unique)
- `nisn` (optional, unique jika diisi)
- `nama` (required)
- `jenis_kelamin` (required, nilai: "L" atau "P")
- `tempat_lahir` (optional)
- `tanggal_lahir` (optional, format: YYYY-MM-DD)
- `alamat` (optional)
- `kelas` (optional)
- `no_hp` (optional)
- `email` (optional, unique jika diisi)
- `nama_wali` (optional)
- `no_hp_wali` (optional)

**Response Success (201):**
```json
{
  "success": true,
  "message": "Data siswa berhasil ditambahkan",
  "data": {
    "id": 1,
    "nis": "12345",
    "nama": "John Doe",
    ...
  }
}
```

---

### 6. Get Student by ID
**GET** `/api/v1/students/{id}`

Mendapatkan detail data siswa berdasarkan ID.

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
  "id": 1,
  "nis": "12345",
  "nisn": "0012345678",
  "nama": "John Doe",
  ...
}
```

---

### 7. Update Student
**PUT** `/api/v1/students/{id}`

Mengupdate data siswa.

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**Request Body:** (sama seperti Create Student)

**Response Success (200):**
```json
{
  "success": true,
  "message": "Data siswa berhasil diperbarui",
  "data": {
    "id": 1,
    "nis": "12345",
    ...
  }
}
```

---

### 8. Delete Student
**DELETE** `/api/v1/students/{id}`

Menghapus data siswa.

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Data siswa berhasil dihapus"
}
```

---

### 9. Import Students from Excel
**POST** `/api/v1/students/import`

Upload dan import data siswa dari file Excel/CSV.

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: multipart/form-data
```

**Request Body:**
```
file: [Excel/CSV file]
```

**Format Excel/CSV:**
File Excel harus memiliki header row dengan kolom:
- nis (required)
- nisn (optional)
- nama (required)
- jenis_kelamin (required, L/P)
- tempat_lahir (optional)
- tanggal_lahir (optional, format: YYYY-MM-DD atau Excel date)
- alamat (optional)
- kelas (optional)
- no_hp (optional)
- email (optional)
- nama_wali (optional)
- no_hp_wali (optional)

**Response Success (200):**
```json
{
  "success": true,
  "message": "Data siswa berhasil diimport dari Excel"
}
```

**Response Error (422) - Validation Error:**
```json
{
  "success": false,
  "message": "Terdapat kesalahan dalam data Excel",
  "errors": [
    {
      "row": 2,
      "attribute": "nis",
      "errors": ["The nis has already been taken."],
      "values": {
        "nis": "12345",
        "nama": "John Doe"
      }
    }
  ]
}
```

---

### 10. Download Excel Template
**GET** `/api/v1/students/template/download`

Download template Excel untuk import siswa.

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
File CSV dengan header kolom yang sesuai untuk import.

---

## Testing dengan cURL - Students

### Get All Students
```bash
curl -X GET http://localhost:8000/api/v1/students \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Create Student
```bash
curl -X POST http://localhost:8000/api/v1/students \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "nis": "12345",
    "nisn": "0012345678",
    "nama": "John Doe",
    "jenis_kelamin": "L",
    "tempat_lahir": "Jakarta",
    "tanggal_lahir": "2005-01-15",
    "kelas": "XII IPA 1"
  }'
```

### Import Excel
```bash
curl -X POST http://localhost:8000/api/v1/students/import \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "file=@/path/to/students.xlsx"
```

### Download Template
```bash
curl -X GET http://localhost:8000/api/v1/students/template/download \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -o template_siswa.csv
```

## Testing dengan PowerShell - Students

### Get All Students
```powershell
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

Invoke-RestMethod -Uri "http://localhost:8000/api/v1/students" `
    -Method Get `
    -Headers $headers
```

### Create Student
```powershell
$body = @{
    nis = "12345"
    nisn = "0012345678"
    nama = "John Doe"
    jenis_kelamin = "L"
    tempat_lahir = "Jakarta"
    tanggal_lahir = "2005-01-15"
    kelas = "XII IPA 1"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/v1/students" `
    -Method Post `
    -Body $body `
    -Headers $headers `
    -ContentType "application/json"
```

### Import Excel
```powershell
$filePath = "C:\path\to\students.xlsx"
$uri = "http://localhost:8000/api/v1/students/import"

$headers = @{
    "Authorization" = "Bearer $token"
}

Add-Type -AssemblyName System.Net.Http
$client = New-Object System.Net.Http.HttpClient
$client.DefaultRequestHeaders.Authorization = New-Object System.Net.Http.Headers.AuthenticationHeaderValue("Bearer", $token)

$content = New-Object System.Net.Http.MultipartFormDataContent
$fileStream = [System.IO.File]::OpenRead($filePath)
$fileContent = New-Object System.Net.Http.StreamContent($fileStream)
$content.Add($fileContent, "file", [System.IO.Path]::GetFileName($filePath))

$response = $client.PostAsync($uri, $content).Result
$result = $response.Content.ReadAsStringAsync().Result
$result | ConvertFrom-Json
```

### Download Template
```powershell
Invoke-WebRequest -Uri "http://localhost:8000/api/v1/students/template/download" `
    -Headers $headers `
    -OutFile "template_siswa.csv"
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 200  | Success |
| 401  | Unauthorized (token invalid/expired atau kredensial salah) |
| 422  | Validation Error |
| 500  | Server Error |
