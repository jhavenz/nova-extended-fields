<?php

namespace Jhavenz\NovaExtendedFields\Tests\fixtures;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->count(10)->create();
    }
}
