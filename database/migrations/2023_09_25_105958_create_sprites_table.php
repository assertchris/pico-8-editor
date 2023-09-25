<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sprites', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->json('pixels')->nullable();
            $table->foreignUlid('project_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sprites');
    }
};
