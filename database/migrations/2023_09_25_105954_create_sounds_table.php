<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sounds', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->integer('length')->nullable();
            $table->json('notes')->nullable();
            $table->foreignUlid('project_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sounds');
    }
};
