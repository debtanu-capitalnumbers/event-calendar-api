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
        Schema::create('event_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description');
            $table->string('location', 100);
            $table->string('file_name', 100)->nullable();
            $table->string('file_path', 200)->nullable();
            $table->string('event_category', 100);
            $table->date('event_start_date');
            $table->time('event_start_time');
            $table->time('event_end_time');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_calendars');
    }
};
