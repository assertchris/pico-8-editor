<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sprites', function (Blueprint $table) {
            $table->json('flags');
        });
    }

    public function down(): void
    {
        Schema::table('sprites', function (Blueprint $table) {
            $table->dropColumn('flags');
        });
    }
};
