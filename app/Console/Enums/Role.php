<?php

namespace App\Console\Enums;

enum Role: string
{
    case ROOT_ADMIN = 'root_admin';
    case TEACHER = 'teacher';
    case PARENT = 'parent';
    case LIBRARIAN = 'librarian';
    case ACCOUNTANT = 'accountant';
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case GUEST = 'guest';

    /**
     * Get the formatted role name.
     */
    public function label(): string
    {
        return match ($this) {
            self::ROOT_ADMIN => 'Root Admin',
            self::TEACHER => 'Teacher',
            self::PARENT => 'Parent',
            self::LIBRARIAN => 'Librarian',
            self::ACCOUNTANT => 'Accountant',
            self::ADMIN => 'Admin',
            self::STUDENT => 'Student',
            self::GUEST => 'Guest',
        };
    }

    /**
     * Get all role values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all role labels.
     */
    public static function labels(): array
    {
        return array_map(fn($role) => $role->label(), self::cases());
    }
}
