<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationReviewer extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'Application Reviewer',
            'email' => 'applicationreviewer.caac@yopmail.com',
            'role_id' => 3, 
            'account_status' => 'active' // Assuming role_id 3 corresponds to the Application Reviewer role
        ]);
    }
}
