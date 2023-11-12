<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('be_nullable', function (Blueprint $table) {
            $table->json('flags')->nullable(true)->change();
        });
    }

    public function down(): void
    {
        Schema::table('be_nullable', function (Blueprint $table) {
            $table->json('flags')->nullable(false)->change();
        });
    }
};
