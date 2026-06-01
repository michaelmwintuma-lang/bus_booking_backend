<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('branches')) {
            Schema::create('branches', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('branches', 'name')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }
        
        if (!Schema::hasColumn('branches', 'location')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->string('location')->nullable();
            });
        }
        
        if (!Schema::hasColumn('branches', 'phone')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->string('phone')->nullable();
            });
        }
        
        if (!Schema::hasColumn('branches', 'email')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->string('email')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['name', 'location', 'phone', 'email']);
        });
    }
};