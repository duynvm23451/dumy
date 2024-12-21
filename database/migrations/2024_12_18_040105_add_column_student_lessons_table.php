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
        Schema::table("student_lessons", function($table) {
            $table->boolean("is_favorited")->default(0);
            $table->boolean("is_completed")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("student_lessons", function($table) {
            $table->dropColumn("is_favorited");
            $table->dropColumn("is_completed");
        });
    }
};
