<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

$app->boot();

$teachers = \Modules\Users\User\App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'teacher');
})->get();

echo "Total Teachers: " . count($teachers) . "\n";

foreach ($teachers as $teacher) {
    echo "- " . $teacher->first_name . " " . $teacher->last_name . " (" . $teacher->email . ")\n";
}
