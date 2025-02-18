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
            Schema::create('student_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('schedule_id'); // Add this line
                $table->json('report_data');
                $table->timestamps();

                $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('schedule_id')->references('id')->on('student_schedules')->onDelete('cascade'); // Add this line
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_reports');
    }
};
