# Admin Module - User Management & Role/Permission System

Admin module provides comprehensive user management capabilities with role and permission assignment.

## Features

- ✅ User CRUD operations (Create, Read, Update, Delete)
- ✅ Assign/Remove roles to users
- ✅ Grant/Revoke permissions to users
- ✅ Get users by role
- ✅ Spatie Laravel Permission integration
- ✅ User role enumeration support

## Module Structure

```
Modules/Users/Admin/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AdminController.php
│   │   ├── Request/
│   │   │   ├── StoreUserRequest.php
│   │   │   ├── UpdateUserRequest.php
│   │   │   ├── AssignRoleRequest.php
│   │   │   └── AssignPermissionRequest.php
│   │   └── Resource/
│   │       └── AdminUserResource.php
│   ├── Models/ (inherits from core User model)
│   └── Providers/
│       └── AdminServiceProvider.php
├── Services/
│   ├── AdminApiServiceInterface.php
│   └── Implementations/
│       └── AdminApiService.php
├── routes/
│   └── api_v1.0.php
├── database/
│   └── seeders/
├── module.json
└── composer.json
```

## API Endpoints

### User Management (CRUD)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/admin/users` | List all users with pagination |
| POST | `/api/admin/users` | Create new user |
| GET | `/api/admin/users/{id}` | Get single user details |
| PATCH | `/api/admin/users/{id}` | Update user information |
| DELETE | `/api/admin/users/{id}` | Delete user |

### Role Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/admin/users/{id}/assign-role` | Assign role to user |
| POST | `/api/admin/users/{id}/remove-role` | Remove role from user |
| GET | `/api/admin/users/role/{roleName}` | Get all users with specific role |

### Permission Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/admin/users/{id}/grant-permission` | Grant permission to user |
| POST | `/api/admin/users/{id}/revoke-permission` | Revoke permission from user |

## Usage Examples

### Create User

**POST** `/api/admin/users`

```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john.doe@school.com",
  "password": "securepassword123",
  "password_confirmation": "securepassword123",
  "phone_no": "0911234567",
  "address": "123 Main Street",
  "city": "Yangon",
  "country": "Myanmar",
  "date_of_birth": "1990-01-15",
  "gender": "male",
  "status": "active",
  "role": "teacher"
}
```

### Update User

**PATCH** `/api/admin/users/{id}`

```json
{
  "first_name": "Jane",
  "city": "Mandalay",
  "status": "inactive"
}
```

### Assign Role

**POST** `/api/admin/users/{id}/assign-role`

```json
{
  "role": "admin"
}
```

### Remove Role

**POST** `/api/admin/users/{id}/remove-role`

```json
{
  "role": "teacher"
}
```

### Grant Permission

**POST** `/api/admin/users/{id}/grant-permission`

```json
{
  "permission": "create-course"
}
```

### Revoke Permission

**POST** `/api/admin/users/{id}/revoke-permission`

```json
{
  "permission": "delete-user"
}
```

### Get Users by Role

**GET** `/api/admin/users/role/teacher`

```json
{
  "success": true,
  "message": "Users with role 'teacher' retrieved successfully.",
  "data": [
    {
      "id": 1,
      "uuid": "uuid-string",
      "first_name": "Teacher",
      "last_name": "Name",
      "email": "teacher@school.com",
      "roles": ["teacher"],
      "permissions": ["create-course", "view-results"],
      "created_at": "2025-11-14T10:30:00Z",
      "updated_at": "2025-11-14T10:30:00Z"
    }
  ]
}
```

## Response Format

All endpoints return standardized JSON responses:

```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {
    "id": 1,
    "uuid": "uuid-string",
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@school.com",
    "phone_no": "0911234567",
    "address": "123 Main Street",
    "city": "Yangon",
    "country": "Myanmar",
    "date_of_birth": "1990-01-15",
    "gender": "male",
    "status": "active",
    "roles": ["teacher", "staff"],
    "permissions": ["view-results", "create-course"],
    "created_at": "2025-11-14T10:30:00Z",
    "updated_at": "2025-11-14T10:30:00Z"
  }
}
```

## Service Layer

The Admin module uses a service pattern with interface-based dependency injection:

```php
// Inject the service
public function __construct(AdminApiServiceInterface $adminService)
{
    $this->adminService = $adminService;
}

// Use service methods
$user = $this->adminService->store($data);
$this->adminService->assignRole($userId, 'admin');
$this->adminService->grantPermission($userId, 'delete-user');
```

## Available Service Methods

- `index(perPage = 10)` - Get paginated users
- `show(id)` - Get single user
- `store(data)` - Create user
- `update(id, data)` - Update user
- `destroy(id)` - Delete user
- `assignRole(userId, roleName)` - Assign role
- `removeRole(userId, roleName)` - Remove role
- `grantPermission(userId, permissionName)` - Grant permission
- `revokePermission(userId, permissionName)` - Revoke permission
- `getUsersByRole(roleName)` - Get users with role

## Integration with Core System

The Admin module integrates with:
- **User Model** - Core user model from `Modules\Users\User`
- **Spatie Laravel Permission** - Role and permission management
- **Role Enum** - Available roles defined in `App\Console\Enums\Role`

## Authentication

All endpoints require authentication via Sanctum:

```
Authorization: Bearer {token}
```

## Error Handling

The service implements comprehensive error handling with try-catch blocks and returns appropriate HTTP status codes:

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `404` - Not Found
- `500` - Server Error

## Notes

- Passwords are automatically hashed using Laravel's default hasher
- User UUIDs are automatically generated on creation
- All timestamps are automatically managed by Laravel
- Roles and permissions are managed through Spatie Laravel Permission package
