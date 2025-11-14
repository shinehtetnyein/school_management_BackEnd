<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Modules\Users\User\App\Models\User;
use Illuminate\Support\Facades\DB;

$email = $argv[1] ?? 'john.anderson@school.com';
$user = User::where('email', $email)->first();
if (!$user) {
    echo "User with email {$email} not found\n";
    exit(1);
}

echo "User found: {$user->id} - {$user->first_name} {$user->last_name}\n";

$subjects = DB::table('subject_teacher')->where('user_id', $user->id)->get();
$courses = DB::table('course_teacher')->where('user_id', $user->id)->get();

echo "subject_teacher rows: " . $subjects->count() . "\n";
foreach ($subjects as $row) {
    echo json_encode((array)$row) . "\n";
}

echo "course_teacher rows: " . $courses->count() . "\n";
foreach ($courses as $row) {
    echo json_encode((array)$row) . "\n";
}
