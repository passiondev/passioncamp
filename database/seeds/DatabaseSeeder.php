<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(User::class)->states(['superAdmin'])->create([
            'email' => 'matt.floyd@268generation.com',
        ]);
    }
}
