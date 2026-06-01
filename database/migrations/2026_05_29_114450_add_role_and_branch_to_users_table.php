<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['customer', 'sub_admin', 'super_admin'])->default('customer');
        $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['branch_id_foreign']);
            $table->dropColumn(['branch_id', 'role']);
        });
    }
};
