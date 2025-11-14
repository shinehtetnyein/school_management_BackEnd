#!/bin/bash

# Course-Subject Setup Script
# Usage: bash setup-courses-subjects.sh

echo "========== COURSE-SUBJECT RELATIONSHIP SETUP =========="
echo ""
echo "This script will help you set up course-subject relationships."
echo "Running in Laravel Tinker..."
echo ""

php artisan tinker << 'EOF'

echo "\n=== STEP 1: CHECK CURRENT DATA ===\n";

$courseCount = Modules\Course\App\Models\Course::count();
$subjectCount = Modules\Subject\App\Models\Subject::count();
$pivotCount = DB::table('course_subject')->count();

echo "Total Courses: $courseCount\n";
echo "Total Subjects: $subjectCount\n";
echo "Current Course-Subject Links: $pivotCount\n";

echo "\n=== STEP 2: LIST ALL SUBJECTS ===\n";
$subjects = Modules\Subject\App\Models\Subject::orderBy('id')->get(['id', 'subject_code', 'subject_name']);
foreach ($subjects as $s) {
    echo $s->id . ". " . $s->subject_code . " - " . $s->subject_name . "\n";
}

echo "\n=== STEP 3: CURRENT ASSIGNMENTS ===\n";
foreach (Modules\Course\App\Models\Course::all() as $c) {
    $subjectIds = $c->subjects()->pluck('id')->toArray();
    $ids = !empty($subjectIds) ? implode(', ', $subjectIds) : 'NONE';
    echo "Course " . $c->id . " ({$c->course_name}): Subjects [$ids]\n";
}

echo "\n=== STEP 4: ASSIGNING DIFFERENT SUBJECTS TO EACH COURSE ===\n";

$courses = Modules\Course\App\Models\Course::all();

// Course 1: Subjects 1, 2, 3
if ($courses->count() >= 1) {
    $c1 = $courses->get(0);
    $c1->subjects()->sync([1, 2, 3]);
    echo "✓ Course {$c1->id} ({$c1->course_name}): Assigned subjects [1, 2, 3]\n";
}

// Course 2: Subjects 1, 2, 4, 5 (different from Course 1)
if ($courses->count() >= 2) {
    $c2 = $courses->get(1);
    $c2->subjects()->sync([1, 2, 4, 5]);
    echo "✓ Course {$c2->id} ({$c2->course_name}): Assigned subjects [1, 2, 4, 5]\n";
}

// Course 3: Subjects 3, 4, 5 (different from both)
if ($courses->count() >= 3) {
    $c3 = $courses->get(2);
    $c3->subjects()->sync([3, 4, 5]);
    echo "✓ Course {$c3->id} ({$c3->course_name}): Assigned subjects [3, 4, 5]\n";
}

echo "\n=== STEP 5: VERIFYING FINAL ASSIGNMENTS ===\n";
foreach (Modules\Course\App\Models\Course::all() as $c) {
    $count = $c->subjects()->count();
    $subjects = $c->subjects()->get(['id', 'subject_name']);
    echo "\nCourse {$c->id}: {$c->course_name} (Subjects: $count)\n";
    foreach ($subjects as $s) {
        echo "  ✓ [{$s->id}] {$s->subject_name}\n";
    }
}

echo "\n========== SETUP COMPLETE ==========\n";
echo "Test with: curl -X GET http://localhost:8000/api/v1/courses/{uuid}/subjects -H 'Authorization: Bearer TOKEN'\n";
echo "Each course should show ONLY its assigned subjects.\n";

EOF

echo ""
echo "Setup script completed!"
