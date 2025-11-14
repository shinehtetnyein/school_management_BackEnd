<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Modules\Users\User\App\Models\User;
use App\Console\Enums\Role;

$parents = User::whereHas('roles', function ($q) {
    $q->where('name', Role::PARENT->value);
})->get();

echo json_encode([
    'message' => 'Parents list',
    'data' => $parents
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
