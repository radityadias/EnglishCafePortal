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
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('internship_id')->nullable();
            $table->unsignedBigInteger('leave_type_id')->nullable();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('internship_id')->references('id')->on('internships');
            $table->foreign('leave_type_id')->references('id')->on('leave_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
