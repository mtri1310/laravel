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
        Schema::table('films', function (Blueprint $table) {
            // Thêm cột 'link_trailer' kiểu string, có thể null, sau cột 'story_line'
            $table->string('link_trailer')->nullable()->after('story_line');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('films', function (Blueprint $table) {
            // Xóa cột 'link_trailer' nếu rollback
            $table->dropColumn('link_trailer');
        });
    }
};
