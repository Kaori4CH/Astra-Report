<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('dealer')->after('password')->comment('dealer atau supervisor');
            $table->foreignId('dealer_id')->nullable()->after('role')->constrained('dealers')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dealer_id');
            $table->dropColumn('role');
        });
    }
};
