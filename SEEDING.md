# Seeding roles, permissions and development users

This project uses Spatie's Roles & Permissions with a custom config file at `config/role&permission.php`.

Seeders included:

- `Database\Seeders\RolesAndPermissionsSeeder` - Creates permissions and roles based on `config/role&permission.php`.
- `Database\Seeders\DevUsersSeeder` - Creates development users and assigns roles.

Recommended local workflow

1. Make sure your local `.env` is configured for your local database.
2. Run migrations:

```powershell
php artisan migrate
```

3. Seed roles/permissions and dev users (safe for dev only):

```powershell
php artisan db:seed
```

This will run `DatabaseSeeder`, which calls `RolesAndPermissionsSeeder` followed by `DevUsersSeeder`.

Notes and safety

- `DevUsersSeeder` contains a guard to skip execution in `production` environment. It will only run when `app()->environment()` is not `production`.
# Seeding roles, permissions and development users

This project uses Spatie's Roles & Permissions with a custom config file at `config/role&permission.php`.

Seeders included:

- `Database\Seeders\RolesAndPermissionsSeeder` - Creates permissions and roles based on `config/role&permission.php`.
- `Database\Seeders\DevUsersSeeder` - Creates development users and assigns roles.

Recommended local workflow

1. Make sure your local `.env` is configured for your local database.
2. Run migrations:

```powershell
php artisan migrate
```

3. Seed roles/permissions and dev users (safe for dev only):

```powershell
php artisan db:seed
```

This will run `DatabaseSeeder`, which calls `RolesAndPermissionsSeeder` followed by `DevUsersSeeder`.

Notes and safety

- `DevUsersSeeder` contains a guard to skip execution in `production` environment. It will only run when `app()->environment()` is not `production`.
- Dev users created:
  - `root@example.com` — Root Admin
  - `admin@example.com` — Admin
  - `parent@example.com` — Parent
  - `librarian@example.com` — Librarian
  - `teacher@example.com` — Teacher
  - `student@example.com` — Student

All dev users use the password `password` by default. Change these credentials in `database/seeders/DevUsersSeeder.php` for your environment if desired.

Advanced

- To seed only roles and permissions:

```powershell
php artisan db:seed --class=Database\\Seeders\\RolesAndPermissionsSeeder
```

- To seed only dev users (make sure roles exist first):

```powershell
php artisan db:seed --class=Database\\Seeders\\DevUsersSeeder
```

If you want me to add sample factories or more test data, tell me what you'd like and I'll add it.
