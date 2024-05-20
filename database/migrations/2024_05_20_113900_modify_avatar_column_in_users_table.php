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
            $table->string('avatar')->default('https://res.cloudinary.com/drjjwnstk/image/upload/v1716200796/user-profile/avatar_qrn096.png')->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->default('https://res.cloudinary.com/drjjwnstk/image/upload/v1716200796/user-profile/avatar_qrn096.png')->change();

        });
    }
};
