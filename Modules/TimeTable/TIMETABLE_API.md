# TimeTable Module - CRUD API Documentation

## Overview

The TimeTable module provides comprehensive CRUD operations for managing school timetables. It supports creating, reading, updating, and deleting timetable entries with full filtering and pagination capabilities.

## API Endpoints

### 1. List All Timetables (with Filtering)

**Endpoint:** `GET /api/v1/timetables`

**Query Parameters:**

- `classroom_id` (int, optional) - Filter by classroom
- `section_id` (int, optional) - Filter by section
- `course_id` (int, optional) - Filter by course
- `day_of_week` (string, optional) - Filter by day (Monday, Tuesday, etc.)
- `teacher_id` (int, optional) - Filter by teacher
- `status` (string, optional) - Filter by status (default: active)
- `per_page` (int, optional) - Records per page (default: 15)
- `page` (int, optional) - Page number for pagination
- `no_pagination` (boolean, optional) - Return all results without pagination

**Example Request:**

```bash
curl -X GET "http://localhost:8000/api/v1/timetables?classroom_id=1&day_of_week=Monday" \
  -H "Authorization: Bearer {token}"
```

**Response:**

```json
{
    "success": true,
    "message": "Timetables retrieved successfully",
    "data": {
        "data": [
            {
                "id": 1,
                "uuid": "uuid-string",
                "classroom_id": 1,
                "classroom": {
                    "id": 1,
                    "room_number": "I"
                },
                "section_id": 1,
                "section": {
                    "id": 1,
                    "name": "Section A"
                },
                "course_id": 1,
                "course": {
                    "id": 1,
                    "name": "Grade 1"
                },
                "day_of_week": "Monday",
                "start_time": "08:00",
                "end_time": "09:00",
                "subject_id": 1,
                "subject": {
                    "id": 1,
                    "name": "Mathematics"
                },
                "teacher_id": 7,
                "teacher": {
                    "id": 7,
                    "uuid": "uuid",
                    "first_name": "John",
                    "last_name": "Anderson",
                    "email": "john@school.com"
                },
                "room_number": "I",
                "notes": "Mathematics class",
                "status": "active",
                "created_at": "2025-11-21T10:00:00Z",
                "updated_at": "2025-11-21T10:00:00Z"
            }
        ],
        "pagination": {
            "total": 6,
            "per_page": 15,
            "current_page": 1,
            "last_page": 1
        }
    }
}
```

---

### 2. Create New Timetable Entry

**Endpoint:** `POST /api/v1/timetables`

**Required Fields:**

- `classroom_id` (integer) - Valid classroom ID
- `section_id` (integer) - Valid section ID
- `course_id` (integer) - Valid course ID
- `day_of_week` (string) - One of: Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday
- `start_time` (string) - Time in H:i format (e.g., "08:00")
- `end_time` (string) - Time in H:i format (must be after start_time)

**Optional Fields:**

- `subject_id` (integer) - Valid subject ID
- `teacher_id` (integer) - Valid user ID (teacher)
- `room_number` (string) - Room/location identifier
- `notes` (string) - Additional notes (max 500 chars)
- `status` (string) - 'active' or 'inactive' (default: 'active')

**Example Request:**

```bash
curl -X POST "http://localhost:8000/api/v1/timetables" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "classroom_id": 1,
    "section_id": 1,
    "course_id": 1,
    "day_of_week": "Monday",
    "start_time": "08:00",
    "end_time": "09:00",
    "subject_id": 1,
    "teacher_id": 7,
    "room_number": "I",
    "notes": "Mathematics class",
    "status": "active"
  }'
```

**Response (201 Created):**

```json
{
  "success": true,
  "message": "Timetable created successfully",
  "data": {
    "id": 7,
    "uuid": "new-uuid",
    "classroom_id": 1,
    ...
  }
}
```

---

### 3. Get Single Timetable Entry

**Endpoint:** `GET /api/v1/timetables/{id}`

**Example Request:**

```bash
curl -X GET "http://localhost:8000/api/v1/timetables/1" \
  -H "Authorization: Bearer {token}"
```

**Response:**

```json
{
  "success": true,
  "message": "Timetable retrieved successfully",
  "data": { ... }
}
```

---

### 4. Update Timetable Entry

**Endpoint:** `PUT /api/v1/timetables/{id}`

**Parameters:** Same as Create (all optional - use only fields you want to update)

**Example Request:**

```bash
curl -X PUT "http://localhost:8000/api/v1/timetables/1" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "end_time": "09:30",
    "teacher_id": 8
  }'
```

**Response:**

```json
{
  "success": true,
  "message": "Timetable updated successfully",
  "data": { ... }
}
```

---

### 5. Delete Timetable Entry

**Endpoint:** `DELETE /api/v1/timetables/{id}`

**Example Request:**

```bash
curl -X DELETE "http://localhost:8000/api/v1/timetables/1" \
  -H "Authorization: Bearer {token}"
```

**Response:**

```json
{
    "success": true,
    "message": "Timetable deleted successfully",
    "data": null
}
```

---

### 6. Get Timetable by Classroom and Day

**Endpoint:** `GET /api/v1/timetables/classroom-day?classroom_id={id}&day_of_week={day}`

**Query Parameters:**

- `classroom_id` (required) - Classroom ID
- `day_of_week` (required) - Day name

**Example Request:**

```bash
curl -X GET "http://localhost:8000/api/v1/timetables/classroom-day?classroom_id=1&day_of_week=Monday" \
  -H "Authorization: Bearer {token}"
```

**Response:**

```json
{
  "success": true,
  "message": "Timetables retrieved successfully",
  "data": [ ... ]
}
```

---

### 7. Get Teacher's Timetable Schedule

**Endpoint:** `GET /api/v1/timetables/teacher-schedule?teacher_id={id}`

**Query Parameters:**

- `teacher_id` (required) - Teacher/User ID

**Example Request:**

```bash
curl -X GET "http://localhost:8000/api/v1/timetables/teacher-schedule?teacher_id=7" \
  -H "Authorization: Bearer {token}"
```

**Response:**

```json
{
  "success": true,
  "message": "Timetables retrieved successfully",
  "data": [ ... ]
}
```

---

## Authentication

All endpoints require Bearer token authentication via `Authorization: Bearer {token}` header.

## Authorization

- Only authenticated users with roles: 'root_admin', 'admin', 'teacher' can create/update/delete timetables
- GET endpoints are available to all authenticated users

## Error Responses

### Validation Error (400)

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "end_time": ["The end time must be after the start time."]
    }
}
```

### Not Found (404)

```json
{
    "success": false,
    "message": "Timetable not found",
    "data": null
}
```

### Server Error (500)

```json
{
    "success": false,
    "message": "Failed to retrieve timetables",
    "data": {
        "error": "Error message"
    }
}
```

---

## Valid Days of Week

- Monday
- Tuesday
- Wednesday
- Thursday
- Friday
- Saturday
- Sunday

## Time Format

Use 24-hour format: `HH:MM` (e.g., "08:00", "14:30")

## Model Relationships

Each timetable entry includes:

- Classroom (room_number, etc.)
- Section (name, etc.)
- Course (name, etc.)
- Subject (name, etc.)
- Teacher/User (name, email, etc.)
