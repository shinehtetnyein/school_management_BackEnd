<?php

return [
    // Default guard
    'default-guard' => 'api',

    // Common CRUD actions
    'common-actions' => ['view', 'create', 'edit', 'delete'],

    // ===============================
    // Permissions and Actions
    // ===============================
    'permissions' => [
        'users' => [
            'common-actions' => true,
            'actions' => ['assign-role', 'remove-role'],
        ],
        'roles' => [
            'common-actions' => true,
            'actions' => ['assign-permission', 'remove-permission'],
        ],
        'permissions' => [
            'common-actions' => true,
            'actions' => [],
        ],
        'courses' => [
            'common-actions' => true,
            'actions' => ['enroll', 'unenroll'],
        ],
        'subjects' => [
            'common-actions' => true,
            'actions' => [],
        ],
        'classrooms' => [
            'common-actions' => true,
            'actions' => ['schedule'],
        ],
        'timetables' => [
            'common-actions' => true,
            'actions' => [],
        ],
        'attendance' => [
            'common-actions' => true,
            'actions' => ['mark', 'verify'],
        ],
        'exams' => [
            'common-actions' => true,
            'actions' => ['grade'],
        ],
        'exam-results' => [
            'common-actions' => true,
            'actions' => [],
        ],
        'homework' => [
            'common-actions' => true,
            'actions' => ['assign', 'submit'],
        ],
        'events' => [
            'common-actions' => true,
            'actions' => ['approve', 'reject'],
        ],
        'books' => [
            'common-actions' => true,
            'actions' => ['borrow', 'return'],
        ],
        'borrow-records' => [
            'common-actions' => true,
            'actions' => ['fine', 'overdue'],
        ],
        'resources' => [
            'common-actions' => true,
            'actions' => ['allocate'],
        ],
        'payments' => [
            'common-actions' => true,
            'actions' => ['approve', 'reject'],
        ],
        'accounts' => [
            'common-actions' => true,
            'actions' => [],
        ],
        'communication' => [
            'common-actions' => true,
            'actions' => ['send', 'reply'],
        ],
        'enrollments' => [
            'common-actions' => true,
            'actions' => ['update-status'],
        ],
    ],

    // ===============================
    // Roles and Permissions
    // ===============================
    'roles' => [
        'Root Admin' => [
            'permissions' => [
                'users.view', 'users.create', 'users.edit', 'users.delete',
                'users.assign-role', 'users.remove-role',
                'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
                'roles.assign-permission', 'roles.remove-permission',
                'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',
                'courses.*',
                'subjects.*',
                'classrooms.*',
                'timetables.*',
                'attendance.*',
                'exams.*',
                'exam-results.*',
                'homework.*',
                'events.*',
                'books.*',
                'borrow-records.*',
                'resources.*',
                'payments.*',
                'accounts.*',
                'communication.*',
                'enrollments.*',
            ],
        ],

        'Admin' => [
            'permissions' => [
                'users.view', 'users.create', 'users.edit',
                'roles.view', 'permissions.view',
                'courses.view', 'courses.create', 'courses.edit', 'courses.enroll',
                'subjects.view', 'subjects.create', 'subjects.edit',
                'classrooms.view', 'classrooms.schedule',
                'timetables.view', 'timetables.create', 'timetables.edit',
                'attendance.view', 'attendance.mark', 'attendance.verify',
                'exams.view', 'exams.create', 'exams.edit',
                'exam-results.view', 'exam-results.create',
                'homework.view', 'homework.create', 'homework.edit', 'homework.assign',
                'events.view', 'events.approve', 'events.create',
                'books.view', 'books.create', 'books.edit',
                'borrow-records.view', 'borrow-records.create', 'borrow-records.fine',
                'resources.view', 'resources.allocate',
                'payments.view', 'payments.approve',
                'accounts.view', 'accounts.create',
                'communication.view', 'communication.send',
                'enrollments.view', 'enrollments.create', 'enrollments.update-status',
            ],
        ],

        'Teacher' => [
            'permissions' => [
                'courses.view', 'courses.edit',
                'subjects.view', 'subjects.edit',
                'classrooms.view',
                'timetables.view',
                'attendance.mark', 'attendance.verify',
                'exams.view', 'exams.grade', 'exams.edit',
                'exam-results.view', 'exam-results.create',
                'homework.view', 'homework.create', 'homework.edit', 'homework.assign',
                'events.view',
                'books.view', 'books.borrow', 'books.return',
                'borrow-records.view',
                'resources.view',
                'communication.view', 'communication.send',
                'enrollments.view',
            ],
        ],

        'Student' => [
            'permissions' => [
                'courses.view', 'courses.enroll', 'courses.unenroll',
                'subjects.view',
                'classrooms.view',
                'timetables.view',
                'attendance.view',
                'exams.view',
                'exam-results.view',
                'homework.view', 'homework.submit',
                'events.view',
                'books.view', 'books.borrow', 'books.return',
                'borrow-records.view',
                'resources.view',
                'payments.create',
                'accounts.view',
                'communication.view', 'communication.reply',
                'enrollments.view',
            ],
        ],

        'Guest' => [
            'permissions' => [
                'courses.view',
                'subjects.view',
                'events.view',
                'books.view',
                'communication.view',
            ],
        ],
    ],
];
