<?php

namespace Jhavenz\NovaExtendedFields\Tests\fixtures;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;
    
    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => $this->faker->dateTimeBetween('-1 month', '-1 hour'),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9',
            'created_at' => $this->faker->dateTimeBetween('-3 years', '-2 months'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', '-1 hour'),
        ];
    }
}
