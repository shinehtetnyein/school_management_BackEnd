<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Modules\Users\User\App\Models\User;
use Modules\Course\App\Models\Course;
use Modules\Subject\App\Models\Subject;
use App\Console\Enums\Role;

echo "\n========== API RESULTS SORTED BY ID ==========\n\n";

// Parents
echo "👨‍👩‍👧 PARENTS (sorted by ID):\n";
$parents = User::whereHas('roles', function ($q) {
    $q->where('name', Role::PARENT->value);
})->orderBy('id', 'asc')->get(['id', 'first_name', 'last_name', 'email']);
foreach ($parents as $p) {
    echo "  [{$p->id}] {$p->first_name} {$p->last_name} ({$p->email})\n";
}

// Students
echo "\n🎓 STUDENTS (sorted by ID):\n";
$students = User::whereHas('roles', function ($q) {
    $q->where('name', Role::STUDENT->value);
})->orderBy('id', 'asc')->get(['id', 'first_name', 'last_name', 'email']);
foreach ($students as $s) {
    echo "  [{$s->id}] {$s->first_name} {$s->last_name} ({$s->email})\n";
}

// Teachers
echo "\n👨‍🏫 TEACHERS (sorted by ID):\n";
$teachers = User::whereHas('roles', function ($q) {
    $q->where('name', Role::TEACHER->value);
})->orderBy('id', 'asc')->get(['id', 'first_name', 'last_name', 'email']);
foreach ($teachers as $t) {
    echo "  [{$t->id}] {$t->first_name} {$t->last_name} ({$t->email})\n";
}

// Courses
echo "\n📚 COURSES (sorted by ID):\n";
$courses = Course::orderBy('id', 'asc')->get(['id', 'course_name', 'category']);
foreach ($courses as $c) {
    echo "  [{$c->id}] {$c->course_name} ({$c->category})\n";
}

// Subjects
echo "\n📖 SUBJECTS (sorted by ID):\n";
$subjects = Subject::orderBy('id', 'asc')->get(['id', 'subject_name', 'subject_code']);
foreach ($subjects as $subj) {
    echo "  [{$subj->id}] {$subj->subject_name} ({$subj->subject_code})\n";
}

echo "\n==========================================\n\n";
