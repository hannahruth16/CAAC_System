<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoardApprover extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'Board Approver',
            'email' => 'boardapprover.caac@yopmail.com',
            'role_id' => 4, // Assuming role_id 2 corresponds to the Board Approver role
        ]);
    }
}
