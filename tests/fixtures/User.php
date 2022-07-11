<?php

namespace Jhavenz\NovaExtendedFields\Tests\fixtures;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'immutable_datetime',
        'password' => 'string',
    ];

    protected static function booted()
    {
        static::creating(function () {
            if (!count(Schema::connection('testing')->getAllTables())) {
                migrate();
            }
        });
    }

    public static function migrate(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    protected static function newFactory(): UserFactory
    {
        return new UserFactory;
    }
}
