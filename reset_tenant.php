<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'faraz@gmail.com';
$password = 'tenant@123';

$u = \App\Models\User::where('email', $email)->first();
if (!$u) {
    echo "Faraz not found, finding any tenant user...\n";
    $u = \App\Models\User::whereNotNull('tenant_id')->first();
    if (!$u) {
        echo "No tenant user exists. Creating one for Tenant #1...\n";
        $tenant = \App\Models\Tenant::first();
        if (!$tenant) { die("No tenant either. Abort.\n"); }
        $u = \App\Models\User::create([
            'name' => 'Faraz',
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'role' => 'tenant_owner',
            'tenant_id' => $tenant->id,
            'status' => true,
            'phone' => '03233323324',
        ]);
        echo "Created Faraz tenant user for tenant #{$tenant->id}\n";
    }
}
$u->password = \Illuminate\Support\Facades\Hash::make($password);
$u->save();
echo "SUCCESS:\n";
echo "  Email: {$u->email}\n";
echo "  Password: {$password}\n";
echo "  Tenant ID: {$u->tenant_id}\n";
echo "  Role: {$u->role}\n";
echo "  Status: " . ($u->status ? 'Active' : 'Inactive') . "\n";
