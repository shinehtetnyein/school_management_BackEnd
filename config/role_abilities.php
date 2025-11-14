<?php

// Define token abilities/scopes per role slug. These abilities are assigned to Sanctum tokens.
// Use '*' for full access or list named abilities for fine-grained control.
return [
    'root_admin' => ['*'],
    'admin'      => ['admin'],
    'teacher'    => ['teacher'],
    'student'    => ['student'],
    'parent'     => ['parent'],
    'librarian'  => ['librarian'],
    'guest'      => ['guest'],
    'accountant' => ['accountant'],
];
