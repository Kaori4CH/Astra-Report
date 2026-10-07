<?php

namespace Database\Seeders;

use App\Models\Dealer;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'Charles@gmail.com'],
            ['name' => 'Supervisor Astra', 'password' => 'password', 'role' => 'supervisor', ],
        );

        foreach (['DLP-001' => 'Janice@gmail.com', 'DLP-002' => 'Janice@gmail.com'] as $code => $email) {
            $dealer = Dealer::where('code', $code)->first();

            User::updateOrCreate(
                ['email' => $email],
                ['name' => 'User '.$dealer?->name, 'password' => 'password', 'role' => 'dealer', 'dealer_id' => $dealer?->id],
            );
        }
    }
}
