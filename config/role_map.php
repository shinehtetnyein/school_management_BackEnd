<?php

// Map simple role slugs used by API clients to DB/spatie role names.
// This mirrors the `App\\Console\\Enums\\Role` labels. Keep in sync with the enum.
return [
    'root_admin' => 'Root Admin',
    'admin'      => 'Admin',
    'teacher'    => 'Teacher',
    'student'    => 'Student',
    'parent'     => 'Parent',
    'librarian'  => 'Librarian',
    'guest'      => 'Guest',
    'accountant' => 'Accountant',
];
