<?php
// bootstrap and toggle a task for quick testing
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Task;

$t = Task::first();
if (!$t) {
    echo "no tasks\n";
    exit;
}
$old = $t->status;
$t->update(['status' => $t->status === 'completed' ? 'idle' : 'completed']);
echo "toggled from $old to {$t->status}\n";
