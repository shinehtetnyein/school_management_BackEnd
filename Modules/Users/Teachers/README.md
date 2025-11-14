# Teacher Module API Documentation

## Overview
The Teacher module provides comprehensive CRUD operations for managing teacher information and linking them with courses and subjects.

## Base URL
`http://localhost:8000/api/v1/teachers`

## Authentication
All endpoints require authentication using Laravel Sanctum (`auth:sanctum` middleware).

## Endpoints

### 1. List All Teachers
- **Method**: `GET`
- **Endpoint**: `/api/v1/teachers`
- **Query Parameters**:
  - `per_page` (optional, default: 10) - Number of teachers per page
  
**Example Request**:
```
GET /api/v1/teachers?per_page=20
```

**Response**:
```json
{
  "success": true,
  "message": "Teachers retrieved successfully",
  "data": [
    {
      "id": 1,
      "uuid": "uuid-string",
      "first_name": "John",
      "last_name": "Doe",
      "full_name": "John Doe",
      "email": "john@example.com",
      "phone_no": "+1234567890",
      "address": "123 Street",
      "city": "City Name",
      "country": "Country Name",
      "date_of_birth": "1990-01-15",
      "gender": "male",
      "profile_photo": null,
      "status": 1,
      "roles": ["teacher"],
      "permissions": [],
      "teaching_subjects": [
        {
          "id": 1,
          "name": "Mathematics",
          "code": "MATH101",
          "status": 1,
          "assigned_date": "2025-11-14T10:00:00Z"
        }
      ],
      "teaching_courses": [
        {
          "id": 1,
          "name": "Algebra I",
          "code": "ALG101",
          "status": 1,
          "assigned_date": "2025-11-14T10:00:00Z"
        }
      ],
      "created_at": "2025-11-14T10:00:00Z",
      "updated_at": "2025-11-14T10:00:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 25,
    "last_page": 3
  }
}
```

### 2. Get Single Teacher
- **Method**: `GET`
- **Endpoint**: `/api/v1/teachers/{id}`

**Example Request**:
```
GET /api/v1/teachers/1
```

**Response**:
```json
{
  "success": true,
  "message": "Teacher retrieved successfully",
  "data": { ... }
}
```

### 3. Create New Teacher
- **Method**: `POST`
- **Endpoint**: `/api/v1/teachers`
- **Content-Type**: `application/json`

**Request Body**:
```json
{
  "first_name": "Jane",
  "last_name": "Smith",
  "email": "jane@example.com",
  "phone_no": "+1234567890",
  "password": "SecurePassword123!",
  "gender": "female",
  "date_of_birth": "1988-05-20",
  "address": "456 Avenue",
  "city": "New City",
  "country": "New Country",
  "status": 1
}
```

**Response** (201 Created):
```json
{
  "success": true,
  "message": "Teacher created successfully",
  "data": { ... }
}
```

### 4. Update Teacher
- **Method**: `PATCH` or `PUT`
- **Endpoint**: `/api/v1/teachers/{id}`

**Request Body** (all fields optional):
```json
{
  "first_name": "Jane",
  "phone_no": "+9876543210",
  "status": 0
}
```

**Response**:
```json
{
  "success": true,
  "message": "Teacher updated successfully",
  "data": { ... }
}
```

### 5. Delete Teacher
- **Method**: `DELETE`
- **Endpoint**: `/api/v1/teachers/{id}`

**Response**:
```json
{
  "success": true,
  "message": "Teacher deleted successfully"
}
```

---

## Subject Management Endpoints

### 6. Assign Subject to Teacher
- **Method**: `POST`
- **Endpoint**: `/api/v1/teachers/{id}/assign-subject`

**Request Body**:
```json
{
  "subject_id": 5
}
```

**Response**:
```json
{
  "success": true,
  "message": "Subject assigned successfully",
  "data": [
    {
      "id": 5,
      "name": "Physics",
      "code": "PHY101",
      "status": 1,
      "assigned_date": "2025-11-14T10:00:00Z"
    }
  ]
}
```

### 7. Remove Subject from Teacher
- **Method**: `DELETE`
- **Endpoint**: `/api/v1/teachers/{id}/remove-subject/{subjectId}`

**Response**:
```json
{
  "success": true,
  "message": "Subject removed successfully"
}
```

### 8. Get Teacher's Assigned Subjects
- **Method**: `GET`
- **Endpoint**: `/api/v1/teachers/{id}/subjects`

**Response**:
```json
{
  "success": true,
  "message": "Teacher subjects retrieved successfully",
  "data": [ ... ]
}
```

### 9. Get Teachers for a Specific Subject
- **Method**: `GET`
- **Endpoint**: `/api/v1/teachers/subject/{subjectId}`

**Response**:
```json
{
  "success": true,
  "message": "Teachers retrieved successfully",
  "data": [ ... ]
}
```

---

## Course Management Endpoints

### 10. Assign Course to Teacher
- **Method**: `POST`
- **Endpoint**: `/api/v1/teachers/{id}/assign-course`

**Request Body**:
```json
{
  "course_id": 3
}
```

**Response**:
```json
{
  "success": true,
  "message": "Course assigned successfully",
  "data": [ ... ]
}
```

### 11. Remove Course from Teacher
- **Method**: `DELETE`
- **Endpoint**: `/api/v1/teachers/{id}/remove-course/{courseId}`

**Response**:
```json
{
  "success": true,
  "message": "Course removed successfully"
}
```

### 12. Get Teacher's Assigned Courses
- **Method**: `GET`
- **Endpoint**: `/api/v1/teachers/{id}/courses`

**Response**:
```json
{
  "success": true,
  "message": "Teacher courses retrieved successfully",
  "data": [ ... ]
}
```

---

## Department/Filter Endpoints

### 13. Get Teachers by Department
- **Method**: `GET`
- **Endpoint**: `/api/v1/teachers/department/{departmentId}`

**Response**:
```json
{
  "success": true,
  "message": "Teachers retrieved successfully",
  "data": [ ... ]
}
```

---

## Error Responses

All endpoints return consistent error responses:

### 404 Not Found
```json
{
  "success": false,
  "message": "Teacher not found",
  "error": "Exception message"
}
```

### 422 Unprocessable Entity (Validation Error)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["Email already exists"],
    "password": ["Password must be at least 8 characters"]
  }
}
```

### 500 Internal Server Error
```json
{
  "success": false,
  "message": "Failed to create/update/delete teacher",
  "error": "Exception message"
}
```

---

## Relationships

Teachers are linked with:
- **Subjects** (Many-to-Many): `teaching_subjects`
- **Courses** (Many-to-Many): `teaching_courses`
- **Students** (Indirect): Through courses and subjects
- **User Role**: Teacher role is automatically assigned when created

## Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation error |
| 500 | Internal Server Error - Server error |

## Notes

- All passwords are hashed using Laravel's default hash
- UUIDs are automatically generated if not provided
- Teachers can have multiple subjects and courses assigned
- Deleting a teacher will detach all assignments
