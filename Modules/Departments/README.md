# Department Module API Documentation

## Overview
This documentation covers the API endpoints for the Department module in the High School Management System.

## Base URL
```
/api/departments
```

## Authentication
All endpoints require authentication using Laravel Sanctum. Include the authentication token in the request header:
```
Authorization: Bearer <your-token>
```

## Endpoints

### Department Management

#### List Departments
```http
GET /api/departments
```
Query Parameters:
- `type` (optional): Filter by department type (academic, administrative, support)

#### Get Department
```http
GET /api/departments/{id}
```

#### Create Department
```http
POST /api/departments
```
Request Body:
```json
{
    "name": "Science Department",
    "code": "SCI",
    "type": "academic",
    "description": "Science department description",
    "head_of_department": 1,
    "location": "Building A, Floor 2",
    "contact_email": "science@school.com",
    "contact_phone": "123-456-7890",
    "grade_levels": ["9", "10", "11", "12"],
    "subjects": [1, 2, 3],
    "office_hours": {
        "monday": "8:00-16:00",
        "tuesday": "8:00-16:00"
    }
}
```

#### Update Department
```http
PUT /api/departments/{id}
```
Request Body: Same as Create Department

#### Delete Department
```http
DELETE /api/departments/{id}
```

### High School Specific Endpoints

#### Grade Level Information
```http
GET /api/departments/{id}/grade-level/{grade}
```

#### Teacher Assignments
```http
GET /api/departments/{id}/teacher-assignments
```

#### Class Schedule
```http
GET /api/departments/{id}/class-schedule
```
Query Parameters:
- `grade`: Grade level
- `section`: Section name

#### Substitute Teachers
```http
GET /api/departments/{id}/substitute-teachers
```

#### Parent Notifications
```http
GET /api/departments/{id}/parent-notifications
```
Query Parameters:
- `grade`: Grade level

### Events Management

#### List Events
```http
GET /api/departments/{id}/events
```
Query Parameters:
- `type`: Event type
- `grade`: Grade level

#### Create Event
```http
POST /api/departments/{id}/events
```
Request Body:
```json
{
    "title": "Parent-Teacher Meeting",
    "description": "Annual parent-teacher meeting",
    "start_date": "2025-11-01T14:00:00",
    "end_date": "2025-11-01T16:00:00",
    "location": "School Auditorium",
    "event_type": "academic",
    "grade_level": ["9", "10"],
    "max_participants": 100,
    "requires_permission": false,
    "additional_info": {
        "bring": ["Student Report Cards"],
        "contact_person": "John Doe"
    }
}
```

### Announcements Management

#### List Announcements
```http
GET /api/departments/{id}/announcements
```
Query Parameters:
- `category`: Announcement category
- `audience`: Target audience
- `grade`: Grade level

#### Create Announcement
```http
POST /api/departments/{id}/announcements
```
Request Body:
```json
{
    "title": "Exam Schedule",
    "content": "Final exams schedule for Grade 10",
    "publish_date": "2025-11-01",
    "expiry_date": "2025-11-15",
    "priority": 5,
    "category": "academic",
    "target_audience": "students",
    "grade_level": ["10"],
    "requires_acknowledgment": true,
    "attachments": [
        {
            "name": "Schedule PDF",
            "url": "http://example.com/schedule.pdf"
        }
    ]
}
```

### Schedule Management

#### List Schedules
```http
GET /api/departments/{id}/schedules
```
Query Parameters:
- `grade_level`: Grade level
- `section`: Section name
- `day_of_week`: Day of week (0-6)
- `teacher_id`: Teacher ID

#### Create Schedule
```http
POST /api/departments/{id}/schedules
```
Request Body:
```json
{
    "subject_id": 1,
    "teacher_id": 1,
    "grade_level": "10",
    "section": "A",
    "day_of_week": 1,
    "period_type": "regular",
    "period_number": 1,
    "start_time": "08:00",
    "end_time": "09:00",
    "room": "Lab 101",
    "attendance_required": true
}
```

### Reports and Statistics

#### Academic Performance
```http
GET /api/departments/{id}/academic-performance
```
Query Parameters:
- `grade_level`: Grade level
- `section`: Section name
- `subject_id`: Subject ID
- `term`: Academic term

#### Attendance Report
```http
GET /api/departments/{id}/attendance
```
Query Parameters:
- `grade_level`: Grade level
- `section`: Section name
- `date`: Specific date
- `month`: Month number (1-12)

#### Generate Report
```http
GET /api/departments/{id}/report
```
Query Parameters:
- `type`: Report type (academic, attendance, behavior, performance)
- `grade_level`: Grade level
- `start_date`: Start date
- `end_date`: End date

## Error Responses

### 400 Bad Request
```json
{
    "message": "Validation failed",
    "errors": {
        "field": ["Error message"]
    }
}
```

### 401 Unauthorized
```json
{
    "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
    "message": "Access denied"
}
```

### 404 Not Found
```json
{
    "message": "Resource not found"
}
```

### 500 Server Error
```json
{
    "message": "Internal server error"
}
```
