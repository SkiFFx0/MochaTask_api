<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table)
        {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('size');
            $table->string('path');
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->SoftDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
